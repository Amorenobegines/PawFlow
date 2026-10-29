@extends('layouts.app')
@section('title', 'Ficha de mascota')

@section('content')
    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <a href="{{ route('pets.index') }}" class="text-sm font-semibold text-teal-700 hover:text-teal-900">
            &larr; Volver a mascotas
        </a>

        <section class="mt-5 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <h1 class="text-2xl font-bold text-gray-800">Ficha de mascota</h1>
            <p class="mt-1 text-gray-500">{{ $pet->name }}</p>

            <dl class="mt-6 grid gap-5 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Raza</dt>
                    <dd class="mt-1 text-gray-900">{{ $pet->breed }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Edad</dt>
                    <dd class="mt-1 text-gray-900">{{ $pet->age }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Peso</dt>
                    <dd class="mt-1 text-gray-900">{{ $pet->weight }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Género</dt>
                    <dd class="mt-1 text-gray-900">{{ $pet->gender }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Alergias</dt>
                    <dd class="mt-1 text-gray-900">{{ $pet->allergic ? 'Sí' : 'No' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Cliente</dt>
                    <dd class="mt-1 text-gray-900">
                        {{ $pet->client?->name }} {{ $pet->client?->last_name }}
                    </dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Observaciones</dt>
                    <dd class="mt-1 whitespace-pre-line text-gray-900">{{ $pet->observations ?: 'Sin observaciones' }}</dd>
                </div>
            </dl>
        </section>

        <section class="mt-6 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-6 py-5">
                <h2 class="text-lg font-semibold text-gray-800">Historial de citas</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-teal-500 text-white">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Fecha</th>
                            <th class="px-5 py-3 font-semibold">Servicios</th>
                            <th class="px-5 py-3 font-semibold">Empleado/a</th>
                            <th class="px-5 py-3 font-semibold">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($pet->appointments->sortByDesc(fn ($appointment) => $appointment->date.' '.$appointment->time) as $appointment)
                            <tr>
                                <td class="px-5 py-3 text-gray-700">{{ $appointment->date }} {{ $appointment->time }}</td>
                                <td class="px-5 py-3 text-gray-700">
                                    {{ $appointment->services->pluck('name')->join(', ') ?: 'Sin servicios' }}
                                </td>
                                <td class="px-5 py-3 text-gray-700">{{ $appointment->user?->name ?? 'Sin asignar' }}</td>
                                <td class="px-5 py-3 text-gray-700">{{ $appointment->status }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-gray-500">
                                    Todavía no hay citas para esta mascota.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
