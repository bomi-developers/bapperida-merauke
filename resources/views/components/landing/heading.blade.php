 <section class="relative top-0 overflow-hidden h-[1200px] [clip-path:ellipse(120%_60%_at_50%_0%)]">
     <div class="absolute top-0 left-0 w-full h-full bg-cover bg-center"
         style="background-image: url('/assets/image6.jpg');">
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
                 'description' =>
                     'Dapatkan informasi terbaru mengenai kegiatan, program, dan inovasi dari BAPPERIDA Kabupaten Merauke.',
             ],
             [
                 'routes' => ['about.struktur-organisasi'],
                 'title' => 'Struktur Organisasi',
                 'subtitle' => 'Struktur Organisasi BAPPERIDA',
                 'description' =>
                     'Kenali struktur organisasi BAPPERIDA Kabupaten Merauke untuk memahami peran dan tanggung jawab setiap unit kerja dalam mendukung pembangunan daerah.',
             ],
             [
                 'routes' => ['about.pegawai'],
                 'title' => 'Daftar Pegawai',
                 'subtitle' => 'Daftar Pegawai BAPPERIDA',
                 'description' =>
                     'Temui tim profesional di balik BAPPERIDA Kabupaten Merauke yang berdedikasi untuk perencanaan dan pembangunan daerah.',
             ],
             [
                 'routes' => ['about.sejarah'],
                 'title' => 'Sejarah',
                 'subtitle' => 'Sejarah BAPPERIDA',
                 'description' =>
                     'Pelajari perjalanan sejarah BAPPERIDA Kabupaten Merauke dalam mendukung pembangunan daerah dari masa ke masa.',
             ],
             [
                 'routes' => ['about.tugas-fungsi'],
                 'title' => 'Tugas dan Fungsi',
                 'subtitle' => 'Tugas dan Fungsi BAPPERIDA',
                 'description' =>
                     'Pahami tugas dan fungsi utama BAPPERIDA Kabupaten Merauke dalam perencanaan pembangunan daerah yang berkelanjutan.',
             ],
             [
                 'routes' => ['about.visi-misi'],
                 'title' => 'Visi dan Misi',
                 'subtitle' => 'Visi dan Misi BAPPERIDA',
                 'description' =>
                     'Jelajahi visi dan misi BAPPERIDA Kabupaten Merauke dalam mewujudkan pembangunan daerah yang inklusif dan berkelanjutan.',
             ],
             [
                 'routes' => ['riset-inovasi.riset'],
                 'title' => 'Publikasi Riset',
                 'subtitle' => 'Data Publikasi Riset oleh BAPPERIDA',
                 'description' =>
                     'Temukan berbagai publikasi riset yang dihasilkan oleh BAPPERIDA Kabupaten Merauke untuk mendukung pembangunan daerah.',
             ],
             [
                 'routes' => ['riset-inovasi.inovasi'],
                 'title' => 'Pengajuan Inovasi',
                 'subtitle' => 'Pengajuan Proposal Inovasi ke BAPPERIDA',
                 'description' =>
                     'Ajukan proposal inovasi Anda kepada BAPPERIDA Kabupaten Merauke untuk mendukung pembangunan daerah yang lebih baik.',
             ],
             [
                 'routes' => ['riset-inovasi.data'],
                 'title' => 'Data Riset dan Inovasi',
                 'subtitle' => 'Data Riset dan Inovasi oleh BAPPERIDA',
                 'description' =>
                     'Akses berbagai data riset dan inovasi yang dikumpulkan oleh BAPPERIDA Kabupaten Merauke untuk mendukung pembangunan daerah.',
             ],
             [
                 'routes' => ['riset-inovasi.kekayaan-intelektual'],
                 'title' => 'Kekayaan Intelektual',
                 'subtitle' => 'Kekayaan Intelektual oleh BAPPERIDA',
                 'description' =>
                     'Jelajahi berbagai kekayaan intelektual yang dimiliki oleh BAPPERIDA Kabupaten Merauke dalam mendukung pembangunan daerah.',
             ],
             [
                 'routes' => ['galeri.public.index', 'galeri.public.show'],
                 'title' => 'Galeri',
                 'subtitle' => 'Galeri oleh BAPPERIDA',
                 'description' =>
                     'Jelajahi berbagai koleksi galeri yang dimiliki oleh BAPPERIDA Kabupaten Merauke dalam mendukung pembangunan daerah.',
             ],
         ];

         $currentRoute = Route::currentRouteName();
         $hero = collect($heroGroups)->first(fn($group) => in_array($currentRoute, $group['routes'])) ?? [
             'title' => 'Selamat Datang',
             'subtitle' => 'Badan Perencanaan Daerah Kabupaten Merauke',
             'description' =>
                 'Bersama BAPPERIDA Kabupaten Merauke, wujudkan pembangunan berkelanjutan, inklusif, dan berbasis potensi lokal.',
         ];
         if ($currentRoute === 'documents.by_category') {
             // Ambil model Kategori langsung dari parameter rute
             $kategoriModel = Route::current()->parameter('kategori');
             if ($kategoriModel) {
                 $hero = [
                     'title' => 'Dokumen',
                     'subtitle' => $kategoriModel->nama_kategori,
                     'description' =>
                         'Temukan semua dokumen terkait kategori ' . $kategoriModel->nama_kategori . ' di sini.',
                     'is_split_title' => true, // Flag untuk gaya judul terpisah
                 ];
             }
         } else {
             // Jika bukan halaman kategori, cari di grup rute biasa
             $hero = collect($heroGroups)->first(fn($group) => in_array($currentRoute, $group['routes']));
         }

         // Fallback jika tidak ada yang cocok
         if (!$hero) {
             $hero = [
                 'title' => 'Selamat Datang',
                 'subtitle' => 'Website Resmi Bapperida Merauke',
                 'description' =>
                     'Bersama BAPPERIDA Kabupaten Merauke, wujudkan pembangunan berkelanjutan, inklusif, dan berbasis potensi lokal.',
             ];
         }
     @endphp

     <div class="relative z-10 h-full flex flex-col items-center justify-start text-center px-4 animate-fade-in-up"
         style="padding-top: 15rem;">
         <h1 class="text-white text-4xl md:text-5xl font-extrabold max-w-4xl leading-tight">
             {{ $hero['title'] }}
         </h1>
         <h2 class="text-white text-2xl md:text-2xl font-extrabold max-w-4xl leading-tight">
             {{ $hero['subtitle'] }}
         </h2>
         <p class="mt-4 text-[#CCFF00] text-lg max-w-2xl">
             {{ $hero['description'] ?? 'Bersama BAPPERIDA Kabupaten Merauke, wujudkan pembangunan berkelanjutan, inklusif, dan berbasis potensi lokal.' }}
         </p>

         {{-- <form class="mt-8 w-full max-w-lg">
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
         </form> --}}
     </div>
 </section>
