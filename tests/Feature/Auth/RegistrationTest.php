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
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticatedAs(Client::where('email', 'test@example.com')->first(), 'client');
    $response->assertRedirect(route('client.dashboard', absolute: false));
});
