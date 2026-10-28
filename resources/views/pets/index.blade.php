@extends('layouts.app')
@section('title', 'Pets')

@section('content')

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

        @if (session('success'))
            <div class="mb-6 rounded-xl border border-green-100 bg-green-50 px-5 py-4 text-green-700">
                <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
            </div>
        @endif

        <!-- Errores -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-700 p-4 rounded-md mb-6">
                <strong>Hay algunos errores:</strong>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $isEdit = isset($pet);
        @endphp

        <!-- Formulario -->
        <section class="flex justify-center mt-12 mb-10 ">
            <div class="w-full max-w-xl bg-white shadow-md rounded-xl p-6">

                <h2 class="text-xl font-semibold text-gray-700 mb-4">
                    {{ $isEdit ? 'Editar Mascota' : 'Añadir Nueva Mascota' }}
                </h2>

                <form action="{{ $isEdit ? route('pets.update', $pet->id) : route('pets.store') }}" method="POST"
                    class="space-y-4">
                    @csrf
                    @if ($isEdit)
                        @method('PATCH')
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="text-sm font-medium">Nombre</label>
                            <input type="text" name="name" value="{{ old('name', $isEdit ? $pet->name : '') }}"
                                class="w-full border-gray-300 rounded-md px-3 py-2 focus:ring-teal-500 focus:border-teal-500"
                                required>
                        </div>

                        <div>
                            <label class="text-sm font-medium">Raza</label>
                            <input type="text" name="breed" value="{{ old('breed', $isEdit ? $pet->breed : '') }}"
                                class="w-full border-gray-300 rounded-md px-3 py-2 focus:ring-teal-500 focus:border-teal-500"
                                required>
                        </div>

                        <div>
                            <label class="text-sm font-medium">Edad</label>
                            <input type="number" name="age" value="{{ old('age', $isEdit ? $pet->age : '') }}"
                                class="w-full border-gray-300 rounded-md px-3 py-2 focus:ring-teal-500 focus:border-teal-500"
                                required>
                        </div>

                        <div>
                            <label class="text-sm font-medium">Peso</label>
                            <input type="number" name="weight" value="{{ old('weight', $isEdit ? $pet->weight : '') }}"
                                class="w-full border-gray-300 rounded-md px-3 py-2 focus:ring-teal-500 focus:border-teal-500"
                                required>
                        </div>

                        <div>
                            <label class="text-sm font-medium">Género</label>
                            <select name="gender"
                                class="w-full border-gray-300 rounded-md px-3 py-2 focus:ring-teal-500 focus:border-teal-500"
                                required>
                                <option value="Male"
                                    {{ old('gender', $isEdit && $pet->gender === 'Male' ? 'selected' : '') }}>Macho</option>
                                <option value="Female"
                                    {{ old('gender', $isEdit && $pet->gender === 'Female' ? 'selected' : '') }}>Hembra
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-medium">Alérgico</label>
                            <select name="allergic"
                                class="w-full border-gray-300 rounded-md px-3 py-2 focus:ring-teal-500 focus:border-teal-500"
                                required>
                                <option value="0"
                                    {{ old('allergic', $isEdit && $pet->allergic == 0 ? 'selected' : '') }}>No</option>
                                <option value="1"
                                    {{ old('allergic', $isEdit && $pet->allergic == 1 ? 'selected' : '') }}>Sí</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-sm font-medium">Observaciones</label>
                            <textarea name="observations"
                                class="w-full border-gray-300 rounded-md px-3 py-2 focus:ring-teal-500 focus:border-teal-500">{{ old('observations', $isEdit ? $pet->observations : '') }}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-sm font-medium">Cliente</label>
                            <select name="client_id"
                                class="w-full border-gray-300 rounded-md px-3 py-2 focus:ring-teal-500 focus:border-teal-500"
                                required>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}"
                                        {{ old('client_id', $isEdit ? $pet->client_id : '') == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }} {{ $client->last_name }} ({{ $client->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="pt-4">
                        @if ($isEdit)
                            <button type="submit"
                                class="bg-teal-500 hover:bg-teal-600 text-white px-6 py-2 rounded-md shadow-md">
                                Actualizar Mascota
                            </button>
                        @else
                            <button type="submit"
                                class="bg-teal-500 hover:bg-teal-600 text-white px-6 py-2 rounded-md shadow-md">
                                Añadir Mascota
                            </button>
                        @endif
                    </div>
                </form>

                @if ($isEdit)
                    <a href="{{ route('pets.index') }}"
                        class="text-sm text-gray-600 hover:text-teal-600 mt-4 inline-block">
                        Cancelar edición
                    </a>
                @endif

            </div>
        </section>


        <!-- Buscador -->
        <section class="flex justify-center mt-6 mb-6">
            <div class="w-full max-w-xl bg-white shadow-md rounded-xl p-6">
                <h2 class="text-xl font-semibold text-gray-700 ">Buscar Mascotas</h2>
                <div class="relative mt-3 max-w-lg">
                    <input type="text" id="name_filter" name="name_filter" value="{{ request('name_filter') }}"
                        placeholder="Escribe el nombre para filtrar"
                        class="w-full rounded-xl border-gray-200 py-3 pl-11 pr-4 text-sm focus:border-teal-500 focus:ring-teal-500">
                </div>
            </div>
        </section>


        <!-- Tabla -->
        <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm mt-6 mb-6">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Listado de Mascotas</h2>
                    <p class="mt-1 text-sm text-gray-500">{{ $pets->count() }} mascotas</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-pink-50 text-pink-500">
                    <i class="fa-regular fa-calendar-check text-lg"></i>
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-left text-sm">
                    <thead class="bg-teal-500 text-white">
                        <tr>
                            <th class="px-4 py-2 font-semibold">Nombre</th>
                            <th class="px-4 py-2 font-semibold">Raza</th>
                            <th class="px-4 py-2 font-semibold">Edad</th>
                            <th class="px-4 py-2 font-semibold">Peso</th>
                            <th class="px-4 py-2 font-semibold">Género</th>
                            <th class="px-4 py-2 font-semibold">Alérgico</th>
                            <th class="px-4 py-2 font-semibold">Observaciones</th>
                            <th class="px-4 py-2 font-semibold">Cliente</th>
                            <th class="whitespace-nowrap px-4 py-2 font-semibold">Acciones</th>
                        </tr>
                    </thead>

                    <tbody id="petsTableBody" class="divide-y divide-gray-100">
                        @foreach ($pets as $pet)
                            <tr class="transition hover:bg-teal-50/40">
                                <td class="px-4 py-2">{{ $pet->name }}</td>
                                <td class="px-4 py-2">{{ $pet->breed }}</td>
                                <td class="px-4 py-2">{{ $pet->age }}</td>
                                <td class="px-4 py-2">{{ $pet->weight }}</td>
                                <td class="px-4 py-2">{{ $pet->gender }}</td>
                                <td class="px-4 py-2">{{ $pet->allergic ? 'Sí' : 'No' }}</td>
                                <td class="px-4 py-2">{{ $pet->observations }}</td>
                                <td class="px-4 py-2">
                                    {{ $pet->client ? $pet->client->name . ' ' . $pet->client->last_name : '-' }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-2">
                                    <div class="flex min-w-max flex-nowrap items-center gap-2">
                                        @can('update', $pet)
                                            <a href="{{ route('pets.edit', $pet->id) }}"
                                                class="inline-flex items-center justify-center whitespace-nowrap rounded-lg bg-teal-50 px-3 py-2 text-xs font-semibold text-teal-700 transition hover:bg-teal-100">
                                                <i class="fa-solid fa-pen mr-1.5"></i>Editar
                                            </a>
                                        @endcan

                                        @can('delete', $pet)
                                            <form action="{{ route('pets.destroy', $pet->id) }}" method="POST"
                                                onsubmit="return confirm('¿Seguro que quieres eliminar esta mascota?');">
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <script>
        document.getElementById('name_filter').addEventListener('input', function() {
            const filterValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#petsTableBody tr');

            rows.forEach(row => {
                const nameCell = row.querySelector('td:first-child');
                if (nameCell) {
                    const nameText = nameCell.textContent.toLowerCase();
                    row.style.display = nameText.includes(filterValue) ? '' : 'none';
                }
            });
        });
    </script>

@endsection
