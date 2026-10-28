<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Service;
use App\Models\User;
use App\Services\AppointmentAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
    public function __construct(private AppointmentAvailability $availability)
    {
    }

    public function index()
    {
        $client = Auth::guard('client')->user();
        $appointments = $client->appointments()
            ->with(['pet', 'services', 'user'])
            ->orderByDesc('date')
            ->orderByDesc('time')
            ->get();

        return view('client.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $client = Auth::guard('client')->user();
        $pets = $client->pets()->orderBy('name')->get();
        $services = Service::orderBy('name')->get();
        $staff = $this->availableStaff();
        $times = $this->availability->availableTimes();

        return view('client.appointments.create', [
            'pets' => $pets,
            'services' => $services,
            'staff' => $staff,
            'times' => $times,
            'appointment' => null,
        ]);
    }

    public function edit(Appointment $appointment)
    {
        $client = Auth::guard('client')->user();
        $appointment = $client->appointments()->with('services')->findOrFail($appointment->id);
        $this->ensureEditable($appointment);

        $pets = $client->pets()->orderBy('name')->get();
        $services = Service::orderBy('name')->get();
        $staff = $this->availableStaff();
        $times = $this->availability->availableTimes();

        return view('client.appointments.create', compact('appointment', 'pets', 'services', 'staff', 'times'));
    }

    public function availability(Request $request)
    {
        $staffIds = $this->availableStaff()->modelKeys();
        $validated = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:'.now('Europe/Madrid')->toDateString()],
            'user_id' => ['required', Rule::in($staffIds)],
            'service_ids' => ['required', 'array', 'min:1'],
            'service_ids.*' => ['required', 'integer', 'distinct', 'exists:services,id'],
            'appointment_id' => ['nullable', 'integer'],
        ]);

        $appointmentId = null;
        if (! empty($validated['appointment_id'])) {
            $appointment = Auth::guard('client')->user()
                ->appointments()
                ->findOrFail($validated['appointment_id']);
            $this->ensureEditable($appointment);
            $appointmentId = $appointment->id;
        }

        $unavailableTimes = $this->availability->unavailableTimes(
            $validated['date'],
            (int) $validated['user_id'],
            $validated['service_ids'],
            $appointmentId
        );

        return response()->json([
            'unavailable_times' => $unavailableTimes,
            'past_times' => $this->availability->pastTimes($validated['date']),
        ]);
    }

    public function store(Request $request)
    {
        $client = Auth::guard('client')->user();
        $validated = $this->validateReservation($request, $client);
        $this->saveReservation($client, $validated);

        return redirect()
            ->route('client.appointments.index')
            ->with('success', 'Tu cita se ha reservado correctamente.');
    }

    public function update(Request $request, Appointment $appointment)
    {
        $client = Auth::guard('client')->user();
        $appointment = $client->appointments()->findOrFail($appointment->id);
        $this->ensureEditable($appointment);
        $validated = $this->validateReservation($request, $client);
        $this->saveReservation($client, $validated, $appointment);

        return redirect()
            ->route('client.appointments.index')
            ->with('success', 'Los cambios de tu cita se han guardado correctamente.');
    }

    public function destroy(Appointment $appointment)
    {
        $client = Auth::guard('client')->user();
        $appointment = $client->appointments()->findOrFail($appointment->id);
        $this->ensureEditable($appointment);
        $appointment->update(['status' => 'canceled']);

        return redirect()
            ->route('client.appointments.index')
            ->with('success', 'Tu cita se ha cancelado y el horario vuelve a estar disponible.');
    }

    private function validateReservation(Request $request, Client $client): array
    {
        $validated = $request->validate([
            'pet_id' => [
                'required',
                Rule::exists('pets', 'id')->where('client_id', $client->id),
            ],
            'service_ids' => ['required', 'array', 'min:1'],
            'service_ids.*' => ['required', 'integer', 'distinct', 'exists:services,id'],
            'user_id' => ['required', Rule::in($this->availableStaff()->modelKeys())],
            'date' => ['required', 'date', 'after_or_equal:'.now('Europe/Madrid')->toDateString()],
            'time' => ['required', Rule::in($this->availability->availableTimes())],
        ]);

        if (Carbon::parse($validated['date'].' '.$validated['time'], 'Europe/Madrid')
            ->lessThanOrEqualTo(now('Europe/Madrid'))) {
            throw ValidationException::withMessages([
                'time' => 'No se puede reservar una hora que ya ha pasado.',
            ]);
        }

        return $validated;
    }

    private function saveReservation(Client $client, array $validated, ?Appointment $appointment = null): void
    {
        DB::transaction(function () use ($client, $validated, $appointment) {
            $slotIsBooked = in_array(
                $validated['time'],
                $this->availability->unavailableTimes(
                    $validated['date'],
                    (int) $validated['user_id'],
                    $validated['service_ids'],
                    $appointment?->id
                ),
                true
            );

            if ($slotIsBooked) {
                throw ValidationException::withMessages([
                    'time' => 'Ese horario ya está reservado para el empleado seleccionado. Selecciona otra hora.',
                ]);
            }

            if ($appointment) {
                $appointment->update([
                    'pet_id' => $validated['pet_id'],
                    'date' => $validated['date'],
                    'time' => $validated['time'],
                    'user_id' => $validated['user_id'],
                ]);
                $appointment->services()->sync($validated['service_ids']);

                return;
            }

            $appointment = $client->appointments()->create([
                'pet_id' => $validated['pet_id'],
                'date' => $validated['date'],
                'time' => $validated['time'],
                'status' => 'pending',
                'user_id' => $validated['user_id'],
            ]);

            $appointment->services()->attach($validated['service_ids']);
        });
    }

    private function ensureEditable(Appointment $appointment): void
    {
        abort_unless(
            $appointment->status === 'pending'
                && Carbon::parse($appointment->date.' '.$appointment->time, 'Europe/Madrid')
                    ->isFuture(),
            403,
            'Solo puedes modificar o cancelar citas futuras que sigan activas.'
        );
    }

    private function availableStaff()
    {
        return User::query()
            ->whereHas('role', fn ($query) => $query->whereIn('name', ['Administrador', 'Recepcionista', 'Empleado']))
            ->orderBy('name')
            ->get(['id', 'name']);
    }

}
