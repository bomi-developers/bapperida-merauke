<!DOCTYPE html>
<html lang="en">
{{-- head --}}
<x-landing.head></x-landing.head>

<body class="bg-[#004299]">
    <div class="bg-white">
        {{-- navbar --}}
        {{-- <x-landing.navbar></x-landing.navbar> --}}
        <x-landing.preloader></x-landing.preloader>
        @php
            $kategoriDocuments = \App\Models\KategoriDocument::orderBy('id', 'asc')->get();
            $productMenus = [];

            if ($kategoriDocuments->isNotEmpty()) {
                foreach ($kategoriDocuments as $kategori) {
                    $productMenus[] = [
                        'label' => $kategori->nama_kategori,
                        'url' => route('documents.by_category', [
                            'kategori' => $kategori->id,
                            'slug' => Str::slug($kategori->nama_kategori),
                        ]),
                    ];
                }
            } else {
                // Jika kosong, bisa beri placeholder atau biarkan array kosong
                $productMenus[] = [
                    'label' => 'Tidak ada kategori',
                    'url' => '#',
                ];
            }

            $menus = [
                [
                    'label' => 'Tentang',
                    'submenu' => [
                        ['label' => 'Visi & Misi', 'url' => '/about/visi-misi'],
                        ['label' => 'Struktur Organisasi', 'url' => '/about/struktur-organisasi'],
                        ['label' => 'Sejarah Bapperida', 'url' => '/about/sejarah'],
                        ['label' => 'Tugas & Fungsi', 'url' => '/about/tugas-fungsi'],
                        ['label' => 'Profil Pegawai', 'url' => '/about/pegawai'],
                    ],
                ],
                [
                    'label' => 'Produk',
                    'submenu' => $productMenus,
                ],
                // [
                //     'label' => 'PPM',
                //     'submenu' => [['label' => 'Pembangunan', 'url' => '#'], ['label' => 'Pemerintahan', 'url' => '#']],
                // ],
                [
                    'label' => 'Riset & Inovasi',
                    'submenu' => [
                        ['label' => 'Riset', 'url' => '/riset-inovasi/riset'],
                        ['label' => 'Inovasi', 'url' => '/riset-inovasi/inovasi'],
                        ['label' => 'Data', 'url' => '/riset-inovasi/data'],
                        ['label' => 'Kekayaan Intelektual', 'url' => '/riset-inovasi/kekayaan-intelektual'],
                    ],
                ],
                // [
                //     'label' => 'Eko-Fispor',
                //     'submenu' => [
                //         ['label' => 'DAK', 'url' => '#'],
                //         ['label' => 'Ekonomi', 'url' => '#'],
                //         ['label' => 'Infrastruktur', 'url' => '#'],
                //     ],
                // ],
                ['label' => 'Galeri', 'url' => '#'],
                ['label' => 'Berita', 'route' => 'berita.public.home'],
            ];
        @endphp
        <x-landing.navbar :menus="$menus" />
        {{-- hading for title --}}
        <x-landing.heading></x-landing.heading>
        {{-- <div class="bg-gradient-to-b from-blue-100 to-white"> --}}
        {{ $slot }}
        {{-- </div> --}}
        {{-- footer page --}}
        <x-landing.footer></x-landing.footer>
        {{-- more script --}}
        <x-landing.script></x-landing.script>
    </div>
</body>

</html>
