<x-layout>
    <x-header></x-header>
    <div class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-100 dark:bg-slate-900 p-6">
        <!-- Stat Cards -->
        <div
            class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-2 gap-6 mb-6 border-b dark:border-indigo-600/50 border-gray-300  pb-2">
            <div class="border-r pr-3 dark:border-indigo-600/50 border-gray-300">
                <!-- Light mode -->
                <img src="{{ asset('img/bapperida_black.png') }}" alt="Logo" class="h-16 block dark:hidden">
                <!-- Dark mode -->
                <img src="{{ asset('img/bapperida_white.png') }}" alt="Logo" class="h-16 hidden dark:block">
            </div>
            <div class="p-2 ">
                <h3 class="font-bold text-2xl text-indigo-800 dark:text-white">SELAMAT DATANG</h3>
                <h2 class="text-lg">{{ Auth::user()->name }}</h2>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">

            <x-card.card1 icon="bi-eye-fill" color="purple" value="{{ number_format($pageViewToday) }}"
                title="Total Views Today" change="" />
            <x-card.card1 icon="bi-folder" color="purple" value="{{ number_format($documentCount) }}"
                title="Total Document Publish" change="" />
            <x-card.card1 icon="bi-images" color="purple" value="{{ number_format($galeriCount) }}"
                title="Total Media Publish" change="" />
            <x-card.card1 icon="bi-newspaper" color="purple" value="{{ $beritaCount }}" title="Total News Published"
                change="" />

        </div>
        <h3 class="my-3 font-bold mx-2 text-2xl">Kunjungan Website</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
            <x-card.card1 icon="bi-eye-fill" value="{{ number_format($pageView) }}" title="Total Views"
                change="" />
            <x-card.card1 icon="bi-eye-fill" color="purple" value="{{ number_format($pageViewToday) }}"
                title="Total Views Today" change="" />
            <x-card.card1 icon="bi-eye-fill" color="purple" value="{{ number_format($pageViewUrl) }}"
                title="Total Views URL" change="" />
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-2 gap-6 mb-6">
            <div
                class="w-full bg-white dark:bg-slate-800 p-3 rounded-lg border border-gray-200 dark:border-transparent">
                <h2 class="text-lg font-semibold text-center mb-4">Kunjungan Per Hari (7 Hari Terakhir)</h2>
                <canvas id="dailyChart"></canvas>
            </div>

            <div
                class="w-full  bg-white dark:bg-slate-800 p-3 rounded-lg border border-gray-200 dark:border-transparent">
                <h2 class="text-lg font-semibold text-center mb-4">Kunjungan Per Bulan (6 Bulan Terakhir)</h2>
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>
        <h3 class="my-3 font-bold mx-2 text-2xl">Berita</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
            <x-card.card1 icon="bi-newspaper" color="purple" value="{{ $beritaCount }}" title="Total News Published"
                change="" />
            <x-card.card1 icon="bi-newspaper" color="yellow" value="{{ $beritaDraftCount }}" title="Total News Draft"
                change="" />
            <x-card.card1 icon="bi-newspaper" color="purple" value="{{ $beritaCountView }}" title="Total News Visites "
                change="" />
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const dailyLabels = @json($dailyViews->pluck('date'));
            const dailyData = @json($dailyViews->pluck('total'));

            const monthlyLabels = @json($monthlyViews->pluck('month'));
            const monthlyData = @json($monthlyViews->pluck('total'));
            new Chart(document.getElementById('dailyChart'), {
                type: 'line',
                data: {
                    labels: dailyLabels,
                    datasets: [{
                        label: 'Kunjungan',
                        data: dailyData,
                        borderColor: '#4338CA',
                        backgroundColor: 'rgba(59,130,246,0.2)',
                        tension: 0.3,
                        fill: true,
                        borderWidth: 2
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            new Chart(document.getElementById('monthlyChart'), {
                type: 'bar',
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                        label: 'Kunjungan',
                        data: monthlyData,
                        backgroundColor: '#4338CA',
                        borderRadius: 6
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        </script>
    @endpush
</x-layout>
