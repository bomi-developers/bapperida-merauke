@props(['menus' => []])

<aside id="sidebar"
    class="w-64 -translate-x-full fixed lg:static lg:translate-x-0 h-full bg-slate-800 text-slate-300 
           transition-transform duration-300 ease-in-out z-30">
    <div class="flex flex-col h-full">
        <!-- Header -->
        <div class="flex items-center justify-between p-6">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/bapperida_white.png') }}" alt="Logo" class="h-8">
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-4 space-y-4 overflow-y-auto no-scrollbar">
            @foreach ($menus as $section)
                <div>
                    <h3 class="px-4 mb-2 text-xs font-semibold tracking-wider text-slate-400 uppercase">
                        {{ $section['title'] }}
                    </h3>
                    <ul class="space-y-1">
                        @foreach ($section['items'] as $item)
                            <li>
                                <a href="{{ $item['route'] && Route::has($item['route']) ? route($item['route']) : '#' }}"
                                    class="flex items-center gap-3 px-4 py-2 rounded-lg transition-colors
                                        {{ $item['route'] && request()->routeIs($item['route'])
                                            ? 'bg-slate-700 text-white font-semibold'
                                            : 'hover:bg-slate-700 text-slate-300 dark:hover:text-white' }}">
                                    <i class="bi {{ $item['icon'] }}"></i>
                                    <span>{{ $item['label'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </nav>
    </div>
</aside>
