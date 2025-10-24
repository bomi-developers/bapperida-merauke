<x-landing.layout>
    {{-- Bagian daftar berita --}}
    <section id="first-section" class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-[300px]">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                {{-- Perulangan untuk menampilkan setiap berita --}}
                @forelse ($beritas as $berita)
                    <div
                        class="bg-white rounded-2xl shadow-lg flex flex-col transition-all duration-300 hover:shadow-2xl hover:shadow-blue-500/20">

                        {{-- Gambar Cover --}}
                        <a href="{{ route('berita.public.show', $berita) }}">
                            <img src="{{ $berita->cover_image ? asset('storage/' . $berita->cover_image) : 'https://placehold.co/600x400/e2e8f0/667eea?text=Berita' }}"
                                alt="Cover Berita {{ $berita->title }}" class="w-full h-48 object-cover rounded-t-2xl">
                        </a>

                        <div class="p-5 flex flex-col flex-grow">
                            {{-- Judul Berita --}}
                            <h3 class="text-lg font-bold text-blue-800 mb-2 leading-tight">
                                <a href="{{ route('berita.public.show', $berita) }}" class="hover:underline">
                                    {{ $berita->title }}
                                </a>
                            </h3>

                            {{-- Penulis dan Tanggal --}}
                            <p class="text-xs text-gray-500 mb-3">
                                Oleh {{ $berita->author->name ?? 'Admin' }} /
                                {{ $berita->created_at->isoFormat('dddd, D MMMM YYYY') }}
                            </p>

                            {{-- Excerpt / Ringkasan --}}
                            <p class="text-sm text-gray-700 mb-4 leading-relaxed flex-grow">
                                {{ $berita->excerpt }}
                            </p>

                            {{-- Tombol Bagikan --}}
                            <div
                                class="flex items-center justify-between text-sm text-gray-600 border-t border-gray-200 pt-3 mt-auto">
                                <span>Bagikan :</span>
                                <div class="flex items-center space-x-3 text-gray-500">
                                    {{-- Tambahkan link share sosial media di sini jika perlu --}}
                                    <a href="#" class="hover:text-green-500">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.894 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.886-.001 2.269.655 4.383 1.908 6.161l-1.217 4.432 4.515-1.185z" />
                                        </svg>
                                    </a>
                                    <a href="#" class="hover:text-blue-600">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v2.385z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- Pesan jika tidak ada berita --}}
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-16">
                        <p class="text-gray-500 text-lg">Saat ini belum ada kontent yang dipublikasikan.</p>
                    </div>
                @endforelse

            </div>

            {{-- Link Paginasi --}}
            <div class="mt-12">
                {{ $beritas->links() }}
            </div>

        </div>
    </section>
</x-landing.layout>
