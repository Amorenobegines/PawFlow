<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Iniciar sesión | AliCan</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-pink-50 flex items-center justify-center p-6">

    <div
        class="w-full max-w-4xl bg-white rounded-3xl shadow-xl overflow-hidden
               flex flex-col md:flex-row">

        <!-- ================================= -->
        <!-- IZQUIERDA: LOGO -->
        <!-- ================================= -->

        <div
            class="w-full md:w-2/5 bg-pink-50
                   flex flex-col items-center justify-center
                   text-center px-8 py-10">

            <a href="{{ url('/') }}" class="flex items-center space-x-2" aria-label="Ir a la página de inicio">
                <img src="{{ asset('images/AliCan.png') }}" alt="AliCan peluqueria canina en Dos Hermanas"
                    class="w-28 h-28 md:w-36 md:h-36 rounded-full object-cover shadow-md mb-6">
            </a>

            <p class="uppercase tracking-[0.25em]
                      text-xs text-pink-400 mb-3">
                Peluquería Canina
            </p>

            <h1 class="text-4xl font-bold text-gray-800 mb-3">
                AliCan
            </h1>

            <p class="text-gray-500 text-sm leading-relaxed max-w-xs">
                Los mejores cuidados para el rey de la casa.
            </p>

        </div>


        <!-- ================================= -->
        <!-- DERECHA: LOGIN -->
        <!-- ================================= -->

        <div class="w-full md:w-3/5 px-8 py-10 md:px-12
                    flex flex-col justify-center">

            <div class="mb-7">

                <h2 class="text-3xl font-bold text-gray-800">
                    Bienvenido/a
                </h2>

                <p class="text-gray-500 text-sm mt-2">
                    Accede como cliente o como personal para gestionar tus citas y mascotas.
                </p>

            </div>


            <!-- ERRORES -->

            @if ($errors->any())

                <div
                    class="bg-red-50 border border-red-200
                           text-red-600 px-4 py-3
                           rounded-xl mb-5 text-sm">

                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach

                </div>

            @endif


            <!-- FORMULARIO -->

            <form method="POST" action="{{ route('login') }}" class="space-y-5">

                @csrf


                <!-- EMAIL -->

                <div>

                    <label for="email"
                        class="block text-sm font-medium
                               text-gray-600 mb-1">

                        Correo electrónico

                    </label>

                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        autocomplete="email" placeholder="correo@ejemplo.com"
                        class="w-full px-4 py-3
                               bg-white text-gray-700
                               border border-gray-200
                               rounded-xl
                               focus:border-pink-400
                               focus:ring-2 focus:ring-pink-100
                               outline-none transition">

                </div>


                <!-- PASSWORD -->

                <div>

                    <label for="password"
                        class="block text-sm font-medium
                               text-gray-600 mb-1">

                        Contraseña

                    </label>

                    <div class="relative">

                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            placeholder="Contraseña"
                            class="w-full px-4 py-3 pr-12
                                   bg-white text-gray-700
                                   border border-gray-200
                                   rounded-xl
                                   focus:border-pink-400
                                   focus:ring-2 focus:ring-pink-100
                                   outline-none transition">

                        <button type="button" onclick="togglePassword()"
                            class="absolute right-3 top-1/2
                                   -translate-y-1/2
                                   text-gray-400
                                   hover:text-pink-400">

                            👁

                        </button>

                    </div>

                </div>


                <!-- RECORDAR -->

                <div class="flex items-center">

                    <input id="remember" type="checkbox" name="remember" value="1"
                        class="rounded border-gray-300
                               text-pink-400
                               focus:ring-pink-300">

                    <label for="remember" class="ms-2 text-sm text-gray-500">

                        Recordarme

                    </label>

                </div>


                <!-- BOTÓN -->

                <button type="submit"
                    class="w-full py-3
                           bg-pink-400
                           text-white font-semibold
                           rounded-xl shadow-md
                           hover:bg-pink-500
                           hover:-translate-y-0.5
                           transition duration-300">

                    INICIAR SESIÓN

                </button>


                <!-- REGISTRO -->

                <p class="text-sm text-center text-gray-500 pt-2">

                    ¿Todavía no tienes una cuenta?

                    <a href="{{ route('register') }}"
                        class="text-pink-500 font-medium
                               hover:text-pink-600
                               hover:underline">

                        Crear cuenta

                    </a>

                </p>


                <!-- ACCESO PERSONAL -->

                <div class="text-center pt-4 border-t border-gray-100">

                    <p class="text-xs text-gray-400 mb-1">
                        Si eres cliente, también puedes iniciar sesión con tu correo y contraseña de cliente.
                    </p>

                    <a href="{{ route('login') }}"
                        class="text-sm text-gray-500
                               hover:text-pink-500
                               transition">

                        Acceso para personal

                    </a>

                </div>

            </form>

        </div>

    </div>


    <script>
        function togglePassword() {

            const password = document.getElementById('password');

            if (password.type === 'password') {

                password.type = 'text';

            } else {

                password.type = 'password';

            }

        }
    </script>

</body>

</html>
