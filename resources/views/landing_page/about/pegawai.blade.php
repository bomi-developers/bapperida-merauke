@section('meta_title', $meta_title)
@section('meta_description', $meta_description)

<x-landing.layout>
    <section x-data="{ openModal: false, selectedPegawai: {} }" class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-[350px] pb-20 z-20">
        <div
            class="bg-white rounded-3xl shadow-xl p-8 md:p-16 border-t-8 border-[#CCFF00] reveal-on-scroll relative overflow-hidden">

            <div class="text-center mb-12 relative z-10">
                <span
                    class="inline-block py-1 px-4 rounded-full bg-blue-50 text-[#004299] text-xs font-bold tracking-wider uppercase mb-4 border border-blue-100">
                    Tentang Kami
                </span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900">Profile Pegawai</h2>
                <div class="w-24 h-1.5 bg-[#004299] mx-auto mt-6 rounded-full"></div>
            </div>

            <div class="relative z-10 space-y-12">
                @foreach ($pegawai->groupBy('id_bidang')->sortBy(fn($group) => $group->first()->bidang->urutan ?? 999) as $id_bidang => $pegawaiGroup)
                    <div class="group">
                        <div class="flex items-center mb-8">
                            <div class="flex-grow border-t-2 border-gray-200"></div>
                            <div class="px-6 py-3 bg-gradient-to-r from-[#004299] to-blue-600 rounded-full shadow-lg">
                                <h3 class="text-xl md:text-lg font-bold text-white text-center">
                                    {{ $pegawaiGroup->first()->bidang->nama_bidang ?? 'Bidang ' . $id_bidang }}
                                </h3>
                            </div>
                            <div class="flex-grow border-t-2 border-gray-200"></div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach ($pegawaiGroup as $employee)
                                @php
                                    // Definisikan logika foto di sini agar bisa dipakai di img src dan @click
                                    $isValidPhoto =
                                        $employee->foto && !Str::contains($employee->foto, ['C:\\', 'fakepath']);

                                    $fotoUrl = $isValidPhoto
                                        ? asset('storage/foto_pegawai/' . $employee->foto)
                                        : 'https://ui-avatars.com/api/?name=' .
                                            urlencode($employee->nama) .
                                            '&size=200&background=004299&color=fff&bold=true';
                                @endphp
                                <div @click="
                                    selectedPegawai = {
                                        nama: '{{ $employee->nama }}',
                                        nip: '{{ $employee->nip ?? '-' }}',
                                        nik: '{{ $employee->nik ?? '-' }}',
                                        alamat: '{{ $employee->alamat ?? '-' }}',
                                        jabatan: '{{ $employee->jabatan->jabatan ?? '-' }}',
                                        bidang: '{{ $employee->bidang->nama_bidang ?? '-' }}',
                                        foto: '{{ $fotoUrl }}'
                                    }; 
                                    openModal = true"
                                    class="group/card cursor-pointer relative bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-[#CCFF00] transform hover:-translate-y-2">

                                    <div class="h-2 bg-gradient-to-r from-[#004299] via-blue-500 to-[#CCFF00]"></div>
                                    <div class="p-6">
                                        {{-- Foto Pegawai --}}
                                        <div class="relative mb-4 mx-auto w-32 h-32">
                                            <img src="{{ $fotoUrl }}"
                                                class="relative w-full h-full rounded-full object-cover border-4 border-white shadow-lg">
                                            <div
                                                class="absolute -bottom-2 -right-2 bg-green-500 rounded-full p-2 shadow-lg border-2 border-white">
                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="text-center mb-3">
                                            <h4 class="text-lg font-bold text-gray-900 line-clamp-2 min-h-[3.5rem]">
                                                {{ $employee->nama }}</h4>
                                        </div>
                                        <div
                                            class="flex items-center justify-center space-x-2 bg-blue-50 rounded-lg py-2 px-3">
                                            <svg class="w-4 h-4 text-[#004299]" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2">
                                                </path>
                                            </svg>
                                            <span
                                                class="text-xs font-semibold text-gray-700">{{ $employee->nip ?? 'NIP: -' }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div x-show="openModal" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class=" fixed inset-0 z-[999] flex items-center justify-center p-4 overflow-y-auto" role="dialog"
            aria-modal="true">

            <div class="fixed inset-0 bg-black/20 backdrop-blur-sm" @click="openModal = false"></div>

            <div class="relative  w-full max-w-xl overflow-hidden transform transition-all" x-show="openModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100">

                <div class="relative">
                    {{-- Foto Pegawai (Melayang sedikit atau tetap di tengah) --}}
                    <div class="text-center mb-6">
                        <img :src="selectedPegawai.foto"
                            class="bg-white w-64 h-64 mx-auto rounded-full border-4 border-[#CCFF00] shadow-md object-cover">
                    </div>
                    <div class="bg-white p-4 rounded-2xl">
                        <button @click="openModal = false"
                            class="absolute right-5 top-5 text-red-800 hover:bg-red-200  rounded-full w-8 h-8 flex items-center justify-center z-50">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12">
                                </path>
                            </svg>
                        </button>
                        <div class="text-center mb-8">
                            <h3 class="text-xl font-extrabold text-gray-900 mb-1" x-text="selectedPegawai.nama"></h3>
                            <p class="text-[#004299] font-bold text-lg" x-text="selectedPegawai.jabatan"></p>
                        </div>

                        {{-- Detail Informasi --}}
                        <div class="space-y-4 border-t border-gray-100 pt-8 text-base">
                            <div class="flex justify-between border-b border-gray-50 pb-3">
                                <span class="text-gray-500 font-medium">NIP</span>
                                <span class="text-gray-900 font-bold" x-text="selectedPegawai.nip"></span>
                            </div>
                            <div class="flex justify-between border-b border-gray-50 pb-3">
                                <span class="text-gray-500 font-medium">Bidang</span>
                                <span class="text-gray-900 font-bold" x-text="selectedPegawai.bidang"></span>
                            </div>
                            <div class="flex flex-col bg-gray-50 p-4 rounded-2xl border border-gray-100">
                                <span class="text-gray-500 font-medium mb-1">Alamat</span>
                                <span class="text-gray-900 leading-relaxed" x-text="selectedPegawai.alamat"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @push('styles')
        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>
    @endpush
</x-landing.layout>
