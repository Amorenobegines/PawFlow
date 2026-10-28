<nav class="bg-white border-b border-pink-100 shadow-sm">

    <div class="max-w-7xl mx-auto px-6">

        <div class="flex justify-between items-center h-20">

            <!-- LOGO -->
            <a href="{{ route('client.dashboard') }}" class="flex items-center gap-3">

                <img src="{{ asset('images/AliCan.png') }}" alt="AliCan" class="h-12 w-auto">

                <div class="hidden sm:block">

                    <span class="block text-xl font-bold text-gray-800">
                        AliCan
                    </span>

                    <span class="block text-xs text-gray-400">
                        Peluquería Canina
                    </span>

                </div>

            </a>


            <!-- MENU -->
            <div class="flex items-center gap-6">

                <a href="{{ route('client.dashboard') }}"
                    class="text-sm font-medium text-gray-600 hover:text-pink-500 transition">

                    Inicio

                </a>

                <a href="{{ route('client.pets.index') }}"
                    class="text-sm font-medium text-gray-600 hover:text-pink-500 transition">
                    Mis mascotas
                </a>

                <a href="{{ route('client.appointments.index') }}"
                    class="text-sm font-medium text-gray-600 hover:text-pink-500 transition">
                    Mis citas
                </a>

                <a href="{{ route('client.appointments.create') }}"
                    class="text-sm font-medium text-gray-600 hover:text-pink-500 transition">
                    Reservar
                </a>


                <span class="text-sm text-gray-400">

                    Hola,
                    {{ Auth::guard('client')->user()->name }}

                </span>


                <!-- LOGOUT -->
                <form method="POST" action="{{ route('client.logout') }}">

                    @csrf

                    <button type="submit"
                        class="px-4 py-2 rounded-xl bg-pink-100 text-pink-700
                               hover:bg-pink-200 transition text-sm font-medium">

                        <i class="fa-solid fa-right-from-bracket mr-2"></i>

                        Cerrar sesión

                    </button>

                </form>

            </div>

        </div>

    </div>

</nav>
