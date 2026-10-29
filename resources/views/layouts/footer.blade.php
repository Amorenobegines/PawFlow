<footer class="relative text-gray-700 overflow-hidden "
    style="background-image:
               radial-gradient(circle at 10px 10px, #a0eec6 2px, transparent 2px),
               radial-gradient(circle at 10px 10px, #fbcfe8 2px, transparent 2px);
               background-size: 50px 50px;">

    <!-- ================= DECORACIÓN SUPERIOR ================= -->

    <div class="absolute top-6 left-8 text-teal-200 text-3xl opacity-60">
        🐾
    </div>

    <div class="absolute top-16 right-16 text-teal-200 text-2xl opacity-50">
        🐾
    </div>

    <!-- ================= CONTENIDO ================= -->

    <div class="relative z-10 max-w-6xl mx-auto px-4 pt-8 pb-12">

        <div class="grid gap-12 items-start" style="grid-template-columns: repeat(4, minmax(0, 1fr));">

            <!-- ================= LOGO ================= -->
            <div class="text-left">
                <a href="/" class="inline-block">
                    <img src="/images/AliCan.png" alt="AliCan Peluquería Canina en Dos Hermanas"
                        class="h-16 w-auto mb-5">
                </a>

                <p class="text-sm leading-6 max-w-xs text-gray-600">
                    Peluquería canina en
                    <strong class="text-gray-800">Dos Hermanas (Sevilla)</strong>.
                </p>

                <p class="mt-4 text-sm leading-6 max-w-xs italic text-gray-500">
                    "Porque ellos también merecen sentirse guapos y cuidados."
                </p>

                <div class="flex items-center gap-2 mt-6">
                    <span class="w-8 h-[2px] bg-teal-300"></span>
                    <span class="text-teal-500 text-lg">🐾</span>
                    <span class="w-8 h-[2px] bg-teal-300"></span>
                </div>
            </div>

            <!-- ================= ENLACES ================= -->
            <div class="text-center">
                <h3 class="font-semibold text-lg text-gray-800 mb-6">Descubre AliCan</h3>

                <ul class="space-y-4 text-sm">
                    <li><a href="/" class="hover:text-teal-600 transition duration-200">Inicio</a></li>
                    <li><a href="/services" class="hover:text-teal-600 transition duration-200">Nuestros servicios</a>
                    </li>
                    <li><a href="/citas" class="hover:text-teal-600 transition duration-200">Pedir cita</a></li>
                    <li><a href="/contacto" class="hover:text-teal-600 transition duration-200">Contacto</a></li>
                </ul>

                <a href="/citas"
                    class="inline-flex items-center gap-2 mt-7 px-6 py-2.5 rounded-full
                           bg-teal-500 text-white text-sm font-medium hover:bg-teal-600
                           transition duration-200 shadow-sm">
                    🐾 Reservar cita
                </a>
            </div>

            <!-- ================= CONTACTO ================= -->
            <div class="text-left">
                <h3 class="font-semibold text-lg text-gray-800 mb-6">Estamos aquí para ti</h3>

                <div class="bg-white rounded-2xl p-5 shadow-sm border border-teal-100">
                    <div class="space-y-4 text-sm">

                        <div class="flex items-start gap-3">
                            <span class="text-lg">📍</span>
                            <div>
                                <p class="font-medium text-gray-800">Ubicación</p>
                                <a href="https://maps.google.com/?q=Dos+Hermanas+Sevilla" target="_blank"
                                    class="text-gray-500 hover:text-teal-600 transition">
                                    Dos Hermanas, Sevilla
                                </a>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="text-lg">📞</span>
                            <div>
                                <p class="font-medium text-gray-800">Teléfono</p>
                                <a href="tel:+34612345678" class="text-gray-500 hover:text-teal-600 transition">
                                    612 345 678
                                </a>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="text-lg">✉️</span>
                            <div>
                                <p class="font-medium text-gray-800">Email</p>
                                <a href="mailto:alican@gmail.com" class="text-gray-500 hover:text-teal-600 transition">
                                    alican@gmail.com
                                </a>
                            </div>
                        </div>

                        🐾
                    </div>
                </div>
            </div>

            <!-- ================= REDES ================= -->
            <div class="text-left">
                <h3 class="font-semibold text-lg text-gray-800 mb-6">Síguenos en redes</h3>

                <div class="bg-white rounded-2xl p-5 shadow-sm border border-teal-100">
                    <div class="space-y-4 text-sm">

                        <a href="https://www.instagram.com/loimaginariodearya?xtok=c2RxcDA0ZTE4dDQ=" title="Instagram"
                            class="w-12 h-12 rounded-full bg-white border border-teal-100
                                   flex items-center justify-center hover:bg-pink-100 hover:text-pink-500 transition">
                            <i class="fa-brands fa-instagram"></i>
                        </a>

                        <a href="https://www.facebook.com/profile.php?id=100046841806827" title="Facebook"
                            class="w-12 h-12 rounded-full bg-white border border-teal-100
                                   flex items-center justify-center hover:bg-blue-100 hover:text-blue-500 transition">
                            <i class="fa-brands fa-facebook"></i>
                        </a>

                        <a href="https://wa.me/34660314322" title="WhatsApp"
                            class="w-12 h-12 rounded-full bg-white border border-teal-100
                                   flex items-center justify-center hover:bg-green-100 hover:text-green-500 transition">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>

                    </div>
                </div>

                🐾
            </div>

            <!-- ================= SEPARADOR ================= -->
            <div class="relative z-20 border-t border-teal-200">
                <div class="flex justify-between items-center text-xs text-gray-400">
                    <p>© {{ date('Y') }} AliCan</p>
                    <p>Peluquería Canina · Dos Hermanas (Sevilla)</p>
                </div>
            </div>

        </div>
    </div>

</footer>
