@section('meta_title', $meta_title)
@section('meta_description', $meta_description)
<x-landing.layout>
    {{-- FIX: Ambil data ProfileDinas jika controller lupa mengirimnya --}}
    @php
        $profile = $profile ?? \App\Models\ProfileDinas::first();
    @endphp

    {{-- Define Title & Description untuk Header Layout (Jika layout mendukung yield) --}}
    @section('hero_title', 'Sejarah')
    @section('hero_subtitle', 'BAPPERIDA Kabupaten Merauke')
    @section('hero_description',
        'Napak tilas perjalanan dan perkembangan perencanaan pembangunan di Kabupaten
        Merauke.')

        {{-- KONTEN UTAMA --}}
        {{-- Menggunakan margin-top negatif agar naik ke area Header --}}
        <section id="sejarah-content" class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-[350px] pb-20 z-20">

            <div
                class="bg-white rounded-3xl shadow-2xl p-8 md:p-16 border-t-8 border-[#CCFF00] reveal-on-scroll relative overflow-hidden">

                {{-- Header Judul Dalam Card --}}
                <div class="text-center mb-12 relative z-10">
                    <span
                        class="inline-block py-1 px-4 rounded-full bg-blue-50 text-[#004299] text-xs font-bold tracking-wider uppercase mb-4 border border-blue-100">
                        Tentang Kami
                    </span>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900">
                        Sejarah Pembentukan
                    </h2>
                    <div class="w-24 h-1.5 bg-[#004299] mx-auto mt-6 rounded-full"></div>
                </div>

                {{-- Konten Sejarah --}}
                <div class="prose prose-lg max-w-none text-gray-600 leading-relaxed text-justify relative z-10">
                    @if (isset($profile) && $profile->sejarah)
                        {!! $profile->sejarah !!}
                    @else
                        {{-- State Kosong --}}
                        <div
                            class="flex flex-col items-center justify-center py-10 text-center border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50">
                            <div
                                class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mb-4 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Belum Ada Data</h3>
                            <p class="text-gray-500">Admin belum menambahkan informasi sejarah.</p>
                        </div>
                    @endif
                </div>

                {{-- Dekorasi Background --}}
                <div
                    class="absolute top-0 right-0 w-64 h-64 bg-blue-50 rounded-bl-full opacity-50 pointer-events-none -mr-10 -mt-10">
                </div>
                <div
                    class="absolute bottom-0 left-0 w-40 h-40 bg-[#CCFF00]/10 rounded-tr-full pointer-events-none -ml-10 -mb-10">
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
