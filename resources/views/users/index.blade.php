<!DOCTYPE html>
<html>
    <head>
        <title>Usuarios</title>
    </head>
    <body>
        <h1>Administración de Usuarios</h1>

        @if (session('success'))
            <p>{{ session('success') }}</p>
        @endif

        @if ($errors->any())
            <div>
                <strong>Hay errores:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $isEdit = isset($user) && $user instanceof \App\Models\User;
        @endphp

        <h2>{{ $isEdit ? 'Editar usuario' : 'Crear nuevo usuario' }}</h2>

        <form action="{{ $isEdit ? route('users.update', $user->id) : route('users.store') }}" method="POST">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <label for="name">Nombre:</label>
            <input type="text" name="name" id="name" value="{{ old('name', $isEdit ? $user->name : '') }}" required><br>

            <label for="last_name">Apellido:</label>
            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $isEdit ? $user->last_name : '') }}" required><br>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="{{ old('email', $isEdit ? $user->email : '') }}" required><br>

            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" {{ $isEdit ? '' : 'required' }}><br>
            @if ($isEdit)
                <small>Dejar vacío para mantener la contraseña actual.</small><br>
            @endif

            <label for="role_id">Rol:</label>
            <select name="role_id" id="role_id" required>
                @foreach ($roles as $role)
                    <option value="">Selecciona un rol</option>
                    <option value="{{ $role->id }}" {{ old('role_id', $isEdit ? $user->role_id : '') == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select><br><br>

            <button type="submit">{{ $isEdit ? 'Actualizar usuario' : 'Crear usuario' }}</button>
        </form>

        @if ($isEdit)
            <p><a href="{{ route('users.index') }}">Cancelar edición</a></p>
        @endif

        <h2>Search users</h2>
        <form action="{{ route('users.index') }}" method="GET" style="margin-bottom: 20px;">
            <label for="email_filter">Buscar por email:</label>
            <input type="text" name="email_filter" id="email_filter" value="{{ request('email_filter') }}" placeholder="Escribe el email para filtrar">
        </form>

        <h2>Lista de usuarios</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="usersTableBody">
                @foreach ($users as $userItem)
                    <tr data-email="{{ $userItem->email }}">
                        <td>{{ $userItem->name }}</td>
                        <td>{{ $userItem->last_name }}</td>
                        <td>{{ $userItem->email }}</td>
                        <td>{{ $userItem->role?->name ?? '-' }}</td>
                        <td>
                            <a href="{{ route('users.edit', $userItem->id) }}"><button type="button">Editar</button></a>
                            <form action="{{ route('users.destroy', $userItem->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('¿Eliminar este usuario?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <script>
            document.getElementById('email_filter').addEventListener('input', function() {
                const filterValue = this.value.toLowerCase();
                const rows = document.querySelectorAll('#usersTableBody tr');

                rows.forEach(row => {
                    const email = row.getAttribute('data-email').toLowerCase();
                    if (email.includes(filterValue)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        </script>

    </body>
</html>
