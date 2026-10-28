<?php

use App\Models\Role;
use App\Models\User;

test('staff dashboard displays its management overview', function () {
    $role = Role::create(['name' => 'Administrador']);
    $user = User::factory()->create(['role_id' => $role->id]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertOk()
        ->assertSee('Panel de gestión')
        ->assertSee('Próximas citas')
        ->assertSee('Mascotas registradas');
});
