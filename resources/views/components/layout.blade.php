<!DOCTYPE html>
<html lang="en">

<x-head></x-head>

<body class="bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300">
    <!-- ===== Preloader Start ===== -->
    <x-preloader></x-preloader>

    <!-- ===== Preloader End ===== -->

    <!-- ===== Page Wrapper Start ===== -->
    <div class="flex h-screen bg-slate-100 dark:bg-slate-900">
        <!-- ===== Sidebar Start ===== -->
        @php
            if (Auth::user()->role == 'super_admin') {
                $menus = [
                    [
                        'title' => 'Menu',
                        'items' => [['label' => 'Dashboard', 'icon' => 'bi-grid-1x2-fill', 'route' => 'home']],
                    ],
                    [
                        'title' => 'Pegawai',
                        'items' => [
                            ['label' => 'Bidang', 'icon' => 'bi-building', 'route' => 'admin.bidang.index'],
                            ['label' => 'Golongan', 'icon' => 'bi-bar-chart-steps', 'route' => 'admin.golongan'],
                            ['label' => 'Jabatan', 'icon' => 'bi-person-badge', 'route' => 'admin.jabatan'],
                            ['label' => 'Data Pegawai', 'icon' => 'bi-people-fill', 'route' => 'admin.pegawai'],
                            ['label' => 'Data PD', 'icon' => 'bi-people-fill', 'route' => 'admin.opd'],
                        ],
                    ],
                    [
                        'title' => 'Information',
                        'items' => [
                            ['label' => 'Berita', 'icon' => 'bi bi-person-lines-fill', 'route' => 'admin.berita.index'],
                            ['label' => 'Galeri', 'icon' => 'bi bi-image', 'route' => 'admin.galeri.index'],
                        ],
                    ],
                    [
                        'title' => 'Document Management',
                        'items' => [
                            [
                                'label' => 'Kategori Dokumen',
                                'icon' => 'bi bi-folder',
                                'route' => 'admin.doctkategori.index',
                            ],
                            ['label' => 'Dokumen', 'icon' => 'bi bi-file-earmark', 'route' => 'admin.documents.index'],
                        ],
                    ],
                    [
                        'title' => 'E-Reporting',
                        'items' => [
                            [
                                'label' => 'Laporan Evaluasi',
                                'icon' => 'bi bi-calendar-check',
                                'route' => 'triwulan.index',
                            ],
                            [
                                'label' => 'Renja',
                                'icon' => 'bi bi-calendar-check',
                                'route' => 'renja.index',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Settings',
                        'items' => [
                            ['label' => 'Landing Setting', 'icon' => 'bi-gear', 'route' => 'admin.lending.index'],
                            ['label' => 'Akun Setting', 'icon' => 'bi-person-badge', 'route' => 'profile'],
                            ['label' => 'Profile Dinas', 'icon' => 'bi-info-circle-fill', 'route' => 'website-setting'],
                        ],
                    ],
                    [
                        'title' => 'Administrator',
                        'items' => [['label' => 'Super Admin', 'icon' => 'bi-people', 'route' => 'admin.user.admin']],
                    ],

                    [
                        'title' => 'Tracker System',
                        'items' => [
                            ['label' => 'Log Login', 'icon' => 'bi-box-arrow-in-right', 'route' => 'admin.login-logs'],
                            ['label' => 'Log Aktivitas', 'icon' => 'bi-activity', 'route' => 'admin.activity-logs'],
                            ['label' => 'Log Tampilan', 'icon' => 'bi-eye-fill', 'route' => 'admin.view-logs'],
                        ],
                    ],
                ];
            } elseif (Auth::user()->role == 'pegawai') {
                $menus = [
                    [
                        'title' => 'Menu',
                        'items' => [['label' => 'Dashboard', 'icon' => 'bi-grid-1x2-fill', 'route' => 'home']],
                    ],

                    [
                        'title' => 'Information',
                        'items' => [
                            ['label' => 'Berita', 'icon' => 'bi bi-person-lines-fill', 'route' => 'admin.berita.index'],
                            ['label' => 'Galeri', 'icon' => 'bi bi-image', 'route' => 'admin.galeri.index'],
                        ],
                    ],
                    [
                        'title' => 'Document Management',
                        'items' => [
                            ['label' => 'Dokumen', 'icon' => 'bi bi-file-earmark', 'route' => 'admin.documents.index'],
                        ],
                    ],
                ];
            } elseif (Auth::user()->role == 'admin' || Auth::user()->role == 'opd') {
                $labelRenja = Auth::user()->role == 'opd' ? 'Renja' : 'RKPD';
                $menus[] = [
                    'title' => 'E-Reporting',
                    'items' => [
                        [
                            'label' => 'Laporan Evaluasi',
                            'icon' => 'bi bi-calendar-check',
                            'route' => 'triwulan.index',
                        ],
                        [
                            'label' => $labelRenja,
                            'icon' => 'bi bi-journal-text',
                            'route' => 'renja.index',
                        ],
                    ],
                ];
            }

        @endphp

        <x-sidebar :menus="$menus" />
        <!-- ===== Sidebar End ===== -->

        <!-- ===== Content Area Start ===== -->
        <div class="flex-1 flex flex-col overflow-hidden">
            {{ $slot }}
        </div>
        <!-- ===== Content Area End ===== -->
    </div>
    {{-- modal konfirmasi --}}
    <div id="confirmModal"
        class="fixed inset-0 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white dark:bg-boxdark rounded-lg shadow-lg p-6 w-96 text-center">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Konfirmasi</h2>
            <p class="text-gray-600 dark:text-gray-300 mb-5">
                Apakah kamu yakin ingin menghapus data ini?
            </p>
            <div class="flex justify-center gap-3">
                <button id="cancelDelete"
                    class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-white rounded hover:bg-gray-400">
                    Batal
                </button>
                <button id="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Hapus
                </button>
            </div>
        </div>
    </div>
    {{-- toast --}}
    <x-toast></x-toast>
    <x-password></x-password>
    {{-- script components layout --}}
    <x-script></x-script>

    @stack('scripts')
</body>

</html>
