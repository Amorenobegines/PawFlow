<x-client-layout>
    <div class="py-10">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wider text-teal-600">AliCan · Dos Hermanas</p>
                    <h1 class="mt-1 text-3xl font-bold text-gray-800">Mis citas</h1>
                    <p class="mt-2 text-gray-500">Consulta y gestiona tus citas reservadas.</p>
                </div>
                <a href="{{ route('client.appointments.create') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-pink-500 px-5 py-3 font-medium text-white transition hover:bg-pink-600">
                    <i class="fa-solid fa-calendar-plus mr-2"></i>Solicitar cita
                </a>
            </div>

            @if (session('success'))
                <div class="mb-6 rounded-xl border border-green-100 bg-green-50 px-5 py-4 text-green-700">
                    <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
                </div>
            @endif

            @if ($appointments->isEmpty())
                <div class="rounded-2xl border border-gray-100 bg-white p-10 text-center shadow-sm">
                    <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-pink-50 text-2xl text-pink-400">
                        <i class="fa-solid fa-calendar-day"></i>
                    </span>
                    <h2 class="mt-5 text-xl font-semibold text-gray-800">Todavía no tienes citas</h2>
                    <p class="mt-2 text-gray-500">Cuando solicites una cita, podrás consultar aquí su estado.</p>
                    <a href="{{ route('client.appointments.create') }}"
                        class="mt-6 inline-flex rounded-xl bg-pink-500 px-5 py-3 font-medium text-white transition hover:bg-pink-600">
                        Solicitar mi primera cita
                    </a>
                </div>
            @else
                <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($appointments as $appointment)
                        @php
                            $statusLabel = match ($appointment->status) {
                                'completed' => 'Completada',
                                'canceled' => 'Cancelada',
                                default => 'Reservada',
                            };
                            $statusClass = match ($appointment->status) {
                                'completed' => 'bg-green-50 text-green-700',
                                'canceled' => 'bg-red-50 text-red-700',
                                default => 'bg-amber-50 text-amber-700',
                            };
                        @endphp
                        <article class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h2 class="text-xl font-bold text-gray-800">{{ $appointment->pet->name }}</h2>
                                    <p class="mt-1 text-sm text-gray-500">{{ $appointment->pet->breed }}</p>
                                </div>
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>

                            <div class="mt-5 space-y-3 text-sm text-gray-600">
                                <p>
                                    <i class="fa-regular fa-calendar mr-2 w-4 text-pink-400"></i>
                                    {{ \Illuminate\Support\Carbon::parse($appointment->date)->format('d/m/Y') }}
                                    · {{ substr((string) $appointment->time, 0, 5) }}
                                </p>
                                <div>
                                    <p class="mb-1 font-medium text-gray-700">Servicios</p>
                                    <ul class="list-inside list-disc">
                                        @foreach ($appointment->services as $service)
                                            <li>{{ $service->name }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <p>
                                    <i class="fa-solid fa-user-tie mr-2 w-4 text-pink-400"></i>
                                    {{ $appointment->user?->name ?? 'Empleado pendiente de asignación' }}
                                </p>
                            </div>

                            @if ($appointment->status === 'pending' && \Illuminate\Support\Carbon::parse($appointment->date.' '.$appointment->time, 'Europe/Madrid')->isFuture())
                                <div class="mt-5 flex gap-3 border-t border-gray-100 pt-4">
                                    <a href="{{ route('client.appointments.edit', $appointment) }}"
                                        class="flex-1 rounded-xl bg-gray-100 px-4 py-2 text-center text-sm font-medium text-gray-700 transition hover:bg-gray-200">
                                        <i class="fa-solid fa-pen mr-1"></i>Modificar
                                    </a>
                                    <form method="POST" action="{{ route('client.appointments.destroy', $appointment) }}"
                                        class="flex-1"
                                        onsubmit="return confirm('¿Seguro que quieres cancelar esta cita? El horario quedará libre.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-full rounded-xl bg-red-50 px-4 py-2 text-sm font-medium text-red-600 transition hover:bg-red-100">
                                            <i class="fa-solid fa-xmark mr-1"></i>Cancelar
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-client-layout>
