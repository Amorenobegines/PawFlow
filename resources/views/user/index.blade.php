<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Clients</title>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    </head>
    <body>
        <h1>Clients</h1>
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
            $isEdit = isset($client) && $client instanceof \App\Models\Client;
            $pet = $isEdit ? $client->pets()->first() : null;
            $petAllergicValue = old('pet_allergic', $pet?->allergic ?? 0);
        @endphp
    </body>
</html>