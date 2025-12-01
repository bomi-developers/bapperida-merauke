<nav id="main-nav" class="fixed top-0 left-0 right-0 z-50">
    <div
        class="w-full mx-auto px-4 sm:px-6 lg:px-8 hidden md:block bg-[#0044A9]/50 border-b border-white/20 backdrop-blur-sm">
        <div class="flex items-center justify-between py-3 max-w-7xl mx-auto ">
            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center space-x-3">
                <img class="h-12 w-auto" src="{{ asset('assets/LogoKabMerauke.png') }}" alt="Logo BAPPERIDA"
                    loading="lazy" />
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
        </div>
    </div>


    <!-- Floating Mobile Navbar -->
    <div
        class="fixed md:hidden top-4 left-1/2 -translate-x-1/2 z-80 w-[95%] max-w-xl
    bg-blue-900/50 backdrop-blur-sm border border-white/20 rounded-full shadow-lg flex justify-between items-center px-4 py-2">

        <!-- Logo -->
        <a href="{{ url('/') }}" class="flex items-center gap-2">
            <img src="{{ asset('assets/LogoKabMerauke.png') }}" alt="Logo" class="h-8 w-8 " loading="lazy">
            <div class="flex flex-col">
                <span class="text-white text-xl font-bold leading-tight">BAPPERIDA</span>
                <span class="text-white text-xs font-light leading-tight">Kab. Merauke</span>
            </div>
        </a>

        <!-- Tombol Menu (Hamburger) -->
        <div class="flex space-x-3">
            <button {{-- id="toggle-search-btn" --}}
                class=" toggle-search-btn text-white text-2xl focus:outline-none transition-transform duration-300">
                <i class="bi bi-search"></i>
            </button>

            <button id="menu-toggle" class="text-white text-3xl focus:outline-none transition-transform duration-300">
                <i class="bi bi-list"></i>
            </button>
        </div>
    </div>

    <!-- Dropdown Menu -->
    <div id="mobile-menu"
        class="hidden md:hidden fixed top-20 left-1/2 -translate-x-1/2 w-[90%] max-w-md bg-blue-800/60 backdrop-blur-md rounded-2xl shadow-xl border border-white/20 overflow-hidden transition-all duration-300 ">
        <div class="p-4 space-y-2">
            <a href="{{ url('/') }}"
                class="block rounded-md px-3 py-2 text-base font-semibold {{ request()->url() == url('/') ? 'bg-lime-400 text-blue-600' : 'text-white hover:text-blue-600 hover:bg-lime-400' }}">
                Beranda
            </a>

            @foreach ($menus as $menu)
                @php
                    $isActive = isset($menu['route'])
                        ? Route::currentRouteName() === $menu['route']
                        : request()->url() == ($menu['url'] ?? '');
                    $menuUrl = isset($menu['route']) ? route($menu['route']) : $menu['url'] ?? '#';
                @endphp

                @if (isset($menu['submenu']))
                    <div x-data="{ open: false }">
                        <button @click="open = !open"
                            class="w-full flex justify-between items-center px-3 py-2 rounded-md text-white text-base font-medium hover:text-blue-600 hover:bg-lime-400">
                            <span>{{ $menu['label'] }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" :class="open ? 'rotate-180 transform' : ''"
                                class="h-5 w-5 transition-transform duration-200" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" x-transition class="pl-6 space-y-1 bg-white p-3 rounded-lg">
                            @foreach ($menu['submenu'] as $submenu)
                                <a href="{{ $submenu['url'] }}"
                                    class="block px-3 py-2 text-sm rounded-md hover:bg-lime-300/80">
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

            @guest
                <a href="{{ route('login') }}"
                    class="block w-full bg-lime-400 text-blue-600 px-3 py-2 rounded-md text-base font-semibold hover:bg-lime-500">
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
@push('scripts')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
