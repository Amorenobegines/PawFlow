<?php

it('shows public navigation to guests on the contacts page', function () {
    $response = $this->get(route('contacts.index'));

    $response->assertOk()
        ->assertSee('Navegación principal')
        ->assertSee('Reservar cita')
        ->assertSee('Contacto')
        ->assertDontSee('Clientes')
        ->assertDontSee('Cerrar sesión');
});
