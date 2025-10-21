<nav id="main-nav" class="fixed top-0 left-0 right-0 z-50 py-4 backdrop-blur-md bg-white/10 border-b border-white/20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <img class="h-12 w-auto" src="{{ asset('assets/LogoKabMerauke.png') }}" alt="Logo BAPPERIDA" />
                <div class="flex flex-col">
                    <span class="text-white text-xl font-bold leading-tight">BAPPERIDA</span><span
                        class="text-white text-xs font-light leading-tight">Kab. Merauke</span>
                </div>
            </div>
            <div class="hidden md:flex items-center space-x-2">
                <a href="{{ url('/') }}"
                    class="text-[#006FFF] font-semibold bg-[#CCFF00] rounded-md px-3 py-1 text-sm">Beranda</a>
                <div class="relative group">
                    <button
                        class="flex items-center text-white bg-transparent rounded-md px-3 py-1 text-sm transition-colors duration-300 hover:bg-[#CCFF00]/50">
                        <span>Tentang</span>
                        <svg class="ml-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <div
                        class="absolute top-full left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20 invisible opacity-0 transform scale-95 transition-all duration-200 ease-in-out group-hover:visible group-hover:opacity-100 group-hover:scale-100">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#CCFF00]">Visi &
                            Misi</a>
                        <a href="#" class="block px-4 py-2 text-sm taext-gray-700 hover:bg-[#CCFF00]">Struktur
                            Organisasi</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#CCFF00]">Sejarah
                            Bapperida</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#CCFF00]">Tugas &
                            Fungsi</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#CCFF00]">Profil
                            Pegawai</a>
                    </div>
                </div>
                <div class="relative group">
                    <button
                        class="flex items-center text-white bg-transparent rounded-md px-3 py-1 text-sm transition-colors duration-300 hover:bg-[#CCFF00]/50">
                        <span>Produk</span>
                        <svg class="ml-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <div
                        class="absolute top-full left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20 invisible opacity-0 transform scale-95 transition-all duration-200 ease-in-out group-hover:visible group-hover:opacity-100 group-hover:scale-100">
                        {{-- Loop dinamis dari data kategori --}}
                        @if ($kategoriDocuments->isNotEmpty())
                            @foreach ($kategoriDocuments as $kategori)
                                {{-- Arahkan ke route 'documents.by_category' dengan parameter ID dan slug --}}
                                <a href="{{ route('documents.by_category', ['kategori' => $kategori->id, 'slug' => Str::slug($kategori->nama_kategori)]) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#CCFF00]">
                                    {{ $kategori->nama_kategori }}
                                </a>
                            @endforeach
                        @else
                            <span class="block px-4 py-2 text-sm text-gray-400">Tidak ada kategori</span>
                        @endif
                    </div>
                </div>
                <div class="relative group">

                    <button
                        class="flex items-center text-white bg-transparent rounded-md px-3 py-1 text-sm transition-colors duration-300 hover:bg-[#CCFF00]/50">
                        <span>PPM</span>
                        <svg class="ml-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <div
                        class="absolute top-full left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20 invisible opacity-0 transform scale-95 transition-all duration-200 ease-in-out group-hover:visible group-hover:opacity-100 group-hover:scale-100">
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#CCFF00]">Pembangunan</a>
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#CCFF00]">Pemerintahan</a>
                    </div>
                </div>
                <div class="relative group">
                    <button
                        class="flex items-center text-white bg-transparent rounded-md px-3 py-1 text-sm transition-colors duration-300 hover:bg-[#CCFF00]/50">
                        <span>Riset & Inovasi</span>
                        <svg class="ml-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <div
                        class="absolute top-full left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20 invisible opacity-0 transform scale-95 transition-all duration-200 ease-in-out group-hover:visible group-hover:opacity-100 group-hover:scale-100">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#CCFF00]">Riset</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#CCFF00]">Inovasi</a>
                    </div>
                </div>
                <div class="relative group">
                    <button
                        class="flex items-center text-white bg-transparent rounded-md px-3 py-1 text-sm transition-colors duration-300 hover:bg-[#CCFF00]/50">
                        <span>Eko-Fispor</span>
                        <svg class="ml-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>

                    <div
                        class="absolute top-full left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20 invisible opacity-0 transform scale-95 transition-all duration-200 ease-in-out group-hover:visible group-hover:opacity-100 group-hover:scale-100">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#CCFF00]">DAK</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#CCFF00]">Ekonomi</a>
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#CCFF00]">Infrastruktur</a>
                    </div>
                </div>
                <a href="#"
                    class="text-white bg-transparent rounded-md px-3 py-1 text-sm transition-colors duration-300 hover:bg-[#CCFF00]/50">Galeri</a>
            </div>
            <a href="{{ route('berita.public.home') }}"
                class="text-white bg-transparent rounded-md px-3 py-1 text-sm transition-colors duration-300 hover:bg-[#CCFF00]/50">Berita</a>
            <div>
                @guest
                    <a href="{{ route('login') }}"
                        class="bg-lime-400 text-blue-600 px-6 py-2 rounded-md text-sm font-semibold hover:bg-lime-600 transition-colors">Login</a>
                @else
                    <a href="{{ route('home') }}"
                        class="bg-lime-400 text-blue-600 px-6 py-2 rounded-md text-sm font-semibold hover:bg-lime-600 transition-colors">Dashboard</a>
                @endguest
            </div>
        </div>
    </div>
</nav>
{{-- menu samping --}}
<div id="side-nav" class="fixed top-1/2 right-4 transform -translate-y-1/2 z-50 flex items-center">

    <div id="side-search-bar" class="relative">
        <input type="text" placeholder="Ketik pencarian..."
            class="w-full bg-white/90 text-gray-800 placeholder-gray-500 rounded-full pl-5 pr-12 py-3 focus:outline-none focus:ring-2 focus:ring-[#CCFF00]">
        <button type="submit"
            class="absolute right-2 top-1/2 -translate-y-1/2 bg-[#CCFF00] text-[#006FFF] p-2 rounded-full hover:bg-yellow-400 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>
    </div>

    <div class="flex flex-col items-center space-y-2 bg-white/20 backdrop-blur-md p-2 rounded-full shadow-lg ml-2">

        <button id="toggle-search-btn"
            class="group relative flex justify-center items-center w-12 h-12 bg-white text-blue-600 rounded-full hover:bg-blue-600 hover:text-white transition-all duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <span
                class="absolute right-full mr-3 px-3 py-1.5 bg-gray-800 text-white text-xs font-semibold rounded-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                Pencarian
            </span>
        </button>

        <a href="#"
            class="group relative flex justify-center items-center w-12 h-12 bg-white text-blue-600 rounded-full hover:bg-blue-600 hover:text-white transition-all duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <span
                class="absolute right-full mr-3 px-3 py-1.5 bg-gray-800 text-white text-xs font-semibold rounded-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                Kontak
            </span>
        </a>
        <a href="#"
            class="group relative flex justify-center items-center w-12 h-12 bg-white text-blue-600 rounded-full hover:bg-blue-600 hover:text-white transition-all duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span
                class="absolute right-full mr-3 px-3 py-1.5 bg-gray-800 text-white text-xs font-semibold rounded-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                Lokasi
            </span>
        </a>
        <a href="#"
            class="group relative flex justify-center items-center w-12 h-12 bg-white text-blue-600 rounded-full hover:bg-blue-600 hover:text-white transition-all duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            <span
                class="absolute right-full mr-3 px-3 py-1.5 bg-gray-800 text-white text-xs font-semibold rounded-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                Login
            </span>
        </a>
    </div>
</div>
@push('scripts')
    <script>
        /* === SIDE NAV SELALU MUNCUL === */
        document.addEventListener('DOMContentLoaded', () => {
            const sideNav = document.getElementById('side-nav');
            if (sideNav) sideNav.classList.add('visible');
        });
    </script>

    <script>
        /* === SIDE SEARCH TOGGLE === */
        document.addEventListener('DOMContentLoaded', () => {
            const toggleButton = document.getElementById('toggle-search-btn');
            const searchBar = document.getElementById('side-search-bar');
            const searchInput = searchBar.querySelector('input');

            toggleButton.addEventListener('click', (event) => {
                event.stopPropagation();
                searchBar.classList.toggle('visible');
                if (searchBar.classList.contains('visible')) searchInput.focus();
            });

            document.addEventListener('click', (event) => {
                if (!searchBar.contains(event.target) && !toggleButton.contains(event.target)) {
                    searchBar.classList.remove('visible');
                }
            });
        });
    </script>
@endpush
