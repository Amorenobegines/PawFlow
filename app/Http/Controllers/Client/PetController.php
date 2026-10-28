<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetController extends Controller
{
    /**
     * Mostrar las mascotas del cliente autenticado.
     */
    public function index()
    {
        $client = Auth::guard('client')->user();

        $pets = $client->pets()->latest()->get();

        return view('client.pets.index', compact('pets'));
    }

    /**
     * Mostrar formulario para añadir una mascota.
     */
    public function create()
    {
        return view('client.pets.create');
    }

    /**
     * Guardar una nueva mascota.
     */
    public function store(Request $request)
    {
        $client = Auth::guard('client')->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'breed' => ['required', 'string', 'max:255'],
            'age' => ['required', 'integer', 'min:0'],
            'weight' => ['required', 'numeric', 'min:0'],
            'gender' => ['required', 'in:Male,Female'],
            'allergic' => ['nullable', 'boolean'],
            'photo' => ['nullable', 'image', 'max:2047'],
            'observations' => ['nullable', 'string'],
        ]);

        $validated['client_id'] = $client->id;

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request
                ->file('photo')
                ->store('pets', 'public');
        }

        $client->pets()->create($validated);

        return redirect()
            ->route('client.pets.index')
            ->with('success', 'Mascota añadida correctamente.');
    }

    /**
 * Mostrar formulario para editar una mascota.
 */
public function edit(Pet $pet)
{
    $this->authorizeClientPet($pet);

    return view('client.pets.create', compact('pet'));
}

/**
 * Actualizar una mascota.
 */
public function update(Request $request, Pet $pet)
{
    $this->authorizeClientPet($pet);

    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'breed' => ['required', 'string', 'max:255'],
        'age' => ['required', 'integer', 'min:0'],
        'weight' => ['required', 'numeric', 'min:0'],
        'gender' => ['required', 'in:Male,Female'],
        'allergic' => ['nullable', 'boolean'],
        'photo' => ['nullable', 'image', 'max:2047'],
        'observations' => ['nullable', 'string'],
    ]);

    if ($request->hasFile('photo')) {
        $validated['photo'] = $request
            ->file('photo')
            ->store('pets', 'public');
    }

    $pet->update($validated);

    return redirect()
        ->route('client.pets.index')
        ->with('success', 'Mascota actualizada correctamente.');
}

/**
 * Eliminar una mascota.
 */
public function destroy(Pet $pet)
{
    $this->authorizeClientPet($pet);

    $pet->delete();

    return redirect()
        ->route('client.pets.index')
        ->with('success', 'Mascota eliminada correctamente.');
}

/**
 * Comprobar que la mascota pertenece al cliente autenticado.
 */
private function authorizeClientPet(Pet $pet): void
{
    $client = Auth::guard('client')->user();

    abort_unless(
        $pet->client_id === $client->id,
        403
    );
}
}