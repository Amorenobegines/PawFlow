@extends('layouts.app')
@section('title', 'Appointments')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-teal-600">AliCan · Administración</p>
                <h1 class="mt-1 text-3xl font-bold text-gray-800">Citas</h1>
                <p class="mt-2 text-gray-500">Organiza las visitas, servicios y horarios de las mascotas.</p>
            </div>

        </div>

        @if (session('success'))
            <div class="mb-6 rounded-xl border border-green-100 bg-green-50 px-5 py-4 text-green-700">
                <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">
                <strong>Revisa los siguientes errores:</strong>
                <ul class="mt-2 list-inside list-disc text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $isEdit = isset($appointment);
            $inputClass =
                'mt-1 w-full rounded-xl border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 shadow-sm focus:border-teal-500 focus:ring-teal-500';
            $selectedServices = old('service_ids', $isEdit ? $appointment->services->pluck('id')->toArray() : []);
            $selectedTime = old('time', $isEdit ? substr((string) $appointment->time, 0, 5) : '');
        @endphp

        @can('create', App\Models\Appointment::class)
            <!-- Formulario -->
            <section id="appointment-form"class="flex justify-center mt-6 mb-6 ">
                <div class="w-full max-w-xl bg-white shadow-md rounded-xl p-6">

                    <div class="border-b border-gray-100 px-6 py-5">
                        <h2 class="text-xl font-semibold text-gray-800">{{ $isEdit ? 'Editar cita' : 'Programar una cita' }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">Selecciona un cliente para mostrar sus mascotas disponibles.</p>
                    </div>
                    <form action="{{ $isEdit ? route('appointments.update', $appointment->id) : route('appointments.store') }}"
                        method="POST" class="space-y-6 p-6" data-availability-url="{{ route('appointments.availability') }}"
                        data-appointment-id="{{ $isEdit ? $appointment->id : '' }}">
                        @csrf
                        @if ($isEdit)
                            @method('PUT')
                        @endif

                        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                            <div>
                                <label for="date" class="text-sm font-medium text-gray-700">Fecha</label>
                                <input type="date" name="date" id="date"
                                    min="{{ now('Europe/Madrid')->toDateString() }}"
                                    value="{{ old('date', $isEdit ? $appointment->date : '') }}" class="{{ $inputClass }}"
                                    required>
                            </div>
                            <div>
                                <label for="time" class="text-sm font-medium text-gray-700">Hora</label>
                                <select name="time" id="time" class="{{ $inputClass }}" required>
                                    <option value="">Selecciona una hora</option>
                                    @for ($hour = 9; $hour < 21; $hour++)
                                        @for ($minute = 0; $minute < 60; $minute += 30)
                                            @php
                                                $value = sprintf('%02d:%02d', $hour, $minute);
                                            @endphp
                                            <option value="{{ $value }}"
                                                {{ $selectedTime == $value ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endfor
                                    @endfor
                                </select>
                                <p id="availability-message" class="mt-1 text-xs text-gray-500" role="status"></p>
                            </div>
                            <div>
                                <label for="status" class="text-sm font-medium text-gray-700">Estado</label>
                                <select name="status" id="status" class="{{ $inputClass }}" required>
                                    <option value="">Selecciona un estado</option>
                                    <option value="pending"
                                        {{ old('status', $isEdit ? $appointment->status : '') === 'pending' ? 'selected' : '' }}>
                                        Pendiente</option>
                                    <option value="completed"
                                        {{ old('status', $isEdit ? $appointment->status : '') === 'completed' ? 'selected' : '' }}>
                                        Completada</option>
                                    <option value="canceled"
                                        {{ old('status', $isEdit ? $appointment->status : '') === 'canceled' ? 'selected' : '' }}>
                                        Cancelada</option>
                                </select>
                            </div>
                            <div>
                                <label for="client_id" class="text-sm font-medium text-gray-700">Cliente</label>
                                <select name="client_id" id="client_id" class="{{ $inputClass }}" required>
                                    <option value="">Selecciona un cliente</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}"
                                            {{ old('client_id', $selectedClientId ?? ($isEdit ? $appointment->client_id : '')) == $client->id ? 'selected' : '' }}>
                                            {{ $client->name }} {{ $client->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="pet_id" class="text-sm font-medium text-gray-700">Mascota</label>
                                <select name="pet_id" id="pet_id" class="{{ $inputClass }}" required>
                                    <option value="">Selecciona una mascota</option>
                                    @foreach ($pets as $pet)
                                        <option value="{{ $pet->id }}"
                                            {{ old('pet_id', $selectedPetId ?? ($isEdit ? $appointment->pet_id : '')) == $pet->id ? 'selected' : '' }}>
                                            {{ $pet->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="user_id" class="text-sm font-medium text-gray-700">Empleado/a</label>
                                <select name="user_id" id="user_id" class="{{ $inputClass }}" required>
                                    <option value="">Selecciona una persona</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('user_id', $selectedUserId ?? ($isEdit ? $appointment->user_id : '')) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <fieldset>
                            <legend class="text-sm font-medium text-gray-700">Servicios</legend>
                            <div class="mt-2 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach ($services as $service)
                                    <label
                                        class="flex cursor-pointer items-center gap-3 rounded-xl border border-gray-100 bg-gray-50 px-4 py-3 text-sm text-gray-700 transition hover:border-teal-200">
                                        <input type="checkbox" name="service_ids[]" value="{{ $service->id }}"
                                            {{ in_array($service->id, $selectedServices) ? 'checked' : '' }}
                                            data-service-checkbox
                                            class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                                        <span>{{ $service->name }} <span class="text-gray-400">· {{ $service->price }}
                                                €</span></span>
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>

                        <div class="flex flex-wrap items-center gap-3 border-t border-gray-100 pt-5">
                            <button type="submit"
                                class="inline-flex items-center rounded-xl bg-teal-500 px-5 py-2.5 font-medium text-white transition hover:bg-teal-600">
                                <i class="fa-solid fa-floppy-disk mr-2"></i>{{ $isEdit ? 'Guardar cambios' : 'Crear cita' }}
                            </button>
                            @if ($isEdit)
                                <a href="{{ route('appointments.index') }}"
                                    class="rounded-xl px-4 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-100">Cancelar</a>
                            @endif
                        </div>
                    </form>
                </div>
            </section>
        @endcan

        <!-- Filtros de búsqueda -->
        <section class="flex justify-center mt-6 mb-6">
            <div class="w-full max-w-xl bg-white shadow-md rounded-xl p-6">
                <h2 class="text-lg font-semibold text-gray-800">Buscar citas</h2>
                <form action="{{ route('appointments.index') }}" method="GET"
                    class="mt-6 grid gap-4 sm:grid-cols-[1fr_1fr_auto_auto] sm:items-end">
                    <div>
                        <label for="user_id_filter" class="text-sm font-medium text-gray-700">Empleado/a</label>
                        <select name="user_id" id="user_id_filter" class="{{ $inputClass }}">
                            <option value="">Personal</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="search_date" class="text-sm font-medium text-gray-700">Fecha</label>
                        <input type="date" name="search_date" id="search_date" value="{{ request('search_date') }}"
                            class="{{ $inputClass }}">
                    </div>
                    <button type="submit"
                        class="rounded-xl bg-teal-500 px-5 py-2.5 font-medium text-white transition hover:bg-teal-600">
                        <i class="fa-solid fa-magnifying-glass mr-2"></i>Buscar
                    </button>
                    <a href="{{ route('appointments.index') }}"
                        class=" rounded-xl border border-gray-200 px-5 py-2.5 text-center font-medium text-gray-600 transition hover:bg-gray-50">Limpiar</a>
                </form>
            </div>
        </section>


        <!-- Tabla de citas -->
        <section class="mt-6 mb-6 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Listado de citas</h2>
                    <p class="mt-1 text-sm text-gray-500">{{ $appointments->count() }} citas</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-pink-50 text-pink-500">
                    <i class="fa-regular fa-calendar-check text-lg"></i>
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-left text-sm">
                    <thead class="bg-teal-500 text-white">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Cliente / mascota</th>
                            <th class="px-4 py-3 font-semibold">Servicios</th>
                            <th class="px-4 py-3 font-semibold">Fecha y hora</th>
                            <th class="px-4 py-3 font-semibold">Estado</th>
                            <th class="px-4 py-3 font-semibold">Empleado/a</th>
                            <th class="px-4 py-3 font-semibold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($appointments as $appointmentItem)
                            <tr class="transition hover:bg-teal-50/40">
                                <td class="px-4 py-4">
                                    <p class="font-semibold text-gray-800">{{ $appointmentItem->client->name }}
                                        {{ $appointmentItem->client->last_name }}</p>
                                    <p class="mt-1 text-gray-500"><i
                                            class="fa-solid fa-paw mr-1 text-pink-400"></i>{{ $appointmentItem->pet->name }}
                                    </p>
                                </td>
                                <td class="px-4 py-4 text-gray-600">
                                    {{ $appointmentItem->services->pluck('name')->join(', ') ?: 'Sin servicios' }}</td>
                                <td class="whitespace-nowrap px-4 py-4 text-gray-600">
                                    <span class="font-medium text-gray-800">{{ $appointmentItem->date }}</span>
                                    <span class="ml-1 text-gray-500">{{ $appointmentItem->time }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    @php
                                        $statusClasses = match ($appointmentItem->status) {
                                            'completed' => 'bg-green-50 text-green-700',
                                            'canceled' => 'bg-red-50 text-red-700',
                                            default => 'bg-amber-50 text-amber-700',
                                        };
                                        $statusLabels = [
                                            'pending' => 'Pendiente',
                                            'completed' => 'Completada',
                                            'canceled' => 'Cancelada',
                                        ];
                                    @endphp
                                    @can('changeStatus', $appointmentItem)
                                        <form action="{{ route('appointments.changeStatus', $appointmentItem->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()"
                                                class="rounded-full border-0 px-3 py-1.5 text-xs font-semibold {{ $statusClasses }} focus:ring-teal-500">
                                                @foreach ($statusLabels as $status => $label)
                                                    <option value="{{ $status }}"
                                                        {{ $appointmentItem->status === $status ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </form>
                                    @else
                                        <span
                                            class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">
                                            {{ $statusLabels[$appointmentItem->status] ?? $appointmentItem->status }}
                                        </span>
                                    @endcan
                                </td>
                                <td class="px-4 py-4 text-gray-600">{{ $appointmentItem->user->name }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex min-w-max flex-nowrap items-center gap-2">
                                        @can('view', $appointmentItem)
                                            <a href="{{ route('appointments.show', $appointmentItem->id) }}"
                                                class="inline-flex items-center justify-center whitespace-nowrap rounded-lg bg-pink-50 px-3 py-2 text-xs font-semibold text-pink-700 transition hover:bg-pink-100">
                                                <i class="fa-solid fa-eye mr-1.5"></i>Ver
                                            </a>
                                        @endcan
                                        @can('update', $appointmentItem)
                                            <a href="{{ route('appointments.edit', $appointmentItem->id) }}"
                                                class="inline-flex items-center justify-center whitespace-nowrap rounded-lg bg-teal-50 px-3 py-2 text-xs font-semibold text-teal-700 transition hover:bg-teal-100">
                                                <i class="fa-solid fa-pen mr-1.5"></i>Editar
                                            </a>
                                        @endcan
                                        @can('delete', $appointmentItem)
                                            <form action="{{ route('appointments.destroy', $appointmentItem->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('¿Seguro que quieres eliminar esta cita?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center whitespace-nowrap rounded-lg bg-teal-50 px-3 py-2 text-xs font-semibold text-teal-700 transition hover:bg-teal-100">
                                                    <i class="fa-solid fa-trash mr-1.5"></i>Eliminar
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <i class="fa-regular fa-calendar-xmark text-3xl text-gray-300"></i>
                                    <p class="mt-3 font-medium text-gray-700">No hay citas para mostrar</p>
                                    <p class="mt-1 text-sm text-gray-500">Prueba a cambiar los filtros o crea una cita
                                        nueva.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    @can('create', App\Models\Appointment::class)
        <script>
            const clientPets = @json($clientPets);
            const clientSelect = document.getElementById('client_id');
            const petSelect = document.getElementById('pet_id');
            const initialPetId = @json(old('pet_id', $selectedPetId ?? ($isEdit ? $appointment->pet_id : null)));
            const appointmentForm = document.querySelector('[data-availability-url]');
            const appointmentDate = document.getElementById('date');
            const appointmentTime = document.getElementById('time');
            const employeeSelect = document.getElementById('user_id');
            const serviceCheckboxes = document.querySelectorAll('[data-service-checkbox]');
            const availabilityMessage = document.getElementById('availability-message');
            const originalTimeOptions = Array.from(appointmentTime.options).map(function(option) {
                return {
                    value: option.value,
                    text: option.textContent,
                };
            });
            let availabilityRequestId = 0;

            function renderPets(clientId) {
                const pets = clientPets[clientId] || [];
                petSelect.innerHTML = '';

                const placeholder = document.createElement('option');
                placeholder.value = '';
                placeholder.textContent = pets.length ? 'Selecciona una mascota' : 'No hay mascotas para este cliente';
                petSelect.appendChild(placeholder);

                pets.forEach(function(pet) {
                    const option = document.createElement('option');
                    option.value = pet.id;
                    option.textContent = pet.name;
                    option.selected = String(pet.id) === String(initialPetId);
                    petSelect.appendChild(option);
                });

                petSelect.disabled = pets.length === 0;
            }

            if (clientSelect && petSelect) {
                clientSelect.addEventListener('change', function() {
                    renderPets(this.value);
                });

                renderPets(clientSelect.value || '');
            }

            async function refreshAvailability() {
                const requestId = ++availabilityRequestId;
                const selectedServices = Array.from(serviceCheckboxes)
                    .filter(function(checkbox) {
                        return checkbox.checked;
                    })
                    .map(function(checkbox) {
                        return checkbox.value;
                    });

                if (!appointmentDate.value || !employeeSelect.value || selectedServices.length === 0) {
                    appointmentTime.innerHTML = '';
                    originalTimeOptions.forEach(function(optionData) {
                        const option = document.createElement('option');
                        option.value = optionData.value;
                        option.textContent = optionData.text;
                        appointmentTime.appendChild(option);
                    });
                    appointmentTime.value = '';
                    appointmentTime.disabled = false;
                    availabilityMessage.textContent =
                        'Selecciona fecha, empleado y al menos un servicio para consultar la disponibilidad.';
                    return;
                }

                const selectedTime = appointmentTime.value;
                const params = new URLSearchParams({
                    date: appointmentDate.value,
                    user_id: employeeSelect.value,
                });
                selectedServices.forEach(function(serviceId) {
                    params.append('service_ids[]', serviceId);
                });
                if (appointmentForm.dataset.appointmentId) {
                    params.set('appointment_id', appointmentForm.dataset.appointmentId);
                }

                appointmentTime.disabled = true;
                availabilityMessage.textContent = 'Consultando disponibilidad...';

                try {
                    const response = await fetch(`${appointmentForm.dataset.availabilityUrl}?${params.toString()}`, {
                        headers: {
                            Accept: 'application/json',
                        },
                    });
                    if (!response.ok) {
                        throw new Error('No se pudo consultar la disponibilidad.');
                    }

                    const availability = await response.json();
                    if (requestId !== availabilityRequestId) {
                        return;
                    }
                    const unavailable = new Set([
                        ...availability.unavailable_times,
                        ...availability.past_times,
                    ]);
                    appointmentTime.innerHTML = '';
                    originalTimeOptions.forEach(function(optionData) {
                        const option = document.createElement('option');
                        option.value = optionData.value;
                        option.textContent = optionData.text;
                        option.disabled = optionData.value !== '' && unavailable.has(optionData.value);
                        if (option.disabled) {
                            option.textContent += availability.past_times.includes(optionData.value) ?
                                ' (hora pasada)' :
                                ' (no disponible)';
                        }
                        appointmentTime.appendChild(option);
                    });
                    appointmentTime.value = selectedTime && !unavailable.has(selectedTime) ? selectedTime : '';
                    appointmentTime.disabled = false;
                    availabilityMessage.textContent = unavailable.size ?
                        'Las horas pasadas y las que se solapan con otra cita están deshabilitadas.' :
                        'No hay citas que bloqueen los horarios seleccionados.';
                } catch (error) {
                    if (requestId !== availabilityRequestId) {
                        return;
                    }
                    appointmentTime.disabled = true;
                    availabilityMessage.textContent = error.message;
                }
            }

            if (appointmentForm) {
                appointmentDate.addEventListener('change', refreshAvailability);
                employeeSelect.addEventListener('change', refreshAvailability);
                serviceCheckboxes.forEach(function(checkbox) {
                    checkbox.addEventListener('change', refreshAvailability);
                });
                refreshAvailability();
            }
        </script>
    @endcan
@endsection
