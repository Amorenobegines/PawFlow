<?php

use App\Models\Client;
use App\Models\Role;
use App\Models\User;

it('allows clients to sign in through the standard login form', function () {
    $client = Client::create([
        'name' => 'Ana',
        'last_name' => 'García',
        'phone' => '555123456',
        'address' => 'Calle 123',
        'email' => 'ana@example.com',
        'password' => 'secret123',
    ]);

    $response = $this->post(route('login'), [
        'email' => $client->email,
        'password' => 'secret123',
    ]);

    $this->assertAuthenticatedAs($client, 'client');
    $response->assertRedirect(route('client.dashboard'));
});

it('rejects invalid client credentials without authenticating a guard', function () {
    $client = Client::create([
        'name' => 'Ana',
        'last_name' => 'García',
        'phone' => '555123456',
        'address' => 'Calle 123',
        'email' => 'ana@example.com',
        'password' => 'secret123',
    ]);

    $this->post(route('login'), [
        'email' => $client->email,
        'password' => 'incorrecta',
    ])->assertSessionHasErrors('email');

    $this->assertGuest('web');
    $this->assertGuest('client');
});

it('continues to authenticate staff through the standard login form', function () {
    $role = Role::create(['name' => 'Administrador']);
    $user = User::factory()->create(['role_id' => $role->id]);

    $response = $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($user, 'web');
    $response->assertRedirect(route('dashboard'));
});
