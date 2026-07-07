<!DOCTYPE html>
<html>
    <head>
        <title>Roles</title>
    </head>
    <body>
        <h1>Roles</h1>

        @if (session('success'))
            <p>{{ session('success') }}</p>
        @endif

        @if ($errors->any())
            <div>
                <strong>There are some errors:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $isEdit = isset($role) && $role instanceof \App\Models\Role;
        @endphp

        <h2>{{ $isEdit ? 'Edit role' : 'Create new role' }}</h2>

        <form action="{{ $isEdit ? route('roles.update', $role->id) : route('roles.store') }}" method="POST">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <label for="name">Name role:</label>
            <input type="text" name="name" id="name" value="{{ old('name', $isEdit ? $role->name : '') }}" required><br>

            <button type="submit">{{ $isEdit ? 'Update role' : 'Create role' }}</button>
        </form>

        @if ($isEdit)
            <p><a href="{{ route('roles.index') }}">Cancel edit</a></p>
        @endif

        <h2>Role List</h2>

        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td>{{ $role->id }}</td>
                        <td>{{ $role->name }}</td>
                        <td>
                            <a href="{{ route('roles.edit', $role->id) }}"> 
                                <button type="button">Edit</button>
                            </a>

                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>

                        </td>
                    </tr>

                @endforeach
            </tbody>
        </table>

    </body>