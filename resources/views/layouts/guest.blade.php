<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AliCan') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/AliCan.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="flex justify-center mt-6 mb-4">
        <img src="{{ asset('images/AliCan.png') }}" class="w-20 h-20 rounded-full object-cover">
    </div>
    <div class="flex justify-center mt-6 mb-4">
        <h1 class="text-2xl font-bold text-teal-500">AliCan</h1>
    </div>

    <div class="flex justify-center">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
