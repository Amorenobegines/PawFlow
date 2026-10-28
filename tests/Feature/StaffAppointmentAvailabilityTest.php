<?php

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Role;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Carbon;

function createStaffBookingData(): array
{
    $role = Role::create(['name' => 'Recepcionista']);
    $staff = User::factory()->create(['role_id' => $role->id]);
    $anotherStaff = User::factory()->create(['role_id' => $role->id]);
    $client = Client::create([
        'name' => 'Ana',
        'last_name' => 'García',
        'phone' => '555123456',
        'address' => 'Calle 123',
        'email' => 'staff-booking@example.com',
        'password' => 'secret123',
    ]);
    $pet = $client->pets()->create([
        'name' => 'Max',
        'breed' => 'Labrador',
        'age' => 3,
        'weight' => 25,
        'gender' => 'Male',
    ]);
    $service = Service::create([
        'name' => 'Baño',
        'type' => 'Peluquería',
        'price' => 25,
        'duration' => 60,
    ]);
    $shortService = Service::create([
        'name' => 'Uñas',
        'type' => 'Higiene',
        'price' => 10,
        'duration' => 30,
    ]);

    return compact('staff', 'anotherStaff', 'client', 'pet', 'service', 'shortService');
}

it('blocks staff booking times that overlap and ignores the appointment being edited', function () {
    $data = createStaffBookingData();
    $date = now('Europe/Madrid')->addDay()->toDateString();
    $booked = $data['client']->appointments()->create([
        'pet_id' => $data['pet']->id,
        'date' => $date,
        'time' => '17:00',
        'status' => 'pending',
        'user_id' => $data['staff']->id,
    ]);
    $booked->services()->attach($data['service']);

    $this->actingAs($data['staff'])
        ->get(route('appointments.index'))
        ->assertOk()
        ->assertSee('data-availability-url');

    $this->actingAs($data['staff'])
        ->getJson(route('appointments.availability', [
            'date' => $date,
            'user_id' => $data['staff']->id,
            'service_ids' => [$data['shortService']->id],
        ]))
        ->assertOk()
        ->assertJsonPath('unavailable_times', ['17:00', '17:30']);

    $this->actingAs($data['staff'])
        ->getJson(route('appointments.availability', [
            'date' => $date,
            'user_id' => $data['anotherStaff']->id,
            'service_ids' => [$data['shortService']->id],
        ]))
        ->assertOk()
        ->assertJsonPath('unavailable_times', []);

    $this->actingAs($data['staff'])
        ->put(route('appointments.update', $booked), [
            'date' => $date,
            'time' => '17:00',
            'status' => 'pending',
            'client_id' => $data['client']->id,
            'pet_id' => $data['pet']->id,
            'user_id' => $data['staff']->id,
            'service_ids' => [$data['service']->id],
        ])
        ->assertRedirect(route('appointments.index'))
        ->assertSessionHasNoErrors();

    $this->actingAs($data['staff'])
        ->post(route('appointments.store'), [
            'date' => $date,
            'time' => '17:30',
            'status' => 'pending',
            'client_id' => $data['client']->id,
            'pet_id' => $data['pet']->id,
            'user_id' => $data['staff']->id,
            'service_ids' => [$data['shortService']->id],
        ])
        ->assertSessionHasErrors('time');

    expect(Appointment::whereDate('date', $date)->count())->toBe(1);
});

it('does not allow staff to book a time that has already passed', function () {
    Carbon::setTestNow(Carbon::parse('2026-10-27 17:00:00', 'Europe/Madrid'));
    $data = createStaffBookingData();

    $availability = $this->actingAs($data['staff'])
        ->getJson(route('appointments.availability', [
            'date' => '2026-10-27',
            'user_id' => $data['staff']->id,
            'service_ids' => [$data['shortService']->id],
        ]))
        ->assertOk();

    expect($availability->json('past_times'))
        ->toHaveCount(17)
        ->toContain('16:30', '17:00')
        ->not->toContain('17:30');

    $this->actingAs($data['staff'])
        ->post(route('appointments.store'), [
            'date' => '2026-10-27',
            'time' => '16:30',
            'status' => 'pending',
            'client_id' => $data['client']->id,
            'pet_id' => $data['pet']->id,
            'user_id' => $data['staff']->id,
            'service_ids' => [$data['shortService']->id],
        ])
        ->assertSessionHasErrors('time');

    expect(Appointment::count())->toBe(0);
    Carbon::setTestNow();
});
