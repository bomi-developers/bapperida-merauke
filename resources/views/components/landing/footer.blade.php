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
                    <div class="map-container rounded-2xl p-6  relative overflow-hidden">
                        <!-- Map placeholder with Google Maps style -->
                        <div class="w-full h-full bg-gray-200 rounded-xl relative overflow-hidden">
                            {!! $websiteSettings->maps_iframe !!}
                        </div>
                        <!-- Location info overlay -->
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
                    <h3 class="text-lg font-semibold mt-6 mb-2">Alamat Kantor</h3>
                    <p class="text-blue-100 hover:text-white transition-colors text-sm">{{ $websiteSettings->alamat }}
                    </p>
                </div>

                <!-- Account & Newsletter Column -->
                <div class="text-white">
                    <!-- Account Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-6">Statistik Pengunjung</h3>
                        <ul class="space-y-1">
                            <li>
                                <strong>Online : </strong>
                                {{ number_format($pageViewOnline) }}
                            </li>
                            <li>
                                <strong>Hari ini : </strong>
                                {{ number_format($pageViewToday) }}
                            </li>
                            <li>
                                <strong>Total : </strong> {{ number_format($pageView) }}
                            </li>

                        </ul>
                    </div>
                    <!-- Newsletter Section -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Berlangganan</h3>
                        <p class="text-blue-100 text-sm mb-4">Berlangganan untuk dapat berita terupdate dari kami!</p>

                        <div class="space-y-3">
                            <input id="subscribeEmail" name="email" type="email" placeholder="Email Address"
                                class="w-full px-4 py-2 rounded-lg bg-white/10 border border-white/20 text-white placeholder-blue-200 focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-transparent">

                            <button id="subscribeBtn"
                                class="w-full bg-[#CCFF00] hover:bg-white text-[#006FFF] font-semibold py-2 px-4 rounded-lg transition-colors">
                                Subscribe Now
                            </button>

                            <div id="subscribeMsg" class="text-sm mt-2"></div>
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
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.getElementById('subscribeBtn').addEventListener('click', async () => {
                const email = document.getElementById('subscribeEmail').value;
                const msg = document.getElementById('subscribeMsg');

                msg.innerHTML = "";
                msg.className = "text-sm mt-2";

                const formData = new FormData();
                formData.append('email', email);

                try {
                    const response = await fetch("{{ route('subscription.store') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                                .content
                        },
                        body: formData
                    });

                    const result = await response.json();

                    if (result.status) {
                        msg.classList.add("text-green-300");
                        msg.innerHTML = "✔ " + result.message;
                        document.getElementById('subscribeEmail').value = "";
                    } else {
                        msg.classList.add("text-red-300");
                        msg.innerHTML = "✖ " + result.message;
                    }

                } catch (error) {
                    console.error("ERROR:", error);
                    msg.classList.add("text-red-300");
                    msg.innerHTML = "✖ Terjadi kesalahan pada server.";
                }
            });
        });
    </script>
@endpush
