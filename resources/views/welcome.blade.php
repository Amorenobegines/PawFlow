<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="Peluquería canina AliCan en Dos Hermanas. Cuidado profesional y con cariño para tu mascota.">

    <title>AliCan · Peluquería Canina</title>
    <link rel="icon" type="image/png" href="{{ asset('images/AliCan.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#fbfaf7] font-sans text-[#20312b] antialiased">
    <header class="sticky top-0 z-30 border-b border-white/70 bg-[#fbfaf7]/95 backdrop-blur">
        <nav aria-label="Navegación principal"
            class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-5 py-3 sm:px-8">
            <a href="#inicio" class="flex items-center gap-3" aria-label="AliCan, inicio">
                <img src="{{ asset('images/AliCan.png') }}" alt="" class="h-12 w-12 rounded-full object-cover">
                <span class="text-xl font-extrabold tracking-tight text-[#20312b]">AliCan</span>
            </a>

            <div
                class="order-3 flex w-full items-center justify-center gap-x-6 gap-y-2 text-sm font-semibold sm:order-none sm:w-auto sm:flex-wrap md:gap-x-8">
                <a href="#servicios" class="transition hover:text-[#e77d65]">Servicios</a>
                <a href="#galeria" class="transition hover:text-[#e77d65]">Galería</a>
                <a href="#contacto" class="transition hover:text-[#e77d65]">Contacto</a>
            </div>

            <a href="{{ route('login') }}"
                class="rounded-full bg-[#20312b] px-5 py-2.5 text-sm font-bold text-white transition hover:bg-[#314b40]">
                Reservar cita
            </a>
        </nav>
    </header>

    <main>
        <section id="inicio"
            class="relative isolate mx-auto min-h-[590px] max-w-[1600px] overflow-hidden bg-[#20312b] lg:min-h-[680px]">
            <img src="{{ asset('images/AliCan.png') }}" alt="Un perro listo para disfrutar de sus cuidados en AliCan"
                class="absolute inset-0 -z-20 h-full w-full object-cover object-center">
            <div class="absolute inset-0 -z-10 bg-gradient-to-r from-[#17251f]/90 via-[#17251f]/65 to-[#17251f]/15">
            </div>

            <div class="mx-auto flex min-h-[590px] max-w-7xl items-center px-6 py-20 sm:px-10 lg:min-h-[680px]">
                <div class="max-w-2xl text-white">
                    <p
                        class="mb-5 inline-flex items-center gap-2 rounded-full border border-white/30 bg-white/10 px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] backdrop-blur-sm">
                        <span aria-hidden="true">🐾</span> Peluquería canina · Dos Hermanas
                    </p>
                    <h1 class="text-5xl font-extrabold leading-[1.05] tracking-tight sm:text-6xl lg:text-7xl">
                        Guapos por fuera.<br>
                        <span class="text-[#ffc6ae]">Felices por dentro.</span>
                    </h1>
                    <p class="mt-6 max-w-xl text-lg leading-8 text-white/85 sm:text-xl">
                        Cuidamos de tu mejor amigo con paciencia, cariño y atención a cada detalle.
                    </p>
                    <div class="mt-9 flex flex-wrap gap-3">
                        <a href="{{ route('login') }}"
                            class="rounded-full bg-[#f18c72] px-7 py-3.5 font-bold text-[#20312b] shadow-lg transition hover:bg-[#ffa58d]">
                            Reserva su cita
                        </a>
                        <a href="#servicios"
                            class="rounded-full border border-white/60 px-7 py-3.5 font-bold text-white transition hover:bg-white/10">
                            Descubre nuestros cuidados
                        </a>
                    </div>
                    <p class="mt-8 text-sm font-medium text-white/75">Un momento de mimo para quien siempre está a tu
                        lado.</p>
                </div>
            </div>
        </section>

        <section id="servicios" class="scroll-mt-24 px-5 py-20 sm:px-8 lg:py-28">
            <div class="mx-auto max-w-7xl">
                <div class="mx-auto max-w-2xl text-center">
                    <p class="text-sm font-extrabold uppercase tracking-[0.2em] text-[#d77760]">Nuestros servicios</p>
                    <h2 class="mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl">Cuidado a su medida</h2>
                    <p class="mt-4 leading-7 text-[#62736b]">Cada peludo es único. Nos tomamos el tiempo de atender sus
                        necesidades con calma y cariño.</p>
                </div>

                <div class="mt-12 grid gap-5 md:grid-cols-3">
                    <article
                        class="rounded-3xl border border-[#e9e8df] bg-white p-7 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                        <span aria-hidden="true"
                            class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#e7f1e9] text-2xl">🫧</span>
                        <h3 class="mt-6 text-xl font-extrabold">Baño y secado</h3>
                        <p class="mt-3 leading-7 text-[#62736b]">Una limpieza agradable, con productos adecuados y un
                            secado cuidadoso para que salga estupendo.</p>
                    </article>
                    <article
                        class="rounded-3xl border border-[#e9e8df] bg-white p-7 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                        <span aria-hidden="true"
                            class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#fff0e9] text-2xl">✂️</span>
                        <h3 class="mt-6 text-xl font-extrabold">Corte y arreglo</h3>
                        <p class="mt-3 leading-7 text-[#62736b]">Un arreglo pensado para su tipo de pelo, su comodidad y
                            el estilo que mejor le sienta.</p>
                    </article>
                    <article
                        class="rounded-3xl border border-[#e9e8df] bg-white p-7 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                        <span aria-hidden="true"
                            class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#f4edda] text-2xl">💛</span>
                        <h3 class="mt-6 text-xl font-extrabold">Higiene y bienestar</h3>
                        <p class="mt-3 leading-7 text-[#62736b]">Atención a esos pequeños cuidados que ayudan a que se
                            sienta limpio, cómodo y bien cuidado.</p>
                    </article>
                </div>
            </div>
        </section>

        <section id="galeria" class="scroll-mt-24 bg-[#eef3ed] px-5 py-20 sm:px-8 lg:py-24">
            <div class="mx-auto grid max-w-7xl items-center gap-12 lg:grid-cols-[1.05fr_0.95fr]">
                <div class="relative">
                    <div class="absolute -left-4 -top-4 h-28 w-28 rounded-full bg-[#f7b49e]/60"></div>
                    <img src="{{ asset('images/pose.png') }}"
                        alt="Un cliente de AliCan disfrutando de un momento de cuidado"
                        class="relative aspect-[4/3] w-full rounded-[2rem] object-cover shadow-xl">
                    <div class="absolute -bottom-5 right-5 rounded-2xl bg-white px-5 py-3 shadow-lg sm:right-8">
                        <p class="font-extrabold">Con cariño, siempre</p>
                        <p class="mt-1 text-sm text-[#62736b]">AliCan · Dos Hermanas</p>
                    </div>
                </div>
                <div class="py-4 lg:pl-4">
                    <p class="text-sm font-extrabold uppercase tracking-[0.2em] text-[#d77760]">Un espacio para ellos
                    </p>
                    <h2 class="mt-3 text-3xl font-extrabold leading-tight tracking-tight sm:text-4xl">Pequeños
                        cuidados.<br>Grandes colas felices.</h2>
                    <p class="mt-5 leading-7 text-[#62736b]">
                        En AliCan creemos que una buena sesión empieza escuchando y respetando el ritmo de cada mascota.
                        Queremos que su visita sea una experiencia tranquila, cercana y llena de mimo.
                    </p>
                    <a href="{{ route('contacts.index') }}"
                        class="mt-7 inline-flex items-center gap-2 font-extrabold text-[#b95f4a] transition hover:text-[#873d2e]">
                        Ven a conocernos <span aria-hidden="true">→</span>
                    </a>
                </div>
            </div>
        </section>

        <section id="contacto" class="scroll-mt-24 px-5 py-20 sm:px-8 lg:py-24">
            <div
                class="mx-auto max-w-5xl overflow-hidden rounded-[2rem] bg-[#20312b] px-7 py-12 text-center text-white shadow-xl sm:px-12 sm:py-16">
                <p class="text-sm font-extrabold uppercase tracking-[0.2em] text-[#ffc6ae]">¿Listos para su próximo
                    mimo?</p>
                <h2 class="mx-auto mt-4 max-w-2xl text-3xl font-extrabold tracking-tight sm:text-4xl">Estaremos
                    encantados de cuidar de tu compañero.</h2>
                <p class="mt-4 text-white/75">Peluquería canina en Dos Hermanas, Sevilla.</p>
                <div class="mt-8 flex flex-wrap justify-center gap-3">
                    <a href="{{ route('login') }}"
                        class="rounded-full bg-[#f18c72] px-7 py-3.5 font-bold text-[#20312b] transition hover:bg-[#ffa58d]">
                        Reservar cita
                    </a>
                    <a href="{{ route('contacts.index') }}"
                        class="rounded-full border border-white/50 px-7 py-3.5 font-bold transition hover:bg-white/10">
                        Más información
                    </a>
                </div>
            </div>
        </section>
    </main>

    <footer class="border-t border-[#e9e8df] px-5 py-7 sm:px-8">
        <div
            class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-4 text-sm text-[#62736b] sm:flex-row">
            <a href="#inicio" class="font-extrabold text-[#20312b]">AliCan <span class="font-normal">· Dos
                    Hermanas</span></a>
            <div class="flex gap-5">
                <a href="#servicios" class="transition hover:text-[#d77760]">Servicios</a>
                <a href="#galeria" class="transition hover:text-[#d77760]">Galería</a>
                <a href="{{ route('contacts.index') }}" class="transition hover:text-[#d77760]">Contacto</a>
            </div>
            <p>© {{ date('Y') }} AliCan</p>
        </div>
    </footer>
</body>

</html>
