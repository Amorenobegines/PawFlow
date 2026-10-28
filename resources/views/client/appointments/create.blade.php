<x-client-layout>
    @php($isEdit = isset($appointment))
    <div class="py-10">
        <div class="mx-auto max-w-3xl px-6">
            <div class="mb-8">
                <a href="{{ route('client.appointments.index') }}"
                    class="inline-flex items-center text-sm text-gray-500 transition hover:text-pink-500">
                    <i class="fa-solid fa-arrow-left mr-2"></i>Volver a mis citas
                </a>
                <h1 class="mt-5 text-3xl font-bold text-gray-800">
                    {{ $isEdit ? 'Modificar cita' : 'Reservar una cita' }}
                </h1>
                <p class="mt-2 text-gray-500">
                    {{ $isEdit ? 'Actualiza los datos de tu cita.' : 'Elige tu mascota, los servicios y el horario que prefieres.' }}
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-100 bg-red-50 px-5 py-4 text-red-700">
                    <p class="mb-2 font-medium">Revisa los datos de la reserva:</p>
                    <ul class="list-inside list-disc text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($pets->isEmpty())
                <div class="rounded-2xl border border-pink-100 bg-white p-8 text-center shadow-sm">
                    <i class="fa-solid fa-paw text-3xl text-pink-400"></i>
                    <h2 class="mt-4 text-xl font-semibold text-gray-800">Primero añade una mascota</h2>
                    <p class="mt-2 text-gray-500">Necesitas tener una mascota registrada para solicitar una cita.</p>
                    <a href="{{ route('client.pets.create') }}"
                        class="mt-5 inline-flex rounded-xl bg-pink-500 px-5 py-3 font-medium text-white transition hover:bg-pink-600">
                        Añadir mascota
                    </a>
                </div>
            @elseif ($services->isEmpty())
                <div class="rounded-2xl border border-amber-100 bg-amber-50 p-6 text-amber-800">
                    En este momento no hay servicios disponibles para reservar. Ponte en contacto con nosotros.
                </div>
            @elseif ($staff->isEmpty())
                <div class="rounded-2xl border border-amber-100 bg-amber-50 p-6 text-amber-800">
                    No hay personal disponible para asignar citas. Ponte en contacto con nosotros.
                </div>
            @else
                <form method="POST"
                    action="{{ $isEdit ? route('client.appointments.update', $appointment) : route('client.appointments.store') }}"
                    data-availability-url="{{ route('client.appointments.availability') }}"
                    data-appointment-id="{{ $isEdit ? $appointment->id : '' }}"
                    class="space-y-6 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
                    @csrf
                    @if ($isEdit)
                        @method('PUT')
                    @endif

                    <div>
                        <label for="pet_id" class="mb-2 block text-sm font-medium text-gray-700">Mascota</label>
                        <select id="pet_id" name="pet_id" required
                            class="w-full rounded-xl border-gray-200 focus:border-pink-400 focus:ring-pink-400">
                            <option value="">Selecciona tu mascota</option>
                            @foreach ($pets as $pet)
                                <option value="{{ $pet->id }}" @selected(old('pet_id', $appointment->pet_id ?? '') == $pet->id)>
                                    {{ $pet->name }} · {{ $pet->breed }}
                                </option>
                            @endforeach
                        </select>
                        @error('pet_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <fieldset>
                        <legend class="mb-3 text-sm font-medium text-gray-700">Servicios</legend>
                        <div class="grid gap-3 sm:grid-cols-2">
                            @foreach ($services as $service)
                                <label
                                    class="flex cursor-pointer items-start gap-3 rounded-xl border border-gray-100 bg-gray-50 p-4 transition hover:border-pink-200">
                                    <input type="checkbox" name="service_ids[]" value="{{ $service->id }}"
                                        data-service-checkbox
                                        @checked(in_array(
                                            $service->id,
                                            (array) old(
                                                'service_ids',
                                                $isEdit ? $appointment->services->modelKeys() : [],
                                            ),
                                        ))
                                        class="mt-1 rounded border-gray-300 text-pink-500 focus:ring-pink-400">
                                    <span>
                                        <span class="block font-medium text-gray-800">{{ $service->name }}</span>
                                        <span class="mt-1 block text-sm text-gray-500">
                                            {{ number_format((float) $service->price, 2, ',', '.') }} €
                                            @if ($service->duration)
                                                · {{ $service->duration }} min
                                            @endif
                                        </span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('service_ids')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        @error('service_ids.*')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </fieldset>

                    <div>
                        <label for="user_id" class="mb-2 block text-sm font-medium text-gray-700">Personal</label>
                        <select id="user_id" name="user_id" required
                            class="w-full rounded-xl border-gray-200 focus:border-pink-400 focus:ring-pink-400">
                            <option value="">Selecciona a quién prefieres</option>
                            @foreach ($staff as $employee)
                                <option value="{{ $employee->id }}" @selected(old('user_id', $appointment->user_id ?? '') == $employee->id)>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="date" class="mb-2 block text-sm font-medium text-gray-700">Fecha preferida</label>
                            <input id="date" name="date" type="date" min="{{ now('Europe/Madrid')->toDateString() }}"
                                value="{{ old('date', $appointment->date ?? '') }}" required
                                class="w-full rounded-xl border-gray-200 focus:border-pink-400 focus:ring-pink-400">
                            @error('date')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="time" class="mb-2 block text-sm font-medium text-gray-700">Hora preferida</label>
                            <select id="time" name="time" required
                                class="w-full rounded-xl border-gray-200 focus:border-pink-400 focus:ring-pink-400">
                                <option value="">Selecciona una hora</option>
                                @foreach ($times as $time)
                                    <option value="{{ $time }}" @selected(old('time', isset($appointment) ? substr((string) $appointment->time, 0, 5) : '') === $time)>{{ $time }}</option>
                                @endforeach
                            </select>
                            <p id="availability-message" aria-live="polite" class="mt-2 text-sm text-gray-500">
                                Selecciona servicios, personal y fecha para consultar los horarios disponibles.
                            </p>
                            @error('time')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <p class="rounded-xl bg-pink-50 px-4 py-3 text-sm text-pink-800">
                        La cita se reservará directamente. Puedes modificarla o cancelarla desde “Mis citas” mientras siga activa y sea futura.
                    </p>

                    <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:justify-end">
                        <a href="{{ route('client.appointments.index') }}"
                            class="rounded-xl border border-gray-200 px-5 py-3 text-center text-gray-600 transition hover:bg-gray-50">
                            Cancelar
                        </a>
                        <button id="appointment-submit" type="submit"
                            class="rounded-xl bg-pink-500 px-5 py-3 font-medium text-white transition hover:bg-pink-600">
                            <i class="fa-solid fa-calendar-check mr-2"></i>
                            {{ $isEdit ? 'Guardar cambios' : 'Reservar cita' }}
                        </button>
                    </div>
                </form>

                <script>
                    (() => {
                        const form = document.querySelector('[data-availability-url]');
                        const dateInput = document.getElementById('date');
                        const staffSelect = document.getElementById('user_id');
                        const serviceCheckboxes = Array.from(form.querySelectorAll('[data-service-checkbox]'));
                        const timeSelect = document.getElementById('time');
                        const message = document.getElementById('availability-message');
                        const submitButton = document.getElementById('appointment-submit');
                        let hasAvailableTime = false;
                        let requestNumber = 0;

                        if (!form || !dateInput || !staffSelect || !timeSelect || !message || !submitButton) {
                            return;
                        }

                        const refreshAvailability = async () => {
                            const selectedTime = timeSelect.value;
                            const options = Array.from(timeSelect.options).slice(1);
                            const selectedServices = serviceCheckboxes
                                .filter((checkbox) => checkbox.checked)
                                .map((checkbox) => checkbox.value);

                            for (const option of options) {
                                option.disabled = false;
                                option.textContent = option.value;
                            }

                            const currentRequest = ++requestNumber;
                            if (!dateInput.value || !staffSelect.value || selectedServices.length === 0) {
                                timeSelect.value = '';
                                timeSelect.disabled = true;
                                submitButton.disabled = true;
                                hasAvailableTime = false;
                                message.textContent = selectedServices.length === 0
                                    ? 'Selecciona al menos un servicio para consultar la disponibilidad.'
                                    : !staffSelect.value
                                    ? 'Selecciona al personal para consultar sus horarios disponibles.'
                                    : 'Selecciona una fecha para consultar los horarios disponibles.';
                                return;
                            }

                            timeSelect.disabled = true;
                            submitButton.disabled = true;
                            message.textContent = 'Consultando horarios disponibles...';

                            try {
                                const url = new URL(form.dataset.availabilityUrl, window.location.origin);
                                url.searchParams.set('date', dateInput.value);
                                url.searchParams.set('user_id', staffSelect.value);
                                selectedServices.forEach((serviceId) => {
                                    url.searchParams.append('service_ids[]', serviceId);
                                });
                                if (form.dataset.appointmentId) {
                                    url.searchParams.set('appointment_id', form.dataset.appointmentId);
                                }
                                const response = await fetch(url, {
                                    headers: {
                                        Accept: 'application/json',
                                    },
                                });

                                if (!response.ok) {
                                    throw new Error('No se pudo consultar la disponibilidad.');
                                }

                                const { unavailable_times: unavailableTimes, past_times: pastTimes } = await response.json();
                                if (currentRequest !== requestNumber) {
                                    return;
                                }

                                for (const option of options) {
                                    if (unavailableTimes.includes(option.value)) {
                                        option.disabled = true;
                                        option.textContent = `${option.value} · Se solapa con otra cita`;
                                    } else if (pastTimes.includes(option.value)) {
                                        option.disabled = true;
                                        option.textContent = `${option.value} · Hora pasada`;
                                    }
                                }

                                if (unavailableTimes.includes(selectedTime) || pastTimes.includes(selectedTime)) {
                                    timeSelect.value = '';
                                }

                                hasAvailableTime = options.some((option) => !option.disabled);
                                timeSelect.disabled = false;
                                message.textContent = !hasAvailableTime
                                    ? 'No quedan horarios disponibles para esta fecha.'
                                    : 'Los horarios reservados o que ya han pasado aparecen desactivados.';
                            } catch (error) {
                                if (currentRequest !== requestNumber) {
                                    return;
                                }
                                timeSelect.value = '';
                                hasAvailableTime = false;
                                message.textContent = 'No se pudo consultar la disponibilidad. Cambia la fecha o inténtalo de nuevo.';
                                console.error(error);
                            } finally {
                                if (currentRequest === requestNumber) {
                                    timeSelect.disabled = false;
                                    submitButton.disabled = !hasAvailableTime;
                                }
                            }
                        };

                        dateInput.addEventListener('change', refreshAvailability);
                        staffSelect.addEventListener('change', refreshAvailability);
                        serviceCheckboxes.forEach((checkbox) => {
                            checkbox.addEventListener('change', refreshAvailability);
                        });
                        refreshAvailability();
                    })();
                </script>
            @endif
        </div>
    </div>
</x-client-layout>
