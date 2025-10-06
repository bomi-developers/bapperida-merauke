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
                    ],
                ],
                [
                    'title' => 'Settings',
                    'items' => [
                        ['label' => 'Akun Setting', 'icon' => 'bi-person-badge', 'route' => 'profile'],
                        ['label' => 'Profile Dinas', 'icon' => 'bi-info-circle-fill', 'route' => 'website-setting'],
                    ],
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
    @stack('scripts')
    {{-- script components layout --}}
    <x-script></x-script>

</body>

</html>
