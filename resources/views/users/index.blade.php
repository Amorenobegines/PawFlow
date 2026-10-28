@extends('layouts.app')
@section('title', 'Usuarios')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-teal-600 mt-6">AliCan · Administración</p>
                <h1 class="mt-1 text-3xl font-bold text-gray-800">Usuarios</h1>
                <p class="mt-2 text-gray-500">Administra las cuentas del equipo y los permisos asignados.</p>
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
            $isEdit = isset($user) && $user instanceof \App\Models\User;
            $inputClass =
                'mt-1 w-full rounded-xl border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 shadow-sm focus:border-teal-500 focus:ring-teal-500';
        @endphp

        <!-- Formulario -->
        <section id="appointment-form"class="flex justify-center mt-6 mb-6 ">
            <div class="w-full max-w-xl bg-white shadow-md rounded-xl p-6">

                <div class="border-b border-gray-100 px-6 py-5">
                    <h2 class="text-xl font-semibold text-gray-800">{{ $isEdit ? 'Editar usuario' : 'Crear un usuario' }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">Asigna al usuario un rol para definir sus permisos en AliCan.</p>
                </div>
                <form action="{{ $isEdit ? route('users.update', $user->id) : route('users.store') }}" method="POST"
                    class="space-y-5 p-6">
                    @csrf
                    @if ($isEdit)
                        @method('PUT')
                    @endif

                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <label for="name" class="text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" name="name" id="name"
                                value="{{ old('name', $isEdit ? $user->name : '') }}" class="{{ $inputClass }}" required>
                        </div>
                        <div>
                            <label for="last_name" class="text-sm font-medium text-gray-700">Apellidos</label>
                            <input type="text" name="last_name" id="last_name"
                                value="{{ old('last_name', $isEdit ? $user->last_name : '') }}" class="{{ $inputClass }}"
                                required>
                        </div>
                        <div>
                            <label for="email" class="text-sm font-medium text-gray-700">Correo electrónico</label>
                            <input type="email" name="email" id="email"
                                value="{{ old('email', $isEdit ? $user->email : '') }}" class="{{ $inputClass }}"
                                required>
                        </div>
                        <div>
                            <label for="password" class="text-sm font-medium text-gray-700">Contraseña</label>
                            <input type="password" name="password" id="password" class="{{ $inputClass }}"
                                {{ $isEdit ? '' : 'required' }} autocomplete="new-password">
                            @if ($isEdit)
                                <p class="mt-1 text-xs text-gray-500">Déjala vacía para conservar la contraseña actual.</p>
                            @endif
                        </div>
                        <div>
                            <label for="role_id" class="text-sm font-medium text-gray-700">Rol</label>
                            <select name="role_id" id="role_id" class="{{ $inputClass }}" required>
                                <option value="">Selecciona un rol</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ old('role_id', $isEdit ? $user->role_id : '') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 border-t border-gray-100 pt-5 mt-6">
                        <button type="submit"
                            class="inline-flex items-center rounded-xl bg-teal-500 px-5 py-2.5 font-medium text-white transition hover:bg-teal-600">
                            <i class="fa-solid fa-floppy-disk mr-2"></i>{{ $isEdit ? 'Guardar cambios' : 'Crear usuario' }}
                        </button>
                        @if ($isEdit)
                            <a href="{{ route('users.index') }}"
                                class="rounded-xl px-4 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-100">Cancelar</a>
                        @endif
                    </div>
                </form>
            </div>
        </section>

        <!-- Filtros de búsqueda -->
        <section class="flex justify-center mt-6 mb-6">
            <div class="w-full max-w-xl bg-white shadow-md rounded-xl p-6">
                <h2 class="text-xl font-semibold text-gray-700">Buscar usuarios</h2>
                <div class="relative mt-3 max-w-lg">
                    <label for="email_filter" class="text-sm font-medium text-gray-700">Correo electrónico</label>
                    <input type="search" name="email_filter" id="email_filter" value="{{ request('email_filter') }}"
                        placeholder="Filtrar por correo electrónico"
                        class="w-full rounded-xl border-gray-200 py-2.5 px-3 text-sm focus:border-teal-500 focus:ring-teal-500">
                </div>
                <div class="relative mt-3 max-w-lg">
                    <label class="text-sm font-medium text-gray-700">Rol</label>

                    <select name="role_filter" id="role_filter" class="{{ $inputClass }}">
                        <option value="">Selecciona un rol</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}"
                                {{ request('role_filter') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>
        </section>

        <!-- Tabla de usuarios -->
        <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm mt-6 mb-6">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Listado de usuarios</h2>
                    <p class="mt-1 text-sm text-gray-500">{{ $users->count() }} usuarios</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-pink-50 text-pink-500">
                    <i class="fa-solid fa-user-group text-lg"></i>
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[780px] text-left text-sm">
                    <thead class="bg-teal-500 text-white">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Usuario</th>
                            <th class="px-4 py-3 font-semibold">Correo electrónico</th>
                            <th class="px-4 py-3 font-semibold">Rol</th>
                            <th class="px-4 py-3 font-semibold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody" class="divide-y divide-gray-100">
                        @forelse ($users as $userItem)
                            <tr data-email="{{ $userItem->email }}" data-role-id="{{ $userItem->role_id }}"
                                class="transition hover:bg-teal-50/40">
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-teal-50 font-semibold text-teal-700">
                                            {{ mb_substr($userItem->name, 0, 1) }}{{ mb_substr($userItem->last_name, 0, 1) }}
                                        </span>
                                        <span class="font-semibold text-gray-800">{{ $userItem->name }}
                                            {{ $userItem->last_name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-gray-600">{{ $userItem->email }}</td>
                                <td class="px-4 py-4">
                                    <span
                                        class="inline-flex rounded-full bg-pink-50 px-3 py-1 text-xs font-semibold text-pink-700">
                                        {{ $userItem->role?->name ?? 'Sin rol' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex min-w-max flex-nowrap items-center gap-2">
                                        @can('update', $userItem)
                                            <a href="{{ route('users.edit', $userItem->id) }}"
                                                class="inline-flex items-center justify-center whitespace-nowrap rounded-lg bg-teal-50 px-3 py-2 text-xs font-semibold text-teal-700 transition hover:bg-teal-100">
                                                <i class="fa-solid fa-pen mr-1.5"></i>Editar
                                            </a>
                                        @endcan
                                        @can('delete', $userItem)
                                            <form action="{{ route('users.destroy', $userItem->id) }}" method="POST"
                                                onsubmit="return confirm('¿Seguro que quieres eliminar este usuario?');">
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
                                    <i class="fa-solid fa-user-group text-3xl text-gray-300"></i>
                                    <p class="mt-3 font-medium text-gray-700">No hay usuarios para mostrar</p>
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
            const emailInput = document.getElementById('email_filter');
            const roleInput = document.getElementById('role_filter');
            const rows = Array.from(document.querySelectorAll('#usersTableBody tr[data-email]'));

            const applyFilters = function() {
                const emailTerm = emailInput.value.trim().toLowerCase();
                const roleId = roleInput.value;

                rows.forEach(function(row) {
                    const matchesEmail = (row.dataset.email || '').toLowerCase().includes(emailTerm);
                    const matchesRole = !roleId || row.dataset.roleId === roleId;
                    row.style.display = matchesEmail && matchesRole ? '' : 'none';
                });
            };

            emailInput.addEventListener('input', applyFilters);
            roleInput.addEventListener('change', applyFilters);
            applyFilters();
        });
    </script>
@endsection
