<!DOCTYPE html>
<html lang="en">
{{-- head --}}
<x-landing.head></x-landing.head>

<body>
    {{-- navbar --}}
    {{-- <x-landing.navbar></x-landing.navbar> --}}
    @php
        $menus = [
            ['label' => 'Beranda', 'url' => url('/')],
            [
                'label' => 'Tentang',
                'submenu' => [
                    ['label' => 'Visi & Misi', 'url' => '#'],
                    ['label' => 'Struktur Organisasi', 'url' => '#'],
                    ['label' => 'Sejarah Bapperida', 'url' => '#'],
                    ['label' => 'Tugas & Fungsi', 'url' => '#'],
                    ['label' => 'Profil Pegawai', 'url' => '#'],
                ],
            ],
            [
                'label' => 'Produk',
                'submenu' => [
                    ['label' => 'RKPD n-1', 'url' => '#'],
                    ['label' => 'RPJMD', 'url' => '#'],
                    ['label' => 'RPJPD', 'url' => '#'],
                    ['label' => 'LKPJ', 'url' => '#'],
                    ['label' => 'Dokumen Lainnya', 'url' => '#'],
                ],
            ],
            [
                'label' => 'PPM',
                'submenu' => [['label' => 'Pembangunan', 'url' => '#'], ['label' => 'Pemerintahan', 'url' => '#']],
            ],
            [
                'label' => 'Riset & Inovasi',
                'submenu' => [['label' => 'Riset', 'url' => '#'], ['label' => 'Inovasi', 'url' => '#']],
            ],
            [
                'label' => 'Eko-Fispor',
                'submenu' => [
                    ['label' => 'DAK', 'url' => '#'],
                    ['label' => 'Ekonomi', 'url' => '#'],
                    ['label' => 'Infrastruktur', 'url' => '#'],
                ],
            ],
            ['label' => 'Galeri', 'url' => '#'],
        ];
    @endphp
    <x-landing.navbar :menus="$menus" />
    {{-- hading for title --}}
    <x-landing.heading></x-landing.heading>
    {{ $slot }}
    {{-- footer page --}}
    <x-landing.footer></x-landing.footer>
    {{-- more script --}}
    <x-landing.script></x-landing.script>
</body>

</html>
