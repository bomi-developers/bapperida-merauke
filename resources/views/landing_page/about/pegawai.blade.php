@section('meta_title', $meta_title)
@section('meta_description', $meta_description)
<x-landing.layout>
    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-[350px] pb-20 z-20">
        <div
            class="bg-white rounded-3xl shadow-xl p-8 md:p-16 border-t-8 border-[#CCFF00] reveal-on-scroll relative overflow-hidden">
            {{-- Header Judul Dalam Card --}}
            <div class="text-center mb-12 relative z-10">
                <span
                    class="inline-block py-1 px-4 rounded-full bg-blue-50 text-[#004299] text-xs font-bold tracking-wider uppercase mb-4 border border-blue-100">
                    Tentang Kami
                </span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900">
                    Profile Pegawai
                </h2>
                <div class="w-24 h-1.5 bg-[#004299] mx-auto mt-6 rounded-full"></div>
            </div>

            {{-- Konten Pegawai --}}
            <div class="relative z-10 space-y-12">
                @foreach ($pegawai->groupBy('id_bidang')->sortBy(function ($group) {
        return $group->first()->bidang->urutan ?? 999;
    }) as $id_bidang => $pegawaiGroup)
                    {{-- Section per Bidang --}}
                    <div class="group ">
                        {{-- Nama Bidang --}}
                        <div class="flex items-center mb-8">
                            <div class="flex-grow border-t-2 border-gray-200"></div>
                            <div
                                class="px-6 py-3 bg-gradient-to-r from-[#004299] to-blue-600 rounded-full shadow-lg transform transition-transform group-hover:scale-105">
                                <h3 class="text-xl md:text-lg font-bold text-white text-center">
                                    {{ $pegawaiGroup->first()->bidang->nama_bidang ?? 'Bidang ' . $id_bidang }}
                                </h3>
                            </div>
                            <div class="flex-grow border-t-2 border-gray-200"></div>
                        </div>

                        {{-- Grid Pegawai --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 ">
                            @foreach ($pegawaiGroup as $employee)
                                <div
                                    class="group/card relative bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-[#CCFF00] transform hover:-translate-y-2">
                                    {{-- Decorative Top Border --}}
                                    <div class="h-2 bg-gradient-to-r from-[#004299] via-blue-500 to-[#CCFF00]">
                                    </div>

                                    {{-- Card Content --}}
                                    <div class="p-6">
                                        {{-- Foto Pegawai --}}
                                        <div class="relative mb-4 mx-auto w-32 h-32">
                                            <div
                                                class="absolute inset-0 bg-gradient-to-br from-[#004299] to-blue-500 rounded-full opacity-20 group-hover/card:opacity-30 transition-opacity">
                                            </div>
                                            @php
                                                $hasValidPhoto = $employee->foto && 
                                                    !Str::contains($employee->foto, ['C:\\', 'fakepath']);
                                                
                                                $fotoSrc = $hasValidPhoto 
                                                    ? asset('storage/foto_pegawai/' . $employee->foto)
                                                    : "https://ui-avatars.com/api/?name=" . urlencode($employee->nama) . "&size=128&background=004299&color=fff&bold=true";
                                            @endphp
                                            <img src="{{ $fotoSrc }}"
                                                alt="{{ $employee->nama }}"
                                                class="relative w-full h-full rounded-full object-cover border-4 border-white shadow-lg group-hover/card:border-[#CCFF00] transition-all duration-300"
                                                onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($employee->nama) }}&size=128&background=004299&color=fff&bold=true'">
                                            {{-- Status Badge --}}
                                            <div
                                                class="absolute -bottom-2 -right-2 bg-green-500 rounded-full p-2 shadow-lg border-2 border-white">
                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>

                                        {{-- Nama Pegawai --}}
                                        <div class="text-center mb-3">
                                            <h4
                                                class="text-lg font-bold text-gray-900 group-hover/card:text-[#004299] transition-colors line-clamp-2 min-h-[3.5rem]">
                                                {{ $employee->nama }}
                                            </h4>
                                        </div>

                                        {{-- NIP --}}
                                        <div
                                            class="flex items-center justify-center space-x-2 bg-blue-50 rounded-lg py-2 px-3 border border-blue-100">
                                            <svg class="w-4 h-4 text-[#004299]" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                            </svg>
                                            <span class="text-xs font-semibold text-gray-700">
                                                {{ $employee->nip ?? 'NIP: -' }}
                                            </span>
                                        </div>

                                        {{-- Optional: Jabatan jika ada --}}
                                        @if (isset($employee->jabatan))
                                            <div class="mt-3 text-center">
                                                <span
                                                    class="inline-block px-3 py-1 bg-[#CCFF00]/20 text-[#004299] text-xs font-medium rounded-full border border-[#CCFF00]">
                                                    {{ $employee->jabatan->jabatan }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Hover Effect Overlay --}}
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-[#004299]/5 to-transparent opacity-0 group-hover/card:opacity-100 transition-opacity pointer-events-none">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
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
</x-landing.layout>
