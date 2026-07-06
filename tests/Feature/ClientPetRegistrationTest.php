<?php

use App\Models\Client;
use App\Models\Pet;
use App\Models\Role;
use App\Models\User;

it('creates a client and a pet from the same registration form', function () {
    Role::create(['name' => 'Administrador']);

    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/clients', [
        'name' => 'Ana',
        'last_name' => 'García',
        'phone' => '555123456',
        'address' => 'Calle 123',
        'email' => 'ana@example.com',
        'password' => 'secret123',
        'pet_name' => 'Max',
        'pet_breed' => 'Labrador',
        'pet_age' => 3,
        'pet_weight' => 25,
        'pet_gender' => 'Male',
        'pet_allergic' => false,
        'pet_observations' => 'Muy activo',
    ]);

    $this->assertEquals(302, $response->getStatusCode());

    $client = Client::where('email', 'ana@example.com')->first();
    expect($client)->not->toBeNull();
    expect(Pet::where('client_id', $client->id)->where('name', 'Max')->exists())->toBeTrue();
});

it('allows creating a client and pet without a password', function () {
    Role::create(['name' => 'Administrador']);

    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/clients', [
        'name' => 'Luis',
        'last_name' => 'Mendoza',
        'phone' => '555654321',
        'address' => 'Avenida 456',
        'email' => 'luis@example.com',
        'pet_name' => 'Nina',
        'pet_breed' => 'Poodle',
        'pet_age' => 2,
        'pet_weight' => 8,
        'pet_gender' => 'Female',
        'pet_allergic' => true,
        'pet_observations' => 'Juguetona',
    ]);

    $this->assertEquals(302, $response->getStatusCode());

    $client = Client::where('email', 'luis@example.com')->first();
    expect($client)->not->toBeNull();
    expect($client->password)->toBeNull();
    expect(Pet::where('client_id', $client->id)->where('name', 'Nina')->exists())->toBeTrue();
});
