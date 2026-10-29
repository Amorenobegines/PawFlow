<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Crear cuenta | AliCan</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-pink-50 flex items-center justify-center p-4 sm:p-6">

    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-xl overflow-hidden flex flex-col md:flex-row">
        <aside
            class="w-full md:w-2/5 bg-pink-50 flex flex-col items-center justify-center text-center px-8 py-10 md:px-10">
            <a href="{{ url('/') }}" class="flex items-center space-x-2" aria-label="Ir a la página de inicio">
                <img src="{{ asset('images/AliCan.png') }}" alt="AliCan peluqueria canina en Dos Hermanas"
                    class="w-28 h-28 md:w-36 md:h-36 rounded-full object-cover shadow-md mb-6">
            </a>
            <p class="uppercase tracking-[0.25em] text-xs text-pink-400 mb-3">
                Peluquería Canina
            </p>

            <h1 class="text-4xl font-bold text-gray-800 mb-3">
                AliCan
            </h1>

            <p class="text-gray-500 text-sm leading-relaxed max-w-xs">
                Los mejores cuidados para el rey de la casa.
            </p>
        </aside>

        <main class="w-full md:w-3/5 px-6 py-8 sm:px-10 md:px-12 md:py-10 flex flex-col justify-center">
            <div class="mb-6">
                <h2 class="text-3xl font-bold text-gray-800">
                    Crea tu cuenta
                </h2>
                <p class="text-gray-500 text-sm mt-2">
                    Regístrate para gestionar las citas y cuidados de tu mascota.
                </p>
            </div>

            @if ($errors->any())
                <div role="alert"
                    class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}"
                class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-600 mb-1">Nombre</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        autocomplete="given-name" placeholder="Tu nombre"
                        class="w-full px-4 py-3 bg-white text-gray-700 border border-gray-200 rounded-xl focus:border-pink-400 focus:ring-2 focus:ring-pink-100 outline-none transition">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-600 mb-1">Apellidos</label>
                    <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required
                        autocomplete="family-name" placeholder="Tus apellidos"
                        class="w-full px-4 py-3 bg-white text-gray-700 border border-gray-200 rounded-xl focus:border-pink-400 focus:ring-2 focus:ring-pink-100 outline-none transition">
                    @error('last_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-600 mb-1">Teléfono</label>
                    <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required
                        autocomplete="tel" inputmode="numeric" minlength="9" maxlength="9" pattern="[0-9]{9}"
                        placeholder="Tu teléfono"
                        class="w-full px-4 py-3 bg-white text-gray-700 border border-gray-200 rounded-xl focus:border-pink-400 focus:ring-2 focus:ring-pink-100 outline-none transition">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-600 mb-1">Dirección</label>
                    <input id="address" type="text" name="address" value="{{ old('address') }}" required
                        autocomplete="street-address" placeholder="Tu dirección"
                        class="w-full px-4 py-3 bg-white text-gray-700 border border-gray-200 rounded-xl focus:border-pink-400 focus:ring-2 focus:ring-pink-100 outline-none transition">
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="email" class="block text-sm font-medium text-gray-600 mb-1">Correo
                        electrónico</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        autocomplete="email" placeholder="correo@ejemplo.com"
                        class="w-full px-4 py-3 bg-white text-gray-700 border border-gray-200 rounded-xl focus:border-pink-400 focus:ring-2 focus:ring-pink-100 outline-none transition">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-600 mb-1">Contraseña</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        minlength="8" placeholder="Mínimo 8 caracteres"
                        class="w-full px-4 py-3 bg-white text-gray-700 border border-gray-200 rounded-xl focus:border-pink-400 focus:ring-2 focus:ring-pink-100 outline-none transition">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Incluye mayúsculas, minúsculas y al menos un símbolo.</p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-600 mb-1">Confirmar
                        contraseña</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        autocomplete="new-password" placeholder="Repite la contraseña"
                        class="w-full px-4 py-3 bg-white text-gray-700 border border-gray-200 rounded-xl focus:border-pink-400 focus:ring-2 focus:ring-pink-100 outline-none transition">
                </div>

                <button type="submit"
                    class="sm:col-span-2 w-full py-3 bg-pink-400 text-white font-semibold rounded-xl shadow-md hover:bg-pink-500 hover:-translate-y-0.5 transition duration-300 mt-4">
                    CREAR CUENTA
                </button>

                <p class="sm:col-span-2 text-sm text-center text-gray-500 pt-1">
                    ¿Ya tienes una cuenta?
                    <a href="{{ route('login') }}"
                        class="text-pink-500 font-medium hover:text-pink-600 hover:underline">
                        Iniciar sesión
                    </a>
                </p>
            </form>
        </main>
    </div>

</body>

</html>
