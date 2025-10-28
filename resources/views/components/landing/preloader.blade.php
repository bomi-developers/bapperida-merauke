<!-- Preloader -->
<div id="preloader"
    class="fixed inset-0 z-[9999] flex flex-col items-center justify-center
             dark:bg-gray-900/40 backdrop-blur-md
            transition-opacity duration-500">

    <!-- Logo -->
    <!-- Logo yang berubah otomatis sesuai tema -->
    <img src="{{ asset('img/bapperida_black.png') }}" alt="Logo Bapperida" loading="lazy"
        class="w-[200px] h-auto mb-6 drop-shadow-lg animate-fadeIn dark:hidden">

    <img src="{{ asset('img/bapperida_white.png') }}" alt="Logo Bapperida (Dark)" loading="lazy"
        class="w-[200px] h-auto mb-6 drop-shadow-lg animate-fadeIn hidden dark:block">

    <!-- Spinner -->
    <div class="h-16 w-16 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
</div>


@push('scripts')
    <script>
        // Tutup preloader saat halaman selesai dimuat
        window.addEventListener('load', () => {
            const preloader = document.getElementById('preloader');
            preloader.classList.add('opacity-0');
            setTimeout(() => preloader.remove(), 300); // hapus setelah animasi fade-out
        });
    </script>
@endpush
