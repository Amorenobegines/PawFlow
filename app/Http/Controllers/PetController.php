<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Client;
use App\Models\Pet;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pets = Pet::all();
        $clients = Client::all();
        return view('pets.index', compact('pets', 'clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
      //  $pets = Pet::all();
        $clients = Client::all();
        $selectedClientId = $request->query('client_id');

        return view('pets.index', compact('pets', 'clients', 'selectedClientId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'breed' => 'required',
            'age' => 'required|integer',
            'weight' => 'required|integer',
            'gender' => 'required|in:Male,Female,male,female',
            'allergic' => 'required|boolean',
            'photo' => 'nullable|image',
            'observations' => 'nullable|string',
            'client_id' => 'required|exists:clients,id',
        ]);

        Pet::create($request->only(['name', 'breed', 'age', 'weight', 'gender', 'allergic', 'photo', 'observations', 'client_id']));
       
        return redirect()->route('pets.index' )->with('success', 'Pet created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pet $pet)
    {
        return view('pet.show', compact('pet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pet $pet)
    {
        $pets = Pet::all();
        $clients = Client::all();

        return view('pets.index', compact('pets', 'pet', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pet = Pet::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'breed' => 'required',
            'age' => 'required|integer',
            'weight' => 'required|integer',
            'gender' => 'required|in:Male,Female,male,female',
            'allergic' => 'required|boolean',
            'photo' => 'nullable|image',
            'observations' => 'nullable|string',
            'client_id' => 'required|exists:clients,id',
        ]);

        $pet->update($request->only(['name', 'breed', 'age', 'weight', 'gender', 'allergic', 'photo', 'observations', 'client_id']));
        Session::flash('success', 'Pet updated successfully.');
        return redirect()->route('pets.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pet $pet)
    {
        $pet->delete();
        Session::flash('success', 'Pet deleted successfully.');
        return redirect()->route('pets.index');
    }
}
