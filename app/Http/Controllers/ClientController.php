<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Client::query();

        if ($request->filled('email_filter')) {
            $query->where('email', 'like', '%' . $request->input('email_filter') . '%');
        }

        $clients = $query->with('pets')->get();

        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::with('pets')->get();

        return view('clients.index', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|numeric|digits_between:9,9',
            'address' => 'required',
            'email' => 'required|email|unique:clients,email',
            'password' => 'nullable|min:6',
            'pet_name' => 'required',
            'pet_breed' => 'required',
            'pet_age' => 'required|integer',
            'pet_weight' => 'required|integer',
            'pet_gender' => 'required|in:Male,Female',
            'pet_allergic' => 'required|boolean',
            'pet_observations' => 'nullable|string',
        ]);

        $clientData = $request->only(['name', 'last_name', 'phone', 'address', 'email']);

        if ($request->filled('password')) {
            $clientData['password'] = $request->input('password');
        }

        $client = Client::create($clientData);

        $client->pets()->create([
            'name' => $request->input('pet_name'),
            'breed' => $request->input('pet_breed'),
            'age' => $request->input('pet_age'),
            'weight' => $request->input('pet_weight'),
            'gender' => $request->input('pet_gender'),
            'allergic' => (bool) $request->input('pet_allergic'),
            'observations' => $request->input('pet_observations'),
        ]);

        return redirect()->route('clients.index')->with('success', 'Client and pet created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $client = Client::findOrFail($id);

        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $clients = Client::with('pets')->get();
        $client = Client::with('pets')->findOrFail($id);

        return view('clients.index', compact('clients', 'client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $client = Client::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'password' => 'nullable|min:6',
            'pet_name' => 'required',
            'pet_breed' => 'required',
            'pet_age' => 'required|integer',
            'pet_weight' => 'required|numeric',
            'pet_gender' => 'required|in:Male,Female',
            'pet_allergic' => 'required|boolean',
            'pet_observations' => 'nullable|string',
        ]);

        $client->update($request->only(['name', 'last_name', 'phone', 'address', 'email']));

        if ($request->filled('password')) {
            $client->forceFill(['password' => $request->input('password')])->save();
        }

        $petPayload = [
            'name' => $request->input('pet_name'),
            'breed' => $request->input('pet_breed'),
            'age' => $request->input('pet_age'),
            'weight' => $request->input('pet_weight'),
            'gender' => $request->input('pet_gender'),
            'allergic' => (bool) $request->input('pet_allergic'),
            'observations' => $request->input('pet_observations'),
        ];

        $pet = $client->pets()->first();

        if ($pet) {
            $pet->update($petPayload);
        } else {
            $client->pets()->create($petPayload);
        }

        return redirect()->route('clients.index')->with('success', 'Client and pet updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }
}
