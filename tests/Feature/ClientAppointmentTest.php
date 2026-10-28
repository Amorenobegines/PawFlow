<?php

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Role;
use App\Models\Service;
use App\Models\User;

it('allows a client to request an appointment and see it in their appointment list', function () {
    $client = Client::create([
        'name' => 'Ana',
        'last_name' => 'García',
        'phone' => '555123456',
        'address' => 'Calle 123',
        'email' => 'ana@example.com',
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
    $role = Role::create(['name' => 'Empleado']);
    $employee = User::factory()->create(['role_id' => $role->id]);
    $otherClient = Client::create([
        'name' => 'Luis',
        'last_name' => 'Pérez',
        'phone' => '555654321',
        'address' => 'Avenida 456',
        'email' => 'luis@example.com',
        'password' => 'secret123',
    ]);
    $otherPet = $otherClient->pets()->create([
        'name' => 'Nina',
        'breed' => 'Poodle',
        'age' => 2,
        'weight' => 8,
        'gender' => 'Female',
    ]);
    $otherClient->appointments()->create([
        'pet_id' => $otherPet->id,
        'date' => now()->addDay()->toDateString(),
        'time' => '11:00',
        'status' => 'pending',
        'user_id' => null,
    ]);

    $this->actingAs($client, 'client')
        ->get(route('client.appointments.create'))
        ->assertOk()
        ->assertSee('Reservar una cita')
        ->assertSee('Baño');

    $response = $this->actingAs($client, 'client')->post(route('client.appointments.store'), [
        'pet_id' => $pet->id,
        'service_ids' => [$service->id],
        'user_id' => $employee->id,
        'date' => now()->addDay()->toDateString(),
        'time' => '10:00',
    ]);

    $response->assertRedirect(route('client.appointments.index'));
    $appointment = $client->appointments()->with('services')->firstOrFail();

    expect($appointment->client_id)->toBe($client->id)
        ->and($appointment->pet_id)->toBe($pet->id)
        ->and($appointment->status)->toBe('pending')
        ->and($appointment->user_id)->toBe($employee->id)
        ->and($appointment->services->modelKeys())->toBe([$service->id]);

    $this->actingAs($client, 'client')
        ->get(route('client.appointments.index'))
        ->assertOk()
        ->assertSee('Mis citas')
        ->assertSee('Reservada')
        ->assertSee($employee->name)
        ->assertSee('Baño')
        ->assertSee('Max')
        ->assertDontSee('Nina');
});

it('does not allow a client to request an appointment for another clients pet', function () {
    $client = Client::create([
        'name' => 'Ana',
        'last_name' => 'García',
        'phone' => '555123456',
        'address' => 'Calle 123',
        'email' => 'ana@example.com',
        'password' => 'secret123',
    ]);
    $otherClient = Client::create([
        'name' => 'Luis',
        'last_name' => 'Pérez',
        'phone' => '555654321',
        'address' => 'Avenida 456',
        'email' => 'luis@example.com',
        'password' => 'secret123',
    ]);
    $otherClientPet = $otherClient->pets()->create([
        'name' => 'Nina',
        'breed' => 'Poodle',
        'age' => 2,
        'weight' => 8,
        'gender' => 'Female',
    ]);
    $service = Service::create([
        'name' => 'Baño',
        'type' => 'Peluquería',
        'price' => 25,
        'duration' => 60,
    ]);
    $role = Role::create(['name' => 'Empleado']);
    $employee = User::factory()->create(['role_id' => $role->id]);

    $response = $this->actingAs($client, 'client')->post(route('client.appointments.store'), [
        'pet_id' => $otherClientPet->id,
        'service_ids' => [$service->id],
        'user_id' => $employee->id,
        'date' => now()->addDay()->toDateString(),
        'time' => '10:00',
    ]);

    $response->assertSessionHasErrors('pet_id');
    expect(Appointment::count())->toBe(0);
});

it('blocks times with active reservations and frees them when canceled', function () {
    $client = Client::create([
        'name' => 'Ana',
        'last_name' => 'García',
        'phone' => '555123456',
        'address' => 'Calle 123',
        'email' => 'ana@example.com',
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
    $trimService = Service::create([
        'name' => 'Corte',
        'type' => 'Peluquería',
        'price' => 20,
        'duration' => 30,
    ]);
    $nailService = Service::create([
        'name' => 'Uñas',
        'type' => 'Higiene',
        'price' => 10,
        'duration' => 30,
    ]);
    $role = Role::create(['name' => 'Empleado']);
    $employee = User::factory()->create(['role_id' => $role->id]);
    $otherEmployee = User::factory()->create(['role_id' => $role->id]);
    $date = now()->addDay()->toDateString();
    $otherClient = Client::create([
        'name' => 'Luis',
        'last_name' => 'Pérez',
        'phone' => '555654321',
        'address' => 'Avenida 456',
        'email' => 'luis@example.com',
        'password' => 'secret123',
    ]);
    $otherPet = $otherClient->pets()->create([
        'name' => 'Nina',
        'breed' => 'Poodle',
        'age' => 2,
        'weight' => 8,
        'gender' => 'Female',
    ]);
    $bookedAppointment = $otherClient->appointments()->create([
        'pet_id' => $otherPet->id,
        'date' => $date,
        'time' => '10:00',
        'status' => 'pending',
        'user_id' => $employee->id,
    ]);
    $bookedAppointment->services()->attach($service);
    $client->appointments()->create([
        'pet_id' => $pet->id,
        'date' => $date,
        'time' => '10:30',
        'status' => 'canceled',
        'user_id' => null,
    ]);

    $this->actingAs($client, 'client')
        ->getJson(route('client.appointments.availability', [
            'date' => $date,
            'user_id' => $employee->id,
            'service_ids' => [$trimService->id, $nailService->id],
        ]))
        ->assertOk()
        ->assertExactJson(['unavailable_times' => ['09:30', '10:00', '10:30'], 'past_times' => []]);

    $this->actingAs($client, 'client')
        ->getJson(route('client.appointments.availability', [
            'date' => $date,
            'user_id' => $otherEmployee->id,
            'service_ids' => [$trimService->id, $nailService->id],
        ]))
        ->assertOk()
        ->assertExactJson(['unavailable_times' => [], 'past_times' => []]);

    $bookingData = [
        'pet_id' => $pet->id,
        'service_ids' => [$trimService->id, $nailService->id],
        'user_id' => $employee->id,
        'date' => $date,
        'time' => '10:30',
    ];

    $this->actingAs($client, 'client')
        ->post(route('client.appointments.store'), $bookingData)
        ->assertSessionHasErrors('time');

    expect(Appointment::where('date', $date)
        ->where('time', '10:30')
        ->where('status', '!=', 'canceled')
        ->count())->toBe(0);

    $bookedAppointment->update(['status' => 'canceled']);

    $this->actingAs($client, 'client')
        ->post(route('client.appointments.store'), $bookingData)
        ->assertRedirect(route('client.appointments.index'));

    expect(Appointment::where('date', $date)
        ->where('time', '10:30')
        ->where('status', '!=', 'canceled')
        ->count())->toBe(1);
});

it('rejects appointment times that have already passed today', function () {
    $this->travelTo(now('Europe/Madrid')->setTime(15, 15));

    $client = Client::create([
        'name' => 'Ana',
        'last_name' => 'García',
        'phone' => '555123456',
        'address' => 'Calle 123',
        'email' => 'ana@example.com',
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
    $role = Role::create(['name' => 'Empleado']);
    $employee = User::factory()->create(['role_id' => $role->id]);

    $this->actingAs($client, 'client')
        ->getJson(route('client.appointments.availability', [
            'date' => now('Europe/Madrid')->toDateString(),
            'user_id' => $employee->id,
            'service_ids' => [$service->id],
        ]))
        ->assertOk()
        ->assertJsonPath('past_times.0', '09:00')
        ->assertJsonMissing(['past_times' => ['15:30']]);

    $this->actingAs($client, 'client')
        ->post(route('client.appointments.store'), [
            'pet_id' => $pet->id,
            'service_ids' => [$service->id],
            'user_id' => $employee->id,
            'date' => now('Europe/Madrid')->toDateString(),
            'time' => '10:00',
        ])
        ->assertSessionHasErrors('time');

    expect(Appointment::count())->toBe(0);
});

it('allows a client to modify and cancel their own future appointment', function () {
    $client = Client::create([
        'name' => 'Ana',
        'last_name' => 'García',
        'phone' => '555123456',
        'address' => 'Calle 123',
        'email' => 'ana@example.com',
        'password' => 'secret123',
    ]);
    $pet = $client->pets()->create([
        'name' => 'Max',
        'breed' => 'Labrador',
        'age' => 3,
        'weight' => 25,
        'gender' => 'Male',
    ]);
    $otherPet = $client->pets()->create([
        'name' => 'Nina',
        'breed' => 'Poodle',
        'age' => 2,
        'weight' => 8,
        'gender' => 'Female',
    ]);
    $firstService = Service::create([
        'name' => 'Baño',
        'type' => 'Peluquería',
        'price' => 25,
        'duration' => 60,
    ]);
    $secondService = Service::create([
        'name' => 'Corte',
        'type' => 'Peluquería',
        'price' => 30,
        'duration' => 45,
    ]);
    $role = Role::create(['name' => 'Empleado']);
    $employee = User::factory()->create(['role_id' => $role->id]);
    $newDate = now('Europe/Madrid')->addDays(2)->toDateString();
    $appointment = $client->appointments()->create([
        'pet_id' => $pet->id,
        'date' => now('Europe/Madrid')->addDay()->toDateString(),
        'time' => '10:00',
        'status' => 'pending',
        'user_id' => $employee->id,
    ]);
    $appointment->services()->attach($firstService);

    $this->actingAs($client, 'client')
        ->get(route('client.appointments.edit', $appointment))
        ->assertOk()
        ->assertSee('Modificar cita')
        ->assertSee('Max · Labrador')
        ->assertSee('10:00');

    $this->actingAs($client, 'client')
        ->put(route('client.appointments.update', $appointment), [
            'pet_id' => $otherPet->id,
            'service_ids' => [$secondService->id],
            'user_id' => $employee->id,
            'date' => $newDate,
            'time' => '11:00',
        ])
        ->assertRedirect(route('client.appointments.index'));

    $appointment->refresh()->load('services');
    expect($appointment->pet_id)->toBe($otherPet->id)
        ->and($appointment->date)->toBe($newDate)
        ->and(substr((string) $appointment->time, 0, 5))->toBe('11:00')
        ->and($appointment->services->modelKeys())->toBe([$secondService->id]);

    $this->actingAs($client, 'client')
        ->delete(route('client.appointments.destroy', $appointment))
        ->assertRedirect(route('client.appointments.index'));

    expect($appointment->fresh()->status)->toBe('canceled');
});

it('does not allow a client to modify or cancel another clients appointment', function () {
    $client = Client::create([
        'name' => 'Ana',
        'last_name' => 'García',
        'phone' => '555123456',
        'address' => 'Calle 123',
        'email' => 'ana@example.com',
        'password' => 'secret123',
    ]);
    $otherClient = Client::create([
        'name' => 'Luis',
        'last_name' => 'Pérez',
        'phone' => '555654321',
        'address' => 'Avenida 456',
        'email' => 'luis@example.com',
        'password' => 'secret123',
    ]);
    $otherPet = $otherClient->pets()->create([
        'name' => 'Nina',
        'breed' => 'Poodle',
        'age' => 2,
        'weight' => 8,
        'gender' => 'Female',
    ]);
    $appointment = $otherClient->appointments()->create([
        'pet_id' => $otherPet->id,
        'date' => now('Europe/Madrid')->addDay()->toDateString(),
        'time' => '10:00',
        'status' => 'pending',
    ]);

    $this->actingAs($client, 'client')
        ->get(route('client.appointments.edit', $appointment))
        ->assertNotFound();

    $this->actingAs($client, 'client')
        ->delete(route('client.appointments.destroy', $appointment))
        ->assertNotFound();

    expect($appointment->fresh()->status)->toBe('pending');
});
