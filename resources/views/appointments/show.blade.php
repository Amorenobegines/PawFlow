@extends('layouts.app')
@section('title', 'Detalle de cita')

@section('content')
    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <a href="{{ route('appointments.index') }}" class="text-sm font-semibold text-teal-700 hover:text-teal-900">
            &larr; Volver a la agenda
        </a>

        <section class="mt-5 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <h1 class="text-2xl font-bold text-gray-800">Detalle de cita</h1>
            <dl class="mt-6 grid gap-5 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Cliente</dt>
                    <dd class="mt-1 text-gray-900">
                        {{ $appointment->client->name }} {{ $appointment->client->last_name }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Mascota</dt>
                    <dd class="mt-1 text-gray-900">
                        <a href="{{ route('pets.show', $appointment->pet) }}" class="text-teal-700 hover:underline">
                            {{ $appointment->pet->name }}
                        </a>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Fecha y hora</dt>
                    <dd class="mt-1 text-gray-900">{{ $appointment->date }} {{ $appointment->time }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Estado</dt>
                    <dd class="mt-1 text-gray-900">{{ $appointment->status }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Servicios</dt>
                    <dd class="mt-1 text-gray-900">
                        {{ $appointment->services->pluck('name')->join(', ') ?: 'Sin servicios' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Empleado/a</dt>
                    <dd class="mt-1 text-gray-900">{{ $appointment->user?->name ?? 'Sin asignar' }}</dd>
                </div>
            </dl>
        </section>
    </div>
@endsection
