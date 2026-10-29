<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Pet;
use App\Models\Client;   
use App\Models\User;
use App\Services\AppointmentAvailability;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
    public function __construct(private AppointmentAvailability $availability)
    {
    }

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

    public function availability(Request $request)
    {
        $this->authorize('create', Appointment::class);

        $validated = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:'.now('Europe/Madrid')->toDateString()],
            'user_id' => ['required', 'exists:users,id'],
            'service_ids' => ['required', 'array', 'min:1'],
            'service_ids.*' => ['required', 'integer', 'distinct', 'exists:services,id'],
            'appointment_id' => ['nullable', 'integer', 'exists:appointments,id'],
        ]);

        $unavailableTimes = $this->availability->unavailableTimes(
            $validated['date'],
            (int) $validated['user_id'],
            $validated['service_ids'],
            isset($validated['appointment_id']) ? (int) $validated['appointment_id'] : null
        );

        return response()->json([
            'unavailable_times' => $unavailableTimes,
            'past_times' => $this->availability->pastTimes($validated['date']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Appointment::class);

        $validated = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:'.now('Europe/Madrid')->toDateString()],
            'time' => ['required', 'date_format:H:i'],
            'status' => 'required|in:pending,completed,canceled',
            'client_id' => 'required|exists:clients,id',
            'pet_id' => 'required|exists:pets,id',
            'user_id' => 'required|exists:users,id',
            'service_ids' => 'required|array|min:1',
            'service_ids.*' => 'required|integer|distinct|exists:services,id',
        ]);

        $this->assertSlotAvailable($validated);

        $appointment = Appointment::create($request->only(['date', 'time', 'status', 'client_id', 'pet_id', 'user_id']));
        $appointment->services()->attach($validated['service_ids']);

        return redirect()->route('appointments.index')->with('success', 'Appointment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);
        $appointment->load(['client', 'pet', 'services', 'user']);

        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $appointment = Appointment::with(['client', 'pet', 'services', 'user'])->findOrFail($id);
        $this->authorize('update', $appointment);
        $appointments = Appointment::with(['client', 'pet', 'services', 'user'])->get();
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
        $validated = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:'.now('Europe/Madrid')->toDateString()],
            'time' => ['required', 'date_format:H:i'],
            'status' => 'required|in:pending,completed,canceled',
            'client_id' => 'required|exists:clients,id',
            'pet_id' => 'required|exists:pets,id',
            'user_id' => 'required|exists:users,id',
            'service_ids' => 'required|array|min:1',
            'service_ids.*' => 'required|integer|distinct|exists:services,id',
        ]);

        $this->assertSlotAvailable($validated, $appointment);
        $appointment->update($request->only(['date', 'time', 'status', 'client_id', 'pet_id', 'user_id']));
        $appointment->services()->sync($validated['service_ids']);
        return redirect()->route('appointments.index')->with('success', 'Appointment updated successfully.');
    }

    private function assertSlotAvailable(array $validated, ?Appointment $appointment = null): void
    {
        if ($validated['status'] === 'canceled') {
            return;
        }

        if (Carbon::parse($validated['date'].' '.$validated['time'], 'Europe/Madrid')
            ->lessThanOrEqualTo(now('Europe/Madrid'))) {
            throw ValidationException::withMessages([
                'time' => 'No se puede reservar una hora que ya ha pasado.',
            ]);
        }

        if (in_array(
            $validated['time'],
            $this->availability->unavailableTimes(
                $validated['date'],
                (int) $validated['user_id'],
                $validated['service_ids'],
                $appointment?->id
            ),
            true
        )) {
            throw ValidationException::withMessages([
                'time' => 'Ese horario se solapa con otra cita del empleado seleccionado.',
            ]);
        }
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
