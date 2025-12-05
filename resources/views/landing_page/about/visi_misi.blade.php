@section('meta_title', $meta_title)
@section('meta_description', $meta_description)
<x-landing.layout>
    {{-- FIX: Ambil data ProfileDinas jika controller lupa mengirimnya --}}
    @php
        $profile = $profile ?? \App\Models\ProfileDinas::first();
    @endphp

    {{-- 2. KONTEN UTAMA (LAYOUT GRID) --}}
    <section class="py-16 -mt-[450px] relative z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8">
                <div
                    class="bg-white rounded-2xl shadow-xl p-8 md:p-12 text-center relative overflow-hidden border-t-4 border-[#CCFF00] reveal-on-scroll">
                    <div class="relative z-10">
                        <span
                            class="inline-block py-1 px-4 rounded-full bg-blue-50 text-[#004299] text-xs font-bold tracking-wider uppercase mb-6 border border-blue-100">
                            Visi Bapperida
                        </span>

                        <div class="relative max-w-3xl mx-auto">
                            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 leading-snug">
                                "{{ optional($profile)->visi ?? 'Terwujudnya Perencanaan Pembangunan yang Berkualitas' }}"
                            </h2>
                        </div>

                        <div class="mt-8 h-1 w-24 bg-[#CCFF00] mx-auto rounded-full"></div>
                    </div>
                    {{-- Background decoration --}}
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-full opacity-50"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-[#CCFF00] rounded-tr-full opacity-20"></div>
                </div>

                {{-- BAGIAN MISI --}}
                <div
                    class="bg-white rounded-2xl shadow-xl p-8 md:p-12 border-t-4 border-[#004299] reveal-on-scroll">
                    <div class="flex items-center gap-4 mb-8 border-b border-gray-100 pb-4">
                        <div
                            class="h-12 w-12 rounded-xl bg-[#004299] flex items-center justify-center text-white shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#CCFF00]" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">Misi Pembangunan</h3>
                            <p class="text-gray-500 text-sm">Langkah strategis untuk mencapai visi.</p>
                        </div>
                    </div>

                    @if (isset($profile) && $profile->misi)
                        <div class="grid gap-6">
                            @php $no = 1; @endphp
                            @foreach (explode("\n", $profile->misi) as $misi)
                                @if (trim($misi) !== '')
                                    <div
                                        class="flex gap-5 p-6 rounded-xl border border-gray-100 bg-gray-50 hover:bg-white hover:shadow-lg transition-all duration-300 hover:-translate-y-1 group">
                                        {{-- Nomor Urut --}}
                                        <div class="flex-shrink-0">
                                            <div
                                                class="h-12 w-12 flex items-center justify-center rounded-xl bg-white text-[#004299] font-black text-xl border border-gray-200 shadow-sm group-hover:bg-[#004299] group-hover:text-[#CCFF00] transition-colors">
                                                {{ $no++ }}
                                            </div>
                                        </div>
                                        {{-- Teks Misi --}}
                                        <div class="flex-grow flex items-center">
                                            <p class="text-gray-700 text-lg leading-relaxed font-medium">
                                                {{ $misi }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        {{-- State Kosong --}}
                        <div
                            class="text-center py-16 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                            <div
                                class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-gray-500 font-medium">Data Misi belum ditambahkan oleh admin.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Animasi Scroll yang sama dengan landing page Anda */
        .reveal-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }

        .reveal-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

    <script>
        // Script sederhana untuk animasi scroll (jika di layout utama belum ada global scriptnya)
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