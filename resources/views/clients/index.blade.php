@extends('layouts.app')
@section('title', 'Clientes')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-teal-600 mt-6">AliCan · Administración</p>
                <h1 class="mt-1 text-3xl font-bold text-gray-800">Clientes</h1>
                <p class="mt-2 text-gray-500">Consulta los datos de contacto y la información de sus mascotas.</p>
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
            $isEdit = isset($client) && $client instanceof \App\Models\Client;
            $pet = $isEdit ? $client->pets()->first() : null;
            $petAllergicValue = old('pet_allergic', $pet?->allergic ?? 0);
            $inputClass =
                'mt-1 w-full rounded-xl border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 shadow-sm focus:border-teal-500 focus:ring-teal-500';
        @endphp

        <!-- Formulario -->
        <section id="client-form"class="flex justify-center mt-6 mb-6 ">
            <div class="w-full max-w-xl bg-white shadow-md rounded-xl p-6">

                <div class="border-b border-gray-100 px-6 py-5">
                    <h2 class="text-xl font-semibold text-gray-800">
                        {{ $isEdit ? 'Editar cliente y mascota' : 'Registrar cliente y mascota' }}</h2>
                    <p class="mt-1 text-sm text-gray-500">Completa los datos de contacto y de su mascota.</p>
                </div>
                <form action="{{ $isEdit ? route('clients.update', $client->id) : route('clients.store') }}" method="POST"
                    class="space-y-6 p-6">
                    @csrf
                    @if ($isEdit)
                        @method('PUT')
                    @endif

                    <fieldset>
                        <legend class="mb-3 flex items-center gap-2 font-semibold text-gray-800">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-teal-50 text-teal-600"><i
                                    class="fa-solid fa-user"></i></span>
                            Datos del cliente
                        </legend>
                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            <div>
                                <label for="name" class="text-sm font-medium text-gray-700">Nombre</label>
                                <input type="text" name="name" id="name"
                                    value="{{ old('name', $isEdit ? $client->name : '') }}" class="{{ $inputClass }}"
                                    required>
                            </div>
                            <div>
                                <label for="last_name" class="text-sm font-medium text-gray-700">Apellidos</label>
                                <input type="text" name="last_name" id="last_name"
                                    value="{{ old('last_name', $isEdit ? $client->last_name : '') }}"
                                    class="{{ $inputClass }}" required>
                            </div>
                            <div>
                                <label for="phone" class="text-sm font-medium text-gray-700">Teléfono</label>
                                <input type="text" name="phone" id="phone"
                                    value="{{ old('phone', $isEdit ? $client->phone : '') }}" class="{{ $inputClass }}"
                                    required>
                            </div>
                            <div>
                                <label for="address" class="text-sm font-medium text-gray-700">Dirección</label>
                                <input type="text" name="address" id="address"
                                    value="{{ old('address', $isEdit ? $client->address : '') }}"
                                    class="{{ $inputClass }}" required>
                            </div>
                            <div class="sm:col-span-2">
                                <label for="email" class="text-sm font-medium text-gray-700">Correo electrónico</label>
                                <input type="email" name="email" id="email"
                                    value="{{ old('email', $isEdit ? $client->email : '') }}" class="{{ $inputClass }}"
                                    required>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border-t border-gray-100 pt-6">
                        <legend class="mb-3 flex items-center gap-2 font-semibold text-gray-800">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-pink-50 text-pink-500"><i
                                    class="fa-solid fa-paw"></i></span>
                            Datos de la mascota
                        </legend>
                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            <div>
                                <label for="pet_name" class="text-sm font-medium text-gray-700">Nombre</label>
                                <input type="text" name="pet_name" id="pet_name"
                                    value="{{ old('pet_name', $pet?->name ?? '') }}" class="{{ $inputClass }}" required>
                            </div>
                            <div>
                                <label for="pet_breed" class="text-sm font-medium text-gray-700">Raza</label>
                                <input type="text" name="pet_breed" id="pet_breed"
                                    value="{{ old('pet_breed', $pet?->breed ?? '') }}" class="{{ $inputClass }}"
                                    required>
                            </div>
                            <div>
                                <label for="pet_age" class="text-sm font-medium text-gray-700">Edad (años)</label>
                                <input type="number" name="pet_age" id="pet_age"
                                    value="{{ old('pet_age', $pet?->age ?? '') }}" class="{{ $inputClass }}" required>
                            </div>
                            <div>
                                <label for="pet_weight" class="text-sm font-medium text-gray-700">Peso (kg)</label>
                                <input type="number" name="pet_weight" id="pet_weight"
                                    value="{{ old('pet_weight', $pet?->weight ?? '') }}" class="{{ $inputClass }}"
                                    required>
                            </div>
                            <div>
                                <label for="pet_gender" class="text-sm font-medium text-gray-700">Género</label>
                                <select name="pet_gender" id="pet_gender" class="{{ $inputClass }}" required>
                                    <option value="Male"
                                        {{ old('pet_gender', $pet?->gender ?? '') === 'Male' ? 'selected' : '' }}>Macho
                                    </option>
                                    <option value="Female"
                                        {{ old('pet_gender', $pet?->gender ?? '') === 'Female' ? 'selected' : '' }}>Hembra
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label for="pet_allergic" class="text-sm font-medium text-gray-700">¿Tiene alergias?</label>
                                <select name="pet_allergic" id="pet_allergic" class="{{ $inputClass }}" required>
                                    <option value="0" {{ $petAllergicValue == 0 ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ $petAllergicValue == 1 ? 'selected' : '' }}>Sí</option>
                                </select>
                            </div>
                            <div class="sm:col-span-2 lg:col-span-3">
                                <label for="pet_observations"
                                    class="text-sm font-medium text-gray-700">Observaciones</label>
                                <textarea name="pet_observations" id="pet_observations" rows="3" class="{{ $inputClass }}">{{ old('pet_observations', $pet?->observations ?? '') }}</textarea>
                            </div>
                        </div>
                    </fieldset>

                    <div class="flex flex-wrap items-center gap-3 border-t border-gray-100 pt-5">
                        <button type="submit"
                            class="inline-flex items-center rounded-xl bg-teal-500 px-5 py-2.5 font-medium text-white transition hover:bg-teal-600">
                            <i
                                class="fa-solid fa-floppy-disk mr-2"></i>{{ $isEdit ? 'Guardar cambios' : 'Crear cliente' }}
                        </button>
                        @if ($isEdit)
                            <a href="{{ route('clients.index') }}"
                                class="rounded-xl px-4 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-100">Cancelar</a>
                        @endif
                    </div>
                </form>
            </div>
        </section>

        <!-- Filtro de búsqueda -->
        <section class="flex justify-center mt-6 mb-6">
            <div class="w-full max-w-xl bg-white shadow-md rounded-xl p-6">

                <h2 class="text-xl font-semibold text-gray-700">Buscar clientes</h2>

                <div class="relative mt-3 max-w-lg">
                    <input type="search" id="email_filter" name="email_filter" value="{{ request('email_filter') }}"
                        placeholder="Filtrar por correo electrónico"
                        class="w-full rounded-xl border-gray-200 py-3 pl-11 pr-4 text-sm focus:border-teal-500 focus:ring-teal-500">
                </div>
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm mt-8 mb-8">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Listado de clientes</h2>
                    <p class="mt-1 text-sm text-gray-500">{{ $clients->count() }} clientes</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-teal-50 text-teal-600">
                    <i class="fa-solid fa-users text-lg"></i>
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[1050px] text-left text-sm">
                    <thead class="bg-teal-500 text-white">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Cliente</th>
                            <th class="px-4 py-3 font-semibold">Contacto</th>
                            <th class="px-4 py-3 font-semibold">Dirección</th>
                            <th class="px-4 py-3 font-semibold">Mascotas</th>
                            <th class="px-4 py-3 font-semibold">Detalles de mascotas</th>
                            <th class="px-4 py-3 font-semibold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="clientsTableBody" class="divide-y divide-gray-100">
                        @forelse ($clients as $clientItem)
                            <tr data-email="{{ $clientItem->email }}" class="transition hover:bg-teal-50/40">
                                <td class="px-4 py-4 font-semibold text-gray-800">
                                    {{ $clientItem->name }} {{ $clientItem->last_name }}
                                </td>
                                <td class="px-4 py-4">
                                    <a href="mailto:{{ $clientItem->email }}"
                                        class="block text-teal-700 hover:underline">{{ $clientItem->email }}</a>
                                    <a href="tel:{{ $clientItem->phone }}"
                                        class="mt-1 block text-gray-500 hover:text-teal-700">{{ $clientItem->phone }}</a>
                                </td>
                                <td class="px-4 py-4 text-gray-600">{{ $clientItem->address }}</td>
                                <td class="px-4 py-4">
                                    @forelse ($clientItem->pets as $petItem)
                                        <span
                                            class="mb-1 inline-flex items-center rounded-full bg-pink-50 px-2.5 py-1 text-xs font-medium text-pink-700">
                                            <i class="fa-solid fa-paw mr-1.5"></i>{{ $petItem->name }}
                                        </span>
                                    @empty
                                        <span class="text-gray-400">Sin mascotas</span>
                                    @endforelse
                                </td>
                                <td class="px-4 py-4">
                                    @forelse ($clientItem->pets as $petItem)
                                        <div class="mb-2 last:mb-0">
                                            <p class="font-medium text-gray-700">{{ $petItem->breed }} ·
                                                {{ $petItem->age }} años · {{ $petItem->weight }} kg</p>
                                            <p class="mt-0.5 text-xs text-gray-500">
                                                {{ $petItem->gender === 'Male' ? 'Macho' : 'Hembra' }}
                                                · {{ $petItem->allergic ? 'Tiene alergias' : 'Sin alergias conocidas' }}
                                                @if ($petItem->observations)
                                                    · {{ $petItem->observations }}
                                                @endif
                                            </p>
                                        </div>
                                    @empty
                                        <span class="text-gray-400">Sin datos</span>
                                    @endforelse
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex min-w-max flex-nowrap items-center gap-2">
                                        @can('update', $clientItem)
                                            <a href="{{ route('clients.edit', $clientItem->id) }}"
                                                class="inline-flex items-center justify-center whitespace-nowrap rounded-lg bg-teal-50 px-3 py-2 text-xs font-semibold text-teal-700 transition hover:bg-teal-100">
                                                <i class="fa-solid fa-pen mr-1.5"></i>Editar
                                            </a>
                                        @endcan
                                        @can('delete', $clientItem)
                                            <form action="{{ route('clients.destroy', $clientItem->id) }}" method="POST"
                                                onsubmit="return confirm('¿Seguro que quieres eliminar este cliente?');">
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
                                    <i class="fa-solid fa-users text-3xl text-gray-300"></i>
                                    <p class="mt-3 font-medium text-gray-700">No hay clientes para mostrar</p>
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
            const input = document.getElementById('email_filter');
            const rows = Array.from(document.querySelectorAll('#clientsTableBody tr'));

            if (input) {
                input.addEventListener('input', function() {
                    const term = input.value.trim().toLowerCase();
                    rows.forEach(function(row) {
                        row.style.display = (row.dataset.email || '').toLowerCase().includes(term) ?
                            '' : 'none';
                    });
                });
            }
        });
    </script>
@endsection
