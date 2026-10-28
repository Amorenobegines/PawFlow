<x-client-layout>

    @php($isEdit = isset($pet))

    <div class="py-10">

        <div class="max-w-4xl mx-auto px-6">

            {{-- CABECERA --}}
            <div class="mb-8">

                <a href="{{ route('client.pets.index') }}"
                    class="inline-flex items-center text-sm
                          text-gray-500 hover:text-pink-500 transition">

                    <i class="fa-solid fa-arrow-left mr-2"></i>

                    Volver a mis mascotas

                </a>

                <h1 class="text-3xl font-bold text-gray-800 mt-5">
                    {{ $isEdit ? 'Editar mascota 🐶' : 'Añadir mascota 🐶' }}
                </h1>

                <p class="text-gray-500 mt-2">
                    {{ $isEdit ? 'Actualiza los datos de tu mascota.' : 'Introduce los datos de tu mascota para registrarla en AliCan.' }}
                </p>

            </div>


            {{-- ERRORES --}}
            @if ($errors->any())

                <div
                    class="mb-6 rounded-xl
                            bg-red-50 border border-red-100
                            text-red-700 px-5 py-4">

                    <p class="font-medium mb-2">
                        Hay algunos errores en el formulario:
                    </p>

                    <ul class="list-disc list-inside text-sm">

                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- FORMULARIO --}}
            <div class="bg-white rounded-2xl
                        shadow-sm border border-gray-100 p-8">

                <form method="POST"
                    action="{{ $isEdit ? route('client.pets.update', $pet) : route('client.pets.store') }}"
                    enctype="multipart/form-data">

                    @csrf
                    @if ($isEdit)
                        @method('PUT')
                    @endif


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                        {{-- NOMBRE --}}
                        <div>

                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">

                                Nombre *

                            </label>

                            <input id="name" type="text" name="name" value="{{ old('name', $pet->name ?? '') }}" required
                                class="w-full rounded-xl border-gray-200
                                       focus:border-pink-400
                                       focus:ring-pink-400"
                                placeholder="Ej. Toby">

                            @error('name')
                                <p class="text-sm text-red-500 mt-1">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- RAZA --}}
                        <div>

                            <label for="breed" class="block text-sm font-medium text-gray-700 mb-2">

                                Raza *

                            </label>

                            <input id="breed" type="text" name="breed" value="{{ old('breed', $pet->breed ?? '') }}" required
                                class="w-full rounded-xl border-gray-200
                                       focus:border-pink-400
                                       focus:ring-pink-400"
                                placeholder="Ej. Golden Retriever">

                            @error('breed')
                                <p class="text-sm text-red-500 mt-1">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- EDAD --}}
                        <div>

                            <label for="age" class="block text-sm font-medium text-gray-700 mb-2">

                                Edad *

                            </label>

                            <input id="age" type="number" name="age" value="{{ old('age', $pet->age ?? '') }}"
                                min="0" required
                                class="w-full rounded-xl border-gray-200
                                       focus:border-pink-400
                                       focus:ring-pink-400"
                                placeholder="Ej. 5">

                            @error('age')
                                <p class="text-sm text-red-500 mt-1">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- PESO --}}
                        <div>

                            <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">

                                Peso (kg) *

                            </label>

                            <input id="weight" type="number" name="weight" value="{{ old('weight', $pet->weight ?? '') }}"
                                min="0" step="0.1" required
                                class="w-full rounded-xl border-gray-200
                                       focus:border-pink-400
                                       focus:ring-pink-400"
                                placeholder="Ej. 12.5">

                            @error('weight')
                                <p class="text-sm text-red-500 mt-1">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- SEXO --}}
                        <div>

                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">

                                Sexo *

                            </label>

                            <select id="gender" name="gender" required
                                class="w-full rounded-xl border-gray-200
                                       focus:border-pink-400
                                       focus:ring-pink-400">

                                <option value="">
                                    Selecciona una opción
                                </option>

                                <option value="Male" {{ old('gender', $pet->gender ?? '') === 'Male' ? 'selected' : '' }}>
                                    Macho
                                </option>

                                <option value="Female" {{ old('gender', $pet->gender ?? '') === 'Female' ? 'selected' : '' }}>
                                    Hembra
                                </option>

                            </select>

                            @error('gender')
                                <p class="text-sm text-red-500 mt-1">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- FOTO --}}
                        <div>

                            <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">

                                Foto

                            </label>

                            @if ($isEdit && $pet->photo)
                                <div
                                    class="mb-4 flex h-48 items-center justify-center overflow-hidden rounded-xl border border-pink-100 bg-gradient-to-br from-pink-50 via-white to-teal-50 p-3">
                                    <img src="{{ asset('storage/' . $pet->photo) }}"
                                        alt="Foto actual de {{ $pet->name }}"
                                        class="max-h-full max-w-full rounded-lg object-contain shadow-sm">
                                </div>
                            @endif

                            <input id="photo" type="file" name="photo" accept="image/*"
                                class="w-full rounded-xl border
                                       border-gray-200 p-2
                                       text-sm text-gray-500
                                       file:mr-4
                                       file:rounded-lg
                                       file:border-0
                                       file:bg-pink-100
                                       file:px-4
                                       file:py-2
                                       file:text-pink-700">

                            <p class="text-xs text-gray-400 mt-2">
                                Opcional. La foto debe pesar menos de 2 MB.
                            </p>

                            @error('photo')
                                <p class="text-sm text-red-500 mt-1">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                    </div>


                    {{-- ALERGIAS --}}
                    <div class="mt-6">

                        <label class="flex items-center gap-3 cursor-pointer">

                            <input type="checkbox" name="allergic" value="1"
                                {{ old('allergic', $pet->allergic ?? false) ? 'checked' : '' }}
                                class="rounded border-gray-300
                                       text-pink-500
                                       focus:ring-pink-400">

                            <span class="text-sm text-gray-700">

                                Mi mascota tiene alergias

                            </span>

                        </label>

                    </div>


                    {{-- OBSERVACIONES --}}
                    <div class="mt-6">

                        <label for="observations" class="block text-sm font-medium text-gray-700 mb-2">

                            Observaciones

                        </label>

                        <textarea id="observations" name="observations" rows="4"
                            class="w-full rounded-xl border-gray-200
                                   focus:border-pink-400
                                   focus:ring-pink-400"
                            placeholder="Información adicional sobre tu mascota...">{{ old('observations', $pet->observations ?? '') }}</textarea>

                        @error('observations')
                            <p class="text-sm text-red-500 mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- BOTONES --}}
                    <div
                        class="flex flex-col-reverse sm:flex-row
                                sm:justify-end gap-3 mt-8 pt-6
                                border-t border-gray-100">

                        <a href="{{ route('client.pets.index') }}"
                            class="px-5 py-3 rounded-xl
                                  border border-gray-200
                                  text-gray-600 text-center
                                  hover:bg-gray-50 transition">

                            Cancelar

                        </a>

                        <button type="submit"
                            class="px-5 py-3 rounded-xl
                                  border border-gray-200
                                  text-gray-600 text-center
                                  hover:bg-gray-50 transition">

                            <i class="fa-solid fa-paw mr-2"></i>

                            {{ $isEdit ? 'Actualizar mascota' : 'Guardar mascota' }}

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-client-layout>
