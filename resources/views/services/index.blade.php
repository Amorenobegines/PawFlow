@extends('layouts.app')
@section('title', 'Servicios')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-teal-600 mt-6">AliCan · Administración</p>
                <h1 class="mt-1 text-3xl font-bold text-gray-800">Servicios</h1>
                <p class="mt-2 text-gray-500">Gestiona los tratamientos y servicios de peluquería canina.</p>
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
            $isEdit = isset($service);
            $inputClass =
                'mt-1 w-full rounded-xl border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 shadow-sm focus:border-teal-500 focus:ring-teal-500';
        @endphp

        <section id="appointment-form"class="flex justify-center mt-6 mb-6 ">
            <div class="w-full max-w-xl bg-white shadow-md rounded-xl p-6">

                <div class="border-b border-gray-100 px-6 py-5">
                    <h2 class="text-xl font-semibold text-gray-800">{{ $isEdit ? 'Editar servicio' : 'Añadir un servicio' }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">Indica el nombre, tipo, precio y duración del servicio.</p>
                </div>
                <form action="{{ $isEdit ? route('services.update', $service->id) : route('services.store') }}"
                    method="POST" class="space-y-5 p-6">
                    @csrf
                    @if ($isEdit)
                        @method('PATCH')
                    @endif

                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label for="name" class="text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" name="name" id="name"
                                value="{{ old('name', $isEdit ? $service->name : '') }}" class="{{ $inputClass }}"
                                required>
                        </div>
                        <div>
                            <label for="type" class="text-sm font-medium text-gray-700">Tipo</label>
                            <input type="text" name="type" id="type"
                                value="{{ old('type', $isEdit ? $service->type : '') }}" class="{{ $inputClass }}"
                                required>
                        </div>
                        <div>
                            <label for="price" class="text-sm font-medium text-gray-700">Precio (€)</label>
                            <input type="number" name="price" id="price" step="0.01" min="0"
                                value="{{ old('price', $isEdit ? $service->price : '') }}" class="{{ $inputClass }}"
                                required>
                        </div>
                        <div>
                            <label for="duration" class="text-sm font-medium text-gray-700">Duración (min)</label>
                            <input type="number" name="duration" id="duration" min="1"
                                value="{{ old('duration', $isEdit ? $service->duration : '') }}"
                                class="{{ $inputClass }}" required>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 border-t border-gray-100 pt-5 mt-6">
                        <button type="submit"
                            class="inline-flex items-center rounded-xl bg-teal-500 px-5 py-2.5 font-medium text-white transition hover:bg-teal-600">
                            <i
                                class="fa-solid fa-floppy-disk mr-2"></i>{{ $isEdit ? 'Guardar cambios' : 'Añadir servicio' }}
                        </button>
                        @if ($isEdit)
                            <a href="{{ route('services.index') }}"
                                class="rounded-xl px-4 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-100">Cancelar</a>
                        @endif
                    </div>
                </form>
            </div>
        </section>

        <!-- Buscador -->
        <section class="flex justify-center mt-6 mb-6">
            <div class="w-full max-w-xl bg-white shadow-md rounded-xl p-6">

                <h2 class="text-lg font-semibold text-gray-700">Buscar servicios</h2>
                <div class="relative mt-3 max-w-lg">
                    <select id="search" name="search" label="Buscar servicio"
                        class="w-full rounded-xl border-gray-200 py-3 pl-11 pr-4 text-sm focus:border-teal-500 focus:ring-teal-500">
                        <optgroup>
                            <option value="">Servicios disponibles</option>
                            @foreach ($services as $serviceItem)
                                <option value="{{ $serviceItem->name }}">{{ $serviceItem->name }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm mb-6 mt-6">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Listado de servicios</h2>
                    <p class="mt-1 text-sm text-gray-500">{{ $services->count() }} servicios</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-pink-50 text-pink-500">
                    <i class="fa-solid fa-scissors text-lg"></i>
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-left text-sm">
                    <thead class="bg-teal-500 text-white">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Servicio</th>
                            <th class="px-4 py-3 font-semibold">Tipo</th>
                            <th class="px-4 py-3 font-semibold">Precio</th>
                            <th class="px-4 py-3 font-semibold">Duración</th>
                            <th class="px-4 py-3 font-semibold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="servicesTableBody" class="divide-y divide-gray-100">
                        @forelse ($services as $serviceItem)
                            <tr class="transition hover:bg-teal-50/40">
                                <td class="px-4 py-4 font-semibold text-gray-800">{{ $serviceItem->name }}</td>
                                <td class="px-4 py-4">
                                    <span
                                        class="rounded-full bg-teal-50 px-3 py-1 text-xs font-medium text-teal-700">{{ $serviceItem->type }}</span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-4 font-semibold text-gray-700">
                                    {{ number_format((float) $serviceItem->price, 2, ',', '.') }} €</td>
                                <td class="whitespace-nowrap px-4 py-4 text-gray-600">
                                    <i class="fa-regular fa-clock mr-1.5 text-gray-400"></i>{{ $serviceItem->duration }}
                                    min
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex min-w-max flex-nowrap items-center gap-2">
                                        @can('update', $serviceItem)
                                            <a href="{{ route('services.edit', $serviceItem->id) }}"
                                                class="inline-flex items-center justify-center whitespace-nowrap rounded-lg bg-teal-50 px-3 py-2 text-xs font-semibold text-teal-700 transition hover:bg-teal-100">
                                                <i class="fa-solid fa-pen mr-1.5"></i>Editar
                                            </a>
                                        @endcan
                                        @can('delete', $serviceItem)
                                            <form action="{{ route('services.destroy', $serviceItem->id) }}" method="POST"
                                                onsubmit="return confirm('¿Seguro que quieres eliminar este servicio?');">
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
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <i class="fa-solid fa-scissors text-3xl text-gray-300"></i>
                                    <p class="mt-3 font-medium text-gray-700">No hay servicios para mostrar</p>
                                    <p class="mt-1 text-sm text-gray-500">Añade el primer servicio con el formulario.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            const tableBody = document.getElementById('servicesTableBody');

            if (searchInput && tableBody) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = searchInput.value.trim().toLowerCase();
                    Array.from(tableBody.rows).forEach(function(row) {
                        const serviceName = row.cells[0]?.textContent.toLowerCase() || '';
                        row.style.display = serviceName.includes(searchTerm) ? '' : 'none';
                    });
                });
            }
        });
    </script>
@endsection
