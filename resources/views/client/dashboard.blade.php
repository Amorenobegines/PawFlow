<x-client-layout>

    <div class="py-10">

        <div class="max-w-7xl mx-auto px-6">

            {{-- BIENVENIDA --}}
            <div class="mb-8">

                <h1 class="text-3xl font-bold text-gray-800">
                    ¡Hola, {{ Auth::guard('client')->user()->name }}! 🐶
                </h1>

                <p class="text-gray-500 mt-2">
                    Bienvenido/a a tu espacio de cliente de AliCan.
                </p>

            </div>


            {{-- TARJETAS PRINCIPALES --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">


                {{-- MASCOTAS --}}
                <a href="{{ route('client.pets.index') }}"
                    class="group bg-white rounded-2xl shadow-sm
                          border border-gray-100 p-6
                          hover:shadow-md hover:-translate-y-1
                          transition">

                    <div
                        class="w-12 h-12 rounded-xl
                                bg-pink-100 text-pink-500
                                flex items-center justify-center
                                text-xl mb-5">

                        <i class="fa-solid fa-paw"></i>

                    </div>

                    <h2 class="text-lg font-semibold text-gray-800">
                        Mis mascotas
                    </h2>

                    <p class="text-sm text-gray-500 mt-2">
                        Consulta y gestiona la información de tus mascotas.
                    </p>

                    <span
                        class="inline-block mt-4 text-sm
                                 font-medium text-pink-500
                                 group-hover:text-pink-600">

                        Ver mascotas →
                    </span>

                </a>


                {{-- CITAS --}}
                <a href="{{ route('client.appointments.index') }}"
                    class="group bg-white rounded-2xl shadow-sm
                          border border-gray-100 p-6
                          hover:shadow-md hover:-translate-y-1
                          transition">

                    <div
                        class="w-12 h-12 rounded-xl
                                bg-green-100 text-green-600
                                flex items-center justify-center
                                text-xl mb-5">

                        <i class="fa-solid fa-calendar-check"></i>

                    </div>

                    <h2 class="text-lg font-semibold text-gray-800">
                        Mis citas
                    </h2>

                    <p class="text-sm text-gray-500 mt-2">
                        Consulta tus próximas citas y su estado.
                    </p>

                    <span class="inline-block mt-4 text-sm
                                 font-medium text-green-600">

                        Ver mis citas →
                    </span>

                </a>


                {{-- RESERVAR --}}
                <a href="{{ route('client.appointments.create') }}"
                    class="group bg-white rounded-2xl shadow-sm
                          border border-gray-100 p-6
                          hover:shadow-md hover:-translate-y-1
                          transition">

                    <div
                        class="w-12 h-12 rounded-xl
                                bg-purple-100 text-purple-500
                                flex items-center justify-center
                                text-xl mb-5">

                        <i class="fa-solid fa-calendar-plus"></i>

                    </div>

                    <h2 class="text-lg font-semibold text-gray-800">
                        Reservar cita
                    </h2>

                    <p class="text-sm text-gray-500 mt-2">
                        Solicita una nueva cita para tu mascota.
                    </p>

                    <span
                        class="inline-block mt-4 text-sm
                                 font-medium text-purple-500">

                        Reservar ahora →
                    </span>

                </a>

            </div>


            {{-- INFORMACIÓN --}}
            <div class="mt-8 bg-white rounded-2xl shadow-sm
                        border border-gray-100 p-6">

                <div class="flex items-start gap-4">

                    <div
                        class="w-10 h-10 rounded-full
                                bg-pink-50 text-pink-500
                                flex items-center justify-center
                                shrink-0">

                        <i class="fa-solid fa-heart"></i>

                    </div>

                    <div>

                        <h2 class="font-semibold text-gray-800">
                            Cuida de tu mascota con AliCan
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            Desde aquí podrás gestionar tus mascotas,
                            consultar tus citas y reservar nuevos servicios.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-client-layout>
