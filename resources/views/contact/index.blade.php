<x-app-layout>
    <x-slot name="header">
        <div class=" flex flex-col gap-1">
            <p class="text-sm font-semibold uppercase tracking-wider text-teal-600">AliCan · Dos Hermanas</p>
            <h1 class="mt-1 text-2xl font-bold leading-tight text-gray-800">Contacto</h1>
        </div>
    </x-slot>

    <div class="bg-gradient-to-b from-teal-50/70 to-gray-50 py-10 sm:py-14 ">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 ">
            <section class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-gray-100 mb-6">
                <div class="grid lg:grid-cols-2 ">
                    <div class="p-8 sm:p-10 lg:p-12">
                        <span
                            class="inline-flex items-center gap-2 rounded-full bg-pink-50 px-4 py-2 text-sm font-medium text-pink-700">
                            <i class="fa-solid fa-paw"></i>
                            Estamos aquí para ayudarte
                        </span>

                        <h2 class="mt-6 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                            Hablemos de tu mascota
                        </h2>
                        <p class="mt-4 max-w-xl leading-7 text-gray-600">
                            ¿Tienes alguna pregunta sobre nuestros servicios o quieres preparar una visita?
                            Ponte en contacto con AliCan. Estaremos encantados de atenderte.
                        </p>

                        <div class="mt-8 flex flex-wrap gap-3">
                            <a href="https://wa.me/34660314322"
                                class="inline-flex items-center gap-2 rounded-xl bg-teal-600 px-5 py-3 font-semibold text-white shadow-sm transition hover:bg-teal-700">
                                <i cl ass="fa-brands fa-whatsapp text-lg"></i>
                                Escríbenos por WhatsApp
                            </a>
                            <a href="{{ route('appointments.create') }}"
                                class="inline-flex items-center gap-2 rounded-xl border border-pink-200 bg-pink-50 px-5 py-3 font-semibold text-pink-700 transition hover:bg-pink-100 ">
                                <i class="fa-regular fa-calendar"></i>
                                Pedir cita
                            </a>
                        </div>
                    </div>

                    <div
                        class="relative min-h-64 bg-gradient-to-br from-teal-100 via-pink-50 to-pink-100 p-8 sm:p-10 lg:min-h-full ">
                        <div class="absolute -right-8 -top-8 h-40 w-40 rounded-full bg-white/40"></div>
                        <div class="absolute -bottom-12 -left-8 h-48 w-48 rounded-full bg-teal-200/30"></div>
                        <div
                            class="relative flex h-full min-h-56 flex-col justify-center rounded-2xl border border-white/70 bg-white/75 p-7 shadow-sm backdrop-blur-sm">
                            <i class="fa-solid fa-heart text-3xl text-pink-400"></i>
                            <p class="mt-5 text-2xl font-bold leading-snug text-gray-800">
                                Cuidado y cariño para quienes más quieres.
                            </p>
                            <p class="mt-3 text-sm leading-6 text-gray-600">
                                Peluquería canina en Dos Hermanas, Sevilla.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <section class=" grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <a href="tel:+34612345678"
                    class="group rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <span
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-teal-50 text-xl text-teal-600">
                        <i class="fa-solid fa-phone"></i>
                    </span>
                    <h3 class="mt-5 font-semibold text-gray-900">Llámanos</h3>
                    <p class="mt-2 text-gray-600">612 345 678</p>
                    <span
                        class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-teal-700 group-hover:text-teal-800">
                        Llamar ahora <i class="fa-solid fa-arrow-right text-xs"></i>
                    </span>
                </a>

                <a href="mailto:alican@gmail.com"
                    class="group rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <span
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-pink-50 text-xl text-pink-600">
                        <i class="fa-regular fa-envelope"></i>
                    </span>
                    <h3 class="mt-5 font-semibold text-gray-900">Escríbenos</h3>
                    <p class="mt-2 break-all text-gray-600">alican@gmail.com</p>
                    <span
                        class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-teal-700 group-hover:text-teal-800">
                        Enviar un correo <i class="fa-solid fa-arrow-right text-xs"></i>
                    </span>
                </a>

                <a href="https://maps.google.com/?q=Dos+Hermanas+Sevilla" target="_blank" rel="noopener noreferrer"
                    class="group rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md sm:col-span-2 lg:col-span-1">
                    <span
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-xl text-amber-600">
                        <i class="fa-solid fa-location-dot"></i>
                    </span>
                    <h3 class="mt-5 font-semibold text-gray-900">Encuéntranos</h3>
                    <p class="mt-2 text-gray-600">Dos Hermanas, Sevilla</p>
                    <span
                        class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-teal-700 group-hover:text-teal-800">
                        Ver en el mapa <i class="fa-solid fa-arrow-right text-xs"></i>
                    </span>
                </a>
            </section>
        </div>
    </div>
</x-app-layout>
