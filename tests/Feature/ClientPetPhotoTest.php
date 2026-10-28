<?php

use App\Models\Client;
use App\Models\Pet;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('stores a client pet photo and saves a database-compatible gender', function () {
    Storage::fake('public');

    $client = Client::create([
        'name' => 'Ana',
        'last_name' => 'García',
        'phone' => '555123456',
        'address' => 'Calle 123',
        'email' => 'ana@example.com',
        'password' => 'secret123',
    ]);

    $response = $this->actingAs($client, 'client')->post(route('client.pets.store'), [
        'name' => 'Max',
        'breed' => 'Labrador',
        'age' => 3,
        'weight' => 25,
        'gender' => 'Male',
        'allergic' => false,
        'photo' => UploadedFile::fake()->image('max.jpg'),
    ]);

    $response->assertRedirect(route('client.pets.index'));

    $pet = Pet::where('client_id', $client->id)->firstOrFail();
    expect($pet->gender)->toBe('Male');
    expect($pet->photo)->not->toBeNull();
    Storage::disk('public')->assertExists($pet->photo);
});

it('rejects pet photos that are 2 MB or larger', function () {
    $client = Client::create([
        'name' => 'Ana',
        'last_name' => 'García',
        'phone' => '555123456',
        'address' => 'Calle 123',
        'email' => 'ana@example.com',
        'password' => 'secret123',
    ]);

    $response = $this->actingAs($client, 'client')->post(route('client.pets.store'), [
        'name' => 'Max',
        'breed' => 'Labrador',
        'age' => 3,
        'weight' => 25,
        'gender' => 'Male',
        'allergic' => false,
        'photo' => UploadedFile::fake()->image('max.jpg')->size(2048),
    ]);

    $response->assertSessionHasErrors('photo');
    expect(Pet::where('client_id', $client->id)->exists())->toBeFalse();
});

it('allows a client to view and update their pet edit form', function () {
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

    $this->actingAs($client, 'client')
        ->get(route('client.pets.edit', $pet))
        ->assertOk()
        ->assertSee('Editar mascota')
        ->assertSee('value="Max"', false);

    $response = $this->actingAs($client, 'client')->put(route('client.pets.update', $pet), [
        'name' => 'Maxi',
        'breed' => 'Labrador',
        'age' => 4,
        'weight' => 26,
        'gender' => 'Male',
        'allergic' => false,
        'observations' => 'Actualizado',
    ]);

    $response->assertRedirect(route('client.pets.index'));
    expect($pet->fresh()->name)->toBe('Maxi');
    expect($pet->fresh()->observations)->toBe('Actualizado');
});
