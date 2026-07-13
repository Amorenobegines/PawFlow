<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Pet;
use App\Models\Client;   
use App\Models\User;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Appointment::class);
        $clients = Client::with('pets:id,name,client_id')->get();
        $users = User::all();
        $services = Service::all();

        $selectedClientId = $request->query('client_id');
        $selectedPetId = $request->query('pet_id');
        $selectedServiceId = $request->query('service_id');
        $selectedStatus = $request->query('status');
        $selectedUserId = $request->query('user_id');
        $searchDate = $request->query('search_date');

        $query = Appointment::with(['client', 'pet', 'services', 'user']);

        if ($selectedUserId) {
            $query->where('user_id', $selectedUserId);
        }

        if ($searchDate) {
            $query->whereDate('date', $searchDate);
        }

        $appointments = $query->get();

        $pets = $selectedClientId
            ? Pet::where('client_id', $selectedClientId)->select('id', 'name', 'client_id')->get()
            : Pet::select('id', 'name', 'client_id')->get();

        $clientPets = $clients->mapWithKeys(fn ($client) => [
            $client->id => $client->pets->map(fn ($pet) => ['id' => $pet->id, 'name' => $pet->name])->values()->all(),
        ])->toArray();

        return view('appointments.index', compact('appointments', 'clients', 'services', 'pets', 'clientPets', 'selectedClientId', 'selectedPetId', 'selectedServiceId', 'selectedStatus', 'users', 'selectedUserId', 'searchDate'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $this->authorize('create', Appointment::class);
        $clients = Client::all();
        $selectedClientId = $request->query('client_id');
        $services = Service::all();
        $selectedServiceId = $request->query('service_id');
        $pets = Pet::select('id', 'name')->get();
        $selectedPetId = $request->query('pet_id', $selectedClientId ? Pet::where('client_id', $selectedClientId)->first()?->id : null);
        $users = User::all();
        $selectedUserId = $request->query('user_id');
        return view('appointments.create', compact('clients', 'services', 'pets', 'selectedClientId', 'selectedServiceId', 'selectedPetId', 'users', 'selectedUserId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Appointment::class);

        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'status' => 'required|in:pending,completed,canceled',
            'client_id' => 'required|exists:clients,id',
            'pet_id' => 'required|exists:pets,id',
            'user_id' => 'required|exists:users,id',
            'service_ids' => 'required|array|exists:services,id',
        ]);

        $appointment = Appointment::create($request->only(['date', 'time', 'status', 'client_id', 'pet_id', 'user_id']));
        $appointment->services()->attach($request->input(['service_ids']));

        return redirect()->route('appointments.index')->with('success', 'Appointment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $appointments = Appointment::with(['client', 'pet', 'services', 'user'])->get();
        $appointment = Appointment::with(['client', 'pet', 'services', 'user'])->findOrFail($id);
        $clients = Client::with('pets:id,name,client_id')->get();
        $services = Service::all();
        $pets = Pet::where('client_id', $appointment->client_id)->select('id', 'name', 'client_id')->get();
        $users = User::all();

        $clientPets = $clients->mapWithKeys(fn ($client) => [
            $client->id => $client->pets->map(fn ($pet) => ['id' => $pet->id, 'name' => $pet->name])->values()->all(),
        ])->toArray();

        $selectedClientId = $appointment->client_id;
        $selectedPetId = $appointment->pet_id;
        $selectedServiceId = $appointment->services->pluck('id')->toArray();
        $selectedStatus = $appointment->status;
        $selectedUserId = $appointment->user_id;

        return view('appointments.index', compact('appointments', 'appointment', 'clients', 'services', 'pets', 'users', 'clientPets', 'selectedClientId', 'selectedPetId', 'selectedServiceId', 'selectedStatus', 'selectedUserId'));
    }
 

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'status' => 'required|in:pending,completed,canceled',
            'client_id' => 'required|exists:clients,id',
            'pet_id' => 'required|exists:pets,id',
            'user_id' => 'required|exists:users,id',
            'service_ids' => 'required|array|exists:services,id',
        ]);

        $appointment->update($request->only(['date', 'time', 'status', 'client_id', 'pet_id', 'user_id']));
        $appointment->services()->sync($request->input(['service_ids']));
        return redirect()->route('appointments.index')->with('success', 'Appointment updated successfully.');
    }

    public function changeStatus(Request $request, Appointment $appointment)
    {
        $this->authorize('changeStatus', $appointment);
        $request->validate([
            'status' => 'required|in:pending,completed,canceled',
        ]);

        $appointment->update(['status' => $request->input('status')]);

        return redirect()->route('appointments.index')->with('success', 'Appointment status updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    { 
        $this->authorize('delete', $appointment);
        $appointment->delete();
        return redirect()->route('appointments.index')->with('success', 'Appointment deleted successfully.');
    } 
}
