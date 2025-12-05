@section('meta_title', $meta_title)
@section('meta_description', $meta_description)
<x-landing.layout>
    {{-- FIX: Ambil data ProfileDinas jika controller lupa mengirimnya --}}
    @php
        $profile = $profile ?? \App\Models\ProfileDinas::first();
    @endphp

    {{-- KONTEN UTAMA --}}
    {{-- Menggunakan margin-top negatif agar naik ke area Header --}}
    <section id="struktur-content" class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-[350px] pb-20 z-20">

        <div
            class="bg-white rounded-3xl shadow-2xl p-8 md:p-16 border-t-8 border-[#CCFF00] reveal-on-scroll relative overflow-hidden">

            {{-- Header Judul Dalam Card --}}
            <div class="text-center mb-12 relative z-10">
                <span
                    class="inline-block py-1 px-4 rounded-full bg-blue-50 text-[#004299] text-xs font-bold tracking-wider uppercase mb-4 border border-blue-100">
                    Tentang Kami
                </span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900">
                    Struktur Organisasi
                </h2>
                <div class="w-24 h-1.5 bg-[#004299] mx-auto mt-6 rounded-full"></div>
                <p class="mt-4 text-gray-500 max-w-2xl mx-auto">
                    Gambaran hierarki dan pembagian tugas dalam organisasi untuk mencapai visi dan misi pembangunan
                    daerah.
                </p>
            </div>

            {{-- Konten Gambar Struktur --}}
            <div class="relative z-10 flex justify-center">
                @if (isset($profile) && $profile->struktur_organisasi)
                    <div
                        class="w-full max-w-5xl p-4 bg-gray-50 border border-gray-200 rounded-2xl shadow-inner overflow-hidden">
                        {{-- Tambahkan fitur zoom/lightbox sederhana jika memungkinkan, untuk saat ini img biasa --}}
                        <img src="{{ asset('storage/' . $profile->struktur_organisasi) }}"
                            alt="Struktur Organisasi BAPPERIDA Merauke" loading="lazy"
                            class="w-full h-auto object-contain rounded-xl hover:scale-[1.02] transition-transform duration-500">
                    </div>

                    {{-- Tombol Download / View Full --}}
                    <div class="absolute bottom-8 right-8">
                        <a href="{{ asset('storage/' . $profile->struktur_organisasi) }}" target="_blank"
                            class="flex items-center gap-2 px-5 py-3 bg-[#004299] text-white rounded-full shadow-lg hover:bg-blue-800 hover:shadow-xl transition-all transform hover:-translate-y-1 font-semibold text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                            </svg>
                            Lihat Ukuran Penuh
                        </a>
                    </div>
                @else
                    {{-- State Kosong / Fallback ke Logo Default --}}
                    <div
                        class="flex flex-col items-center justify-center py-16 text-center w-full border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50">
                        <div
                            class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center mb-4 text-gray-400 animate-pulse">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Struktur Belum Diupload</h3>
                        <p class="text-gray-500 mb-6 max-w-md">Admin belum mengunggah bagan struktur organisasi terbaru.
                            Menampilkan logo sebagai placeholder.</p>

                        <img src="{{ asset('assets/LogoKabMerauke.png') }}" alt="Logo Kabupaten Merauke"
                            class="w-32 h-auto opacity-50 grayscale hover:grayscale-0 transition-all duration-500">
                    </div>
                @endif
            </div>

            {{-- Dekorasi Background --}}
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
                <div
                    class="absolute top-[-10%] left-[-5%] w-72 h-72 bg-blue-50 rounded-full mix-blend-multiply filter blur-3xl opacity-70">
                </div>
                <div
                    class="absolute bottom-[-10%] right-[-5%] w-72 h-72 bg-[#CCFF00]/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70">
                </div>
            </div>
        </div>

    </section>

    {{-- Script Animasi Sederhana --}}
    <style>
        .reveal-on-scroll {
            opacity: 0;
            transform: translateY(40px);
            transition: all 1s ease-out;
        }

        .reveal-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, {
                threshold: 0.1
            });

            document.querySelectorAll('.reveal-on-scroll').forEach(el => observer.observe(el));
        });
    </script>
</x-landing.layout>
