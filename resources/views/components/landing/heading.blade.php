 <section class="relative h-[1200px] [clip-path:ellipse(120%_60%_at_50%_0%)]">
     <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('/Assets/image6.jpg');">
     </div>

     <div class="absolute inset-0 bg-blue-800/50"></div>

     @php
         $heroGroups = [
             [
                 'routes' => ['welcome'],
                 'title' => 'Badan Perencanaan',
                 'subtitle' => 'Pembangunan, Riset dan Inovasi Daerah Kabupaten Merauke',
             ],
             [
                 'routes' => ['berita.public.home', 'berita.public.show'],
                 'title' => 'Berita dan Informasi',
                 'subtitle' => 'Update Terkini Seputar Kegiatan BAPPERIDA',
             ],
         ];

         $currentRoute = Route::currentRouteName();
         $hero = collect($heroGroups)->first(fn($group) => in_array($currentRoute, $group['routes'])) ?? [
             'title' => 'Selamat Datang',
             'subtitle' => 'Badan Perencanaan Daerah Kabupaten Merauke',
         ];
     @endphp

     <div class="relative z-10 h-full flex flex-col items-center justify-start text-center px-4 animate-fade-in-up"
         style="padding-top: 15rem;">
         <h1 class="text-white text-4xl md:text-5xl font-extrabold max-w-4xl leading-tight">
             {{ $hero['title'] }}
             <span class="block mt-2">{{ $hero['subtitle'] }}</span>
         </h1>
         <p class="mt-4 text-[#CCFF00] text-lg max-w-2xl">
             Bersama BAPPERIDA Kabupaten Merauke, wujudkan pembangunan berkelanjutan, inklusif, dan berbasis potensi
             lokal.
         </p>

         <form class="mt-8 w-full max-w-lg">
             <div class="flex items-center bg-white rounded-full p-2 shadow-md">
                 <svg class="h-6 w-6 text-gray-400 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                 </svg>
                 <input type="text" placeholder="Cari inovasi, produk, atau berita..."
                     class="w-full bg-transparent px-4 text-gray-700 focus:outline-none" />
                 <button type="submit"
                     class="bg-[#CCFF00] text-[#006FFF] px-6 py-2 rounded-full text-sm font-semibold hover:bg-yellow-400 transition-colors">
                     Cari
                 </button>
             </div>
         </form>
     </div>
 </section>
