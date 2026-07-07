<!DOCTYPE html>
<html>
    <head>
        <title>Usuario</title>
    </head>
    <body>
        <h1>Usuario: {{ $user->name }} {{ $user->last_name }}</h1>

        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Rol:</strong> {{ $user->role?->name ?? '-' }}</p>
        <p><strong>Creado:</strong> {{ $user->created_at }}</p>
        <p><strong>Última actualización:</strong> {{ $user->updated_at }}</p>

        <p><a href="{{ route('users.index') }}">Volver a la lista</a></p>
    </body>
</html>
