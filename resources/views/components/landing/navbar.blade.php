<nav id="main-nav"
    class="fixed top-0 left-0 right-0 z-50 py-3 backdrop-blur-md bg-[#0044A9]/50 border-b border-white/20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center space-x-3">
                <img class="h-12 w-auto" src="{{ asset('assets/LogoKabMerauke.png') }}" alt="Logo BAPPERIDA" />
                <div class="flex flex-col">
                    <span class="text-white text-xl font-bold leading-tight">BAPPERIDA</span>
                    <span class="text-white text-xs font-light leading-tight">Kab. Merauke</span>
                </div>
            </a>

            {{-- Menu Desktop --}}
            <div class="hidden md:flex items-center space-x-2">
                <a href="{{ url('/') }}"
                    class="rounded-md px-3 py-1 text-sm font-semibold transition-colors duration-300
                        {{ request()->url() == url('/') ? 'bg-lime-400 text-blue-600' : 'text-white hover:bg-[#CCFF00]/50' }}">
                    Beranda
                </a>

                @foreach ($menus as $menu)
                    {{-- @php
                        $isActive = isset($menu['route'])
                            ? Route::currentRouteName() === $menu['route']
                            : request()->url() == ($menu['url'] ?? '');
                        $menuUrl = isset($menu['route']) ? route($menu['route']) : $menu['url'] ?? '#';
                    @endphp --}}
                    @php
                        // Cek apakah item ini punya submenu
                        $hasSubmenu = isset($menu['submenu']) && is_array($menu['submenu']);

                        // Tentukan apakah menu utama aktif
                        $isActive = isset($menu['route'])
                            ? Route::currentRouteName() === $menu['route']
                            : request()->url() == ($menu['url'] ?? '');

                        // Jika tidak aktif, periksa apakah ada submenu yang aktif
                        if (!$isActive && $hasSubmenu) {
                            foreach ($menu['submenu'] as $submenu) {
                                $submenuActive = isset($submenu['route'])
                                    ? Route::currentRouteName() === $submenu['route']
                                    : request()->url() == ($submenu['url'] ?? '');

                                if ($submenuActive) {
                                    $isActive = true;
                                    break;
                                }
                            }
                        }

                        // Tentukan URL menu utama
                        $menuUrl = isset($menu['route']) ? route($menu['route']) : $menu['url'] ?? '#';
                    @endphp

                    @if (isset($menu['submenu']))
                        {{-- Dropdown Desktop --}}
                        <div class="relative group hidden md:block">
                            <button
                                class="flex items-center rounded-md px-3 py-1 text-sm transition duration-300
                                    {{ $isActive ? 'bg-lime-400 text-blue-600' : 'text-white hover:bg-[#CCFF00]/50' }}">
                                <span>{{ $menu['label'] }}</span>
                                <svg class="ml-2 h-4 w-4 {{ $isActive ? 'text-blue-600' : 'text-white' }}"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div
                                class="absolute top-full left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20
                                invisible opacity-0 transform scale-95 transition-all duration-200 ease-in-out
                                group-hover:visible group-hover:opacity-100 group-hover:scale-100 dropdown">
                                @foreach ($menu['submenu'] as $submenu)
                                    @php
                                        $submenuUrl = $submenu['url'] ?? '#';
                                        $submenuActive = request()->is(ltrim($submenuUrl, '/'));
                                    @endphp
                                    <a href="{{ $submenu['url'] }}"
                                        class="block px-4 py-2 text-sm  {{ $submenuActive ? 'bg-lime-400 text-blue-600' : ' text-grey-700 hover:bg-[#CCFF00]' }}">
                                        {{ $submenu['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        {{-- Menu Biasa --}}
                        <a href="{{ $menuUrl }}"
                            class="rounded-md px-3 py-1 text-sm font-semibold transition duration-300
                                {{ $isActive ? 'bg-lime-400 text-blue-600' : 'text-white hover:bg-[#CCFF00]/50' }}">
                            {{ $menu['label'] }}
                        </a>
                    @endif
                @endforeach
            </div>

            {{-- Tombol Login / Dashboard --}}
            <div class="hidden md:block">
                @guest
                    <a href="{{ route('login') }}"
                        class="bg-lime-400 text-blue-600 px-6 py-2 rounded-md text-sm font-semibold hover:bg-lime-500 transition-colors">
                        Login
                    </a>
                @else
                    <a href="{{ route('home') }}"
                        class="bg-lime-400 text-blue-600 px-6 py-2 rounded-md text-sm font-semibold hover:bg-lime-500 transition-colors">
                        Dashboard
                    </a>
                @endguest
            </div>

            {{-- Tombol Menu Mobile --}}
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-button" class="p-2 rounded-md text-white hover:bg-white/30 focus:outline-none">
                    <svg id="icon-open" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg id="icon-close" class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Menu Mobile --}}
    <div id="mobile-menu" class="hidden md:hidden bg-[#0044A9]/50">
        <div class="px-4 pt-4 pb-3 space-y-2">
            <a href="{{ url('/') }}"
                class="block rounded-md px-3 py-2 text-base font-semibold
                {{ request()->url() == url('/') ? 'bg-lime-400 text-blue-600' : 'text-white hover:text-blue-600  hover:bg-lime-400' }}">
                Beranda
            </a>

            {{-- Loop Menu Mobile menggunakan Alpine --}}
            @foreach ($menus as $menu)
                @php
                    $isActive = isset($menu['route'])
                        ? Route::currentRouteName() === $menu['route']
                        : request()->url() == ($menu['url'] ?? '');
                    $menuUrl = isset($menu['route']) ? route($menu['route']) : $menu['url'] ?? '#';
                @endphp

                @if (isset($menu['submenu']))
                    <div x-data="{ open: false }" class="md:hidden">
                        <button @click.stop="open = !open"
                            class="w-full flex justify-between items-center px-3 py-2 rounded-md text-white text-base font-medium hover:text-blue-600  hover:bg-lime-400 ">
                            <span>{{ $menu['label'] }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" :class="open ? 'rotate-180 transform' : ''"
                                class="h-5 w-5 transition-transform duration-200" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-2"
                            class="pl-6 space-y-1 bg-white p-3 rounded-lg">
                            @foreach ($menu['submenu'] as $submenu)
                                @php
                                    $submenuUrl = $submenu['url'] ?? '#';
                                    $submenuActive = request()->is(ltrim($submenuUrl, '/'));
                                @endphp
                                <a href="{{ $submenu['url'] }}"
                                    class="block px-3 py-2 text-sm  {{ $submenuActive ? 'bg-lime-400 ' : 'hover:bg-[#CCFF00]' }} rounded-md">
                                    {{ $submenu['label'] }}
                                </a>
                            @endforeach

                        </div>
                    </div>
                @else
                    <a href="{{ $menuUrl }}"
                        class="block rounded-md px-3 py-2 text-base font-medium {{ $isActive ? 'bg-lime-400 text-blue-600' : 'text-white hover:text-blue-600 hover:bg-lime-400' }}">
                        {{ $menu['label'] }}
                    </a>
                @endif
            @endforeach

            {{-- Login / Dashboard --}}
            @guest
                <a href="{{ route('login') }}"
                    class="block w-full bg-lime-400 text-blue-600 px-3 py-2 rounded-md text-base font-semibold hover:text-blue-600  hover:bg-lime-500">
                    Login
                </a>
            @else
                <a href="{{ route('home') }}"
                    class="block w-full bg-lime-400 text-blue-600 px-3 py-2 rounded-md text-base font-semibold hover:bg-lime-500">
                    Dashboard
                </a>
            @endguest
        </div>
    </div>
</nav>
<div id="side-nav" class="hidden md:flex fixed top-1/2 right-4 transform -translate-y-1/2 z-50 items-center">
    <div class="flex flex-col items-center space-y-2 bg-white/20 backdrop-blur-md p-2 rounded-full shadow-lg ml-2">
        <button id="toggle-search-btn"
            class="group relative flex justify-center items-center w-12 h-12 bg-white text-blue-600 rounded-full hover:bg-blue-600 hover:text-white transition-all duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
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
{{-- Script toggle mobile menu --}}
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const btn = document.getElementById("mobile-menu-button");
        const menu = document.getElementById("mobile-menu");
        const iconOpen = document.getElementById("icon-open");
        const iconClose = document.getElementById("icon-close");

        btn.addEventListener("click", () => {
            menu.classList.toggle("hidden");
            iconOpen.classList.toggle("hidden");
            iconClose.classList.toggle("hidden");
        });
    });
</script>
