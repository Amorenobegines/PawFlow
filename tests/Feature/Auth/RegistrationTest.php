<?php

use App\Models\Client;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'last_name' => 'Example',
        'phone' => '555123456',
        'address' => '123 Example Street',
        'email' => 'test@example.com',
        'password' => 'Password!1',
        'password_confirmation' => 'Password!1',
    ]);

    $this->assertAuthenticatedAs(Client::where('email', 'test@example.com')->first(), 'client');
    $response->assertRedirect(route('client.dashboard', absolute: false));
});

test('registration requires all client details', function () {
    $response = $this->post('/register', []);

    $response->assertSessionHasErrors([
        'name',
        'last_name',
        'phone',
        'address',
        'email',
        'password',
    ]);
});

test('registration validates phone number and email format', function (string $phone) {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'last_name' => 'Example',
        'phone' => $phone,
        'address' => '123 Example Street',
        'email' => 'not-an-email',
        'password' => 'Password!1',
        'password_confirmation' => 'Password!1',
    ]);

    $response->assertSessionHasErrors(['phone', 'email']);
    $this->assertDatabaseCount('clients', 0);
})->with([
    'too short' => '12345678',
    'too long' => '1234567890',
    'non-numeric' => '12345678a',
]);

test('registration requires a strong password', function (string $password) {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'last_name' => 'Example',
        'phone' => '555123456',
        'address' => '123 Example Street',
        'email' => 'test@example.com',
        'password' => $password,
        'password_confirmation' => $password,
    ]);

    $response->assertSessionHasErrors('password');
    $this->assertDatabaseCount('clients', 0);
})->with([
    'too short' => 'Pass!1',
    'missing uppercase' => 'password!1',
    'missing lowercase' => 'PASSWORD!1',
    'missing symbol' => 'Password1',
]);
