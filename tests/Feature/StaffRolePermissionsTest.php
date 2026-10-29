<?php

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Pet;
use App\Models\Role;
use App\Models\Service;
use App\Models\User;

function createRoleAccessFixture(string $roleName): array
{
    $role = Role::create(['name' => $roleName]);
    $user = User::factory()->create(['role_id' => $role->id]);
    $client = Client::create([
        'name' => 'Ana',
        'last_name' => 'García',
        'phone' => '555123456',
        'address' => 'Calle 123',
        'email' => strtolower($roleName).'@example.com',
        'password' => 'secret123',
    ]);
    $pet = $client->pets()->create([
        'name' => 'Max',
        'breed' => 'Labrador',
        'age' => 3,
        'weight' => 25,
        'gender' => 'Male',
        'allergic' => true,
        'observations' => 'Alergia conocida',
    ]);
    $service = Service::create([
        'name' => 'Baño',
        'type' => 'Peluquería',
        'price' => 25,
        'duration' => 60,
    ]);
    $appointment = $client->appointments()->create([
        'pet_id' => $pet->id,
        'date' => now()->addDays(2)->toDateString(),
        'time' => '12:00',
        'status' => 'pending',
        'user_id' => $user->id,
    ]);
    $appointment->services()->attach($service);

    return compact('user', 'client', 'pet', 'service', 'appointment');
}

it('allows employees to consult the agenda and pet records without edit forms', function () {
    $data = createRoleAccessFixture('Empleado');

    $this->actingAs($data['user'])
        ->get(route('appointments.index'))
        ->assertOk()
        ->assertSee('Listado de citas')
        ->assertSee('Ver')
        ->assertDontSee('Programar una cita');

    $this->actingAs($data['user'])
        ->get(route('appointments.show', $data['appointment']))
        ->assertOk()
        ->assertSee('Detalle de cita');

    $this->actingAs($data['user'])
        ->get(route('pets.index'))
        ->assertOk()
        ->assertSee('Max')
        ->assertDontSee('Añadir Nueva Mascota');

    $this->actingAs($data['user'])
        ->get(route('pets.show', $data['pet']))
        ->assertOk()
        ->assertSee('Ficha de mascota')
        ->assertSee('Alergia conocida');

    $this->actingAs($data['user'])->get(route('clients.index'))->assertForbidden();
    $this->actingAs($data['user'])->get(route('services.index'))->assertForbidden();
});

it('prevents employees from creating, editing, or deleting appointments and pets', function () {
    $data = createRoleAccessFixture('Empleado');

    $this->actingAs($data['user'])
        ->get(route('appointments.edit', $data['appointment']))
        ->assertForbidden();

    $this->actingAs($data['user'])
        ->post(route('appointments.store'), [])
        ->assertForbidden();

    $this->actingAs($data['user'])
        ->delete(route('appointments.destroy', $data['appointment']))
        ->assertForbidden();

    $this->actingAs($data['user'])
        ->get(route('pets.edit', $data['pet']))
        ->assertForbidden();

    $this->actingAs($data['user'])
        ->post(route('pets.store'), [])
        ->assertForbidden();

    $this->actingAs($data['user'])
        ->delete(route('pets.destroy', $data['pet']))
        ->assertForbidden();
});

it('allows employees to assign each authorized appointment status', function () {
    $data = createRoleAccessFixture('Empleado');

    foreach (['pending', 'completed', 'canceled'] as $status) {
        $this->actingAs($data['user'])
            ->patch(route('appointments.changeStatus', $data['appointment']), [
                'status' => $status,
            ])
            ->assertRedirect(route('appointments.index'));

        expect($data['appointment']->fresh()->status)->toBe($status);
    }
});

it('allows receptionists to manage clients and create or edit appointments but not delete them', function () {
    $data = createRoleAccessFixture('Recepcionista');

    $this->actingAs($data['user'])
        ->get(route('clients.index'))
        ->assertOk();

    $this->actingAs($data['user'])
        ->get(route('appointments.edit', $data['appointment']))
        ->assertOk();

    $this->actingAs($data['user'])
        ->post(route('appointments.store'), [
            'date' => now()->addDays(3)->toDateString(),
            'time' => '13:00',
            'status' => 'pending',
            'client_id' => $data['client']->id,
            'pet_id' => $data['pet']->id,
            'user_id' => $data['user']->id,
            'service_ids' => [$data['service']->id],
        ])
        ->assertRedirect(route('appointments.index'));

    $this->actingAs($data['user'])
        ->put(route('appointments.update', $data['appointment']), [
            'date' => $data['appointment']->date,
            'time' => $data['appointment']->time,
            'status' => 'pending',
            'client_id' => $data['client']->id,
            'pet_id' => $data['pet']->id,
            'user_id' => $data['user']->id,
            'service_ids' => [$data['service']->id],
        ])
        ->assertRedirect(route('appointments.index'));

    $this->actingAs($data['user'])
        ->delete(route('appointments.destroy', $data['appointment']))
        ->assertForbidden();

    $this->actingAs($data['user'])
        ->get(route('services.index'))
        ->assertForbidden();
});
