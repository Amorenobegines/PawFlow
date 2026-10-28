<x-client-layout>

    <div class="py-10">

        <div class="max-w-7xl mx-auto px-6">

            {{-- CABECERA --}}
            <div
                class="flex flex-col sm:flex-row
                        sm:items-center sm:justify-between
                        gap-4 mb-8">

                <div>

                    <h1 class="text-3xl font-bold text-gray-800">
                        Mis mascotas 🐶
                    </h1>

                    <p class="text-gray-500 mt-2">
                        Aquí puedes consultar las mascotas que tienes registradas.
                    </p>

                </div>

                <a href="{{ route('client.pets.create') }}"
                    class="inline-flex items-center justify-center
                          px-5 py-3 rounded-xl
                          bg-pink-500 text-zinc-50
                          hover:bg-pink-600
                          transition font-medium">

                    <i class="fa-solid fa-plus mr-2"></i>

                    Añadir mascota

                </a>

            </div>


            {{-- MENSAJE DE ÉXITO --}}
            @if (session('success'))
                <div
                    class="mb-6 rounded-xl
                            bg-green-50 border border-green-100
                            text-green-700 px-5 py-4">

                    <i class="fa-solid fa-circle-check mr-2"></i>

                    {{ session('success') }}

                </div>
            @endif


            {{-- MASCOTAS --}}
            @if ($pets->isEmpty())

                <div
                    class="bg-white rounded-2xl
                            shadow-sm border border-gray-100
                            p-10 text-center">

                    <div
                        class="w-16 h-16 mx-auto
                                rounded-full bg-pink-50
                                text-pink-500
                                flex items-center justify-center
                                text-2xl">

                        <i class="fa-solid fa-paw"></i>

                    </div>

                    <h2 class="mt-5 text-xl font-semibold text-gray-800">
                        Todavía no tienes mascotas registradas
                    </h2>

                    <p class="mt-2 text-gray-500">
                        Añade tu primera mascota para poder reservar citas.
                    </p>

                    <a href="{{ route('client.pets.create') }}"
                        class="inline-block mt-6
                              px-5 py-3 rounded-xl
                              bg-pink-500 text-white
                              hover:bg-pink-600 transition">

                        Añadir mi mascota

                    </a>

                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                    @foreach ($pets as $pet)
                        <div
                            class="bg-white rounded-2xl
                                    shadow-sm border border-gray-100
                                    overflow-hidden">

                            {{-- FOTO --}}
                            <div
                                class="relative flex aspect-[4/3] items-center justify-center overflow-hidden bg-gradient-to-br from-pink-50 via-white to-teal-50 p-4">

                                @if ($pet->photo)
                                    <img src="{{ asset('storage/' . $pet->photo) }}" alt="{{ $pet->name }}"
                                        class="max-h-full max-w-full rounded-xl object-contain shadow-sm">
                                @else
                                    <i
                                        class="fa-solid fa-dog
                                              text-5xl text-pink-300"></i>
                                @endif

                            </div>


                            {{-- INFORMACIÓN --}}
                            <div class="p-6">

                                <h2 class="text-xl font-bold text-gray-800">
                                    {{ $pet->name }}
                                </h2>

                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $pet->breed }}
                                </p>

                                <div class="mt-4 space-y-2 text-sm text-gray-600">

                                    <p>
                                        <i
                                            class="fa-solid fa-cake-candles
                                                  w-5 text-pink-400"></i>

                                        {{ $pet->age }} años
                                    </p>

                                    <p>
                                        <i
                                            class="fa-solid fa-weight-scale
                                                  w-5 text-pink-400"></i>

                                        {{ $pet->weight }} kg
                                    </p>

                                    <p>
                                        <i
                                            class="fa-solid fa-venus-mars
                                                  w-5 text-pink-400"></i>

                                        {{ $pet->gender }}
                                    </p>

                                </div>

                                @if ($pet->allergic)
                                    <div
                                        class="mt-4 px-3 py-2
                                                rounded-lg
                                                bg-red-50 text-red-600
                                                text-sm">

                                        <i class="fa-solid fa-triangle-exclamation mr-1"></i>

                                        Tiene alergias

                                    </div>
                                @endif

                            </div>

                            <div class="flex gap-3 mt-6 pt-5 border-t border-gray-100">

                                <a href="{{ route('client.pets.edit', $pet) }}"
                                    class="flex-1 text-center px-4 py-2 rounded-xl
                                            bg-gray-100 text-gray-700
                                            hover:bg-gray-200 transition text-sm">

                                    <i class="fa-solid fa-pen mr-1"></i>

                                    Editar

                                </a>

                                <form method="POST" action="{{ route('client.pets.destroy', $pet) }}" class="flex-1"
                                    onsubmit="return confirm('¿Seguro que quieres eliminar esta mascota?');">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="w-full px-4 py-2 rounded-xl
                                                bg-red-50 text-red-600
                                                hover:bg-red-100 transition text-sm">

                                        <i class="fa-solid fa-trash mr-1"></i>

                                        Eliminar

                                    </button>

                                </form>

                            </div>
                        </div>
                    @endforeach

                </div>

            @endif

        </div>

    </div>

</x-client-layout>
