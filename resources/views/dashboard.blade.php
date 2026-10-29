<x-app-layout>
    <div class="bg-[#f7f8f6]">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
            <section
                class="relative overflow-hidden rounded-3xl bg-[#203b32] px-6 py-8 text-white shadow-lg sm:px-9 sm:py-10">
                <div class="absolute -right-10 -top-20 h-64 w-64 rounded-full bg-white/5"></div>
                <div class="absolute -bottom-32 right-40 h-64 w-64 rounded-full bg-[#f18c72]/15"></div>

                <div class="relative flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#ffc6ae]">AliCan · Panel de
                            gestión</p>
                        <h1 class="mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl text-[#ffc6ae]">
                            ¡Hola, {{ Auth::user()->name }}!
                        </h1>
                        <p class="mt-2 max-w-xl text-sm leading-6 text-[#ffc6ae] sm:text-base">
                            Aquí tienes el resumen de actividad y las próximas citas de la peluquería.
                        </p>
                    </div>

                    <div
                        class="flex items-center gap-3 rounded-2xl border border-white/15 bg-white/10 px-4 py-3 backdrop-blur">
                        <span
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#f18c72] text-lg text-[#203b32]">
                            <i class="fa-regular fa-calendar" aria-hidden="true"></i>
                        </span>
                        <div>
                            <p class="text-xs font-medium text-[#ffc6ae]">Hoy</p>
                            <p class="text-[#ffc6ae] font-bold">{{ now()->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <section aria-label="Resumen" class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <article class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Citas de hoy</p>
                            <p class="mt-3 text-3xl font-extrabold text-gray-900">{{ $appointmentsToday }}</p>
                        </div>
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#e7f1e9] text-[#38745a]">
                            <i class="fa-solid fa-calendar-day" aria-hidden="true"></i>
                        </span>
                    </div>
                    <a href="{{ route('appointments.index', ['search_date' => today()->toDateString()]) }}"
                        class="mt-4 inline-flex items-center gap-2 text-sm font-bold text-[#38745a] hover:text-[#203b32]">
                        Ver agenda de hoy <span aria-hidden="true">→</span>
                    </a>
                </article>

                <article class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Pendientes próximas</p>
                            <p class="mt-3 text-3xl font-extrabold text-gray-900">{{ $pendingAppointments }}</p>
                        </div>
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#fff0e9] text-[#c66b53]">
                            <i class="fa-regular fa-clock" aria-hidden="true"></i>
                        </span>
                    </div>
                    <a href="{{ route('appointments.index') }}"
                        class="mt-4 inline-flex items-center gap-2 text-sm font-bold text-[#bd614a] hover:text-[#8d402f]">
                        Revisar citas <span aria-hidden="true">→</span>
                    </a>
                </article>

                <article class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Mascotas registradas</p>
                            <p class="mt-3 text-3xl font-extrabold text-gray-900">{{ $petsCount }}</p>
                        </div>
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#f4edda] text-[#a17a29]">
                            <i class="fa-solid fa-paw" aria-hidden="true"></i>
                        </span>
                    </div>
                    <a href="{{ route('pets.index') }}"
                        class="mt-4 inline-flex items-center gap-2 text-sm font-bold text-[#90702e] hover:text-[#6c5120]">
                        Ver mascotas <span aria-hidden="true">→</span>
                    </a>
                </article>

                @if ($clientsCount !== null)
                    <article class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Clientes</p>
                                <p class="mt-3 text-3xl font-extrabold text-gray-900">{{ $clientsCount }}</p>
                            </div>
                            <span
                                class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#f4eaf3] text-[#94618e]">
                                <i class="fa-solid fa-users" aria-hidden="true"></i>
                            </span>
                        </div>
                        <a href="{{ route('clients.index') }}"
                            class="mt-4 inline-flex items-center gap-2 text-sm font-bold text-[#875b82] hover:text-[#63405f]">
                            Ver clientes <span aria-hidden="true">→</span>
                        </a>
                    </article>
                @else
                    <article class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Tu perfil</p>
                                <p class="mt-3 text-2xl font-extrabold text-gray-900">
                                    {{ Auth::user()->role?->name ?? 'Usuario' }}</p>
                            </div>
                            <span
                                class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#e9eef8] text-[#5876a7]">
                                <i class="fa-solid fa-scissors" aria-hidden="true"></i>
                            </span>
                        </div>
                        <a href="{{ route('profile.edit') }}"
                            class="mt-4 inline-flex items-center gap-2 text-sm font-bold text-[#5876a7] hover:text-[#354c72]">
                            Editar perfil <span aria-hidden="true">→</span>
                        </a>
                    </article>
                @endif
            </section>

            <section class="mt-6 grid gap-6 lg:grid-cols-[1.4fr_0.8fr]">
                <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                    <div
                        class="flex flex-col gap-3 border-b border-gray-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                        <div>
                            <h2 class="text-lg font-extrabold text-gray-900">Próximas citas</h2>
                            <p class="mt-1 text-sm text-gray-500">Las siguientes visitas programadas en AliCan.</p>
                        </div>
                        <a href="{{ route('appointments.index') }}"
                            class="inline-flex items-center gap-2 text-sm font-bold text-[#38745a] hover:text-[#203b32]">
                            Ver todas <span aria-hidden="true">→</span>
                        </a>
                    </div>

                    @if ($upcomingAppointments->isEmpty())
                        <div class="px-6 py-12 text-center">
                            <span
                                class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#e7f1e9] text-xl text-[#38745a]">
                                <i class="fa-regular fa-calendar-check" aria-hidden="true"></i>
                            </span>
                            <h3 class="mt-4 font-bold text-gray-900">No hay citas próximas</h3>
                            <p class="mt-1 text-sm text-gray-500">Cuando se programen citas, aparecerán aquí.</p>
                        </div>
                    @else
                        <ul class="divide-y divide-gray-100">
                            @foreach ($upcomingAppointments as $appointment)
                                <li
                                    class="flex flex-col gap-3 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                                    <div class="flex min-w-0 items-center gap-4">
                                        <div
                                            class="flex h-12 w-12 shrink-0 flex-col items-center justify-center rounded-xl bg-[#f7f8f6] text-[#203b32]">
                                            <span
                                                class="text-xs font-bold uppercase">{{ \Illuminate\Support\Carbon::parse($appointment->date)->format('M') }}</span>
                                            <span
                                                class="text-lg font-extrabold leading-5">{{ \Illuminate\Support\Carbon::parse($appointment->date)->format('d') }}</span>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="truncate font-bold text-gray-900">
                                                {{ $appointment->pet?->name ?? 'Mascota' }}
                                                <span class="font-normal text-gray-400">·</span>
                                                {{ $appointment->client?->name ?? 'Cliente' }}
                                            </p>
                                            <p class="mt-1 text-sm text-gray-500">
                                                {{ \Illuminate\Support\Carbon::parse($appointment->date)->format('d/m/Y') }}
                                                · {{ substr($appointment->time, 0, 5) }}
                                            </p>
                                        </div>
                                    </div>
                                    @php
                                        $statusClasses = match ($appointment->status) {
                                            'pending' => 'bg-amber-50 text-amber-700',
                                            'completed' => 'bg-green-50 text-green-700',
                                            'canceled' => 'bg-red-50 text-red-700',
                                            default => 'bg-gray-100 text-gray-600',
                                        };
                                        $statusLabels = [
                                            'pending' => 'Pendiente',
                                            'completed' => 'Completada',
                                            'canceled' => 'Cancelada',
                                        ];
                                    @endphp
                                    <span class="w-fit rounded-full px-3 py-1 text-xs font-bold {{ $statusClasses }}">
                                        {{ $statusLabels[$appointment->status] ?? ucfirst($appointment->status) }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <aside class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
                    <h2 class="text-lg font-extrabold text-gray-900">Accesos rápidos</h2>
                    <p class="mt-1 text-sm text-gray-500">Ve directamente a las tareas más habituales.</p>

                    <div class="mt-5 space-y-3">
                        <a href="{{ route('appointments.index') }}"
                            class="group flex items-center gap-3 rounded-xl border border-gray-100 p-3 transition hover:border-[#c7ddce] hover:bg-[#f5faf6]">
                            <span
                                class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#e7f1e9] text-[#38745a]">
                                <i class="fa-solid fa-calendar-check" aria-hidden="true"></i>
                            </span>
                            <span class="flex-1">
                                <span class="block text-sm font-bold text-gray-800">Gestionar citas</span>
                                <span class="mt-0.5 block text-xs text-gray-500">Agenda y estados</span>
                            </span>
                            <span class="text-gray-400 transition group-hover:translate-x-1">→</span>
                        </a>

                        <a href="{{ route('pets.index') }}"
                            class="group flex items-center gap-3 rounded-xl border border-gray-100 p-3 transition hover:border-[#f2d6c8] hover:bg-[#fff9f6]">
                            <span
                                class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#fff0e9] text-[#c66b53]">
                                <i class="fa-solid fa-paw" aria-hidden="true"></i>
                            </span>
                            <span class="flex-1">
                                <span class="block text-sm font-bold text-gray-800">Ver mascotas</span>
                                <span class="mt-0.5 block text-xs text-gray-500">Fichas y cuidados</span>
                            </span>
                            <span class="text-gray-400 transition group-hover:translate-x-1">→</span>
                        </a>

                        @can('viewAny', App\Models\Client::class)
                            <a href="{{ route('clients.index') }}"
                                class="group flex items-center gap-3 rounded-xl border border-gray-100 p-3 transition hover:border-[#dfcfe0] hover:bg-[#fbf7fb]">
                                <span
                                    class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#f4eaf3] text-[#94618e]">
                                    <i class="fa-solid fa-users" aria-hidden="true"></i>
                                </span>
                                <span class="flex-1">
                                    <span class="block text-sm font-bold text-gray-800">Ver clientes</span>
                                    <span class="mt-0.5 block text-xs text-gray-500">Contactos registrados</span>
                                </span>
                                <span class="text-gray-400 transition group-hover:translate-x-1">→</span>
                            </a>
                        @endcan

                        @can('viewAny', App\Models\Service::class)
                            <a href="{{ route('services.index') }}"
                                class="group flex items-center gap-3 rounded-xl border border-gray-100 p-3 transition hover:border-[#d4ddef] hover:bg-[#f7f9fd]">
                                <span
                                    class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#e9eef8] text-[#5876a7]">
                                    <i class="fa-solid fa-scissors" aria-hidden="true"></i>
                                </span>
                                <span class="flex-1">
                                    <span class="block text-sm font-bold text-gray-800">Ver servicios</span>
                                    <span class="mt-0.5 block text-xs text-gray-500">Tratamientos y tarifas</span>
                                </span>
                                <span class="text-gray-400 transition group-hover:translate-x-1">→</span>
                            </a>
                        @endcan
                    </div>
                </aside>
            </section>
        </div>
    </div>
</x-app-layout>
