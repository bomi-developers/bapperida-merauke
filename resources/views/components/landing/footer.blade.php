<footer class=" bg-[url('/assets/backgroundfooter.svg')] bg-cover bg-center">
    <!-- Curved top border -->
    <div class="absolute top-0 left-0 w-full h-20 curved-top transform -translate-y-10"></div>

    <div class="relative pt-20 pb-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header with Logo -->
            <div class="flex items-center justify-between mb-12 pt-20" style="margin-top: 100pt;">
                <div class="flex items-center space-x-4">
                    <!-- Logo Placeholder -->
                    <img class="h-12 w-auto" src="/assets/LogoKabMerauke.png" alt="Logo BAPPERIDA" loading="lazy" />
                    <div>
                        <h2 class="text-white text-2xl font-bold">BAPPERIDA</h2>
                        <p class="text-blue-100 text-sm">KAB. MERAUKE</p>
                    </div>
                </div>
                <!-- Decorative line -->
                <div class="hidden lg:block flex-1 mx-8">
                    <div class="h-px bg-gradient-to-r from-transparent via-white/30 to-transparent"></div>
                </div>
            </div>

            <!-- Main Footer Content -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 lg:gap-12">
                <!-- Map Section -->
                <div class="lg:col-span-2">
                    <div class="map-container rounded-2xl p-6 h-80 relative overflow-hidden">
                        <!-- Map placeholder with Google Maps style -->
                        <div class="w-full h-full bg-gray-200 rounded-xl relative overflow-hidden">
                            <!-- Map background -->
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-blue-100"></div>

                            <!-- Roads/Streets -->
                            <div class="absolute top-1/4 left-0 w-full h-1 bg-white/60"></div>
                            <div class="absolute top-1/2 left-0 w-full h-1 bg-white/60"></div>
                            <div class="absolute top-3/4 left-0 w-full h-1 bg-white/60"></div>
                            <div class="absolute left-1/4 top-0 w-1 h-full bg-white/60"></div>
                            <div class="absolute left-1/2 top-0 w-1 h-full bg-white/60"></div>
                            <div class="absolute left-3/4 top-0 w-1 h-full bg-white/60"></div>

                            <!-- Location marker -->
                            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center shadow-lg">
                                    <div class="w-4 h-4 bg-white rounded-full"></div>
                                </div>
                            </div>

                            <!-- Map controls -->
                            <div class="absolute bottom-4 left-4 flex flex-col space-y-2">
                                <button
                                    class="w-8 h-8 bg-white rounded shadow flex items-center justify-center text-gray-600 text-sm font-bold">+</button>
                                <button
                                    class="w-8 h-8 bg-white rounded shadow flex items-center justify-center text-gray-600 text-sm font-bold">-</button>
                            </div>

                            <!-- Google Maps logo placeholder -->
                            <div class="absolute bottom-2 right-2 text-xs text-gray-500 bg-white px-2 py-1 rounded">
                                Maps
                            </div>
                        </div>

                        <!-- Location info overlay -->
                        <div class="absolute bottom-6 left-6 right-6">
                            <div class="bg-white rounded-lg p-4 shadow-lg">
                                <h3 class="font-semibold text-gray-800 text-sm">Kantor Bapperida</h3>
                                <p class="text-gray-600 text-xs mt-1">Jl. Raya Mandala, Merauke</p>
                                <div class="flex items-center mt-2 space-x-4">
                                    <button class="text-blue-600 text-xs hover:underline">Directions</button>
                                    <button class="text-blue-600 text-xs hover:underline">Save</button>
                                    <button class="text-blue-600 text-xs hover:underline">Share</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Services Column -->
                <div class="text-white">
                    <h3 class="text-lg font-semibold mb-6">Tentang</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ url('/about/visi-misi') }}"
                                class="text-blue-100 hover:text-white transition-colors text-sm">Visi dan
                                Misi</a>
                        </li>
                        <li><a href="{{ url('/about/struktur-organisasi') }}"
                                class="text-blue-100 hover:text-white transition-colors text-sm">Struktur
                                Organisasi</a></li>
                        <li><a href="{{ url('/about/sejarah') }}"
                                class="text-blue-100 hover:text-white transition-colors text-sm">Sejarah
                                Bapperida</a>
                        </li>
                        <li><a href="{{ url('/about/tugas-fungsi') }}"
                                class="text-blue-100 hover:text-white transition-colors text-sm">Tugas dan
                                Fungsi
                            </a></li>
                        <li><a href="{{ url('/about/pegawai') }}"
                                class="text-blue-100 hover:text-white transition-colors text-sm">Profil
                                Pegawai
                            </a></li>
                    </ul>
                </div>

                <!-- Account & Newsletter Column -->
                <div class="text-white">
                    <!-- Account Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-6">Akun</h3>
                        <ul class="space-y-1">
                            <li>
                                @guest
                                    <a href="{{ route('login') }}"
                                        class="text-blue-100 hover:text-white transition-colors text-sm">Sign
                                        In</a>
                                @else
                                    <a href="{{ route('home') }}"
                                        class="text-blue-100 hover:text-white transition-colors text-sm">Sign
                                        In</a>
                                    @endif
                                </li>
                            </ul>
                        </div>

                        <!-- Newsletter Section -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Stay in Touch</h3>
                            <p class="text-blue-100 text-sm mb-4">Subscribe now for exclusive insights and
                                offers!</p>

                            <div class="space-y-3">
                                <input type="email" placeholder="Email Address"
                                    class="w-full px-4 py-2 rounded-lg bg-white/10 border border-white/20 text-white placeholder-blue-200 focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-transparent">
                                <button
                                    class="w-full bg-[#CCFF00] hover:bg-white text-[#006FFF] font-semibold py-2 px-4 rounded-lg transition-colors">
                                    Subscribe Now
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Copyright -->
                <div class="mt-12 pt-8 border-t border-white/20">
                    <p class="text-center text-blue-100 text-sm">
                        © {{ date('Y') }} Bapperida . All Rights Reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>
