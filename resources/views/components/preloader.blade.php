<!-- Preloader -->
<div id="preloader"
    class="fixed inset-0 z-[9999] flex flex-col items-center justify-center
            bg-white/70 dark:bg-gray-900/60 backdrop-blur-md
            transition-opacity duration-500">

    <!-- Logo -->
    <!-- Logo yang berubah otomatis sesuai tema -->
    <img src="{{ asset('img/bapperida_black.png') }}" alt="Logo Bapperida"
        class="w-[200px] h-auto mb-6 drop-shadow-lg animate-fadeIn dark:hidden">

    <img src="{{ asset('img/bapperida_white.png') }}" alt="Logo Bapperida (Dark)"
        class="w-[200px] h-auto mb-6 drop-shadow-lg animate-fadeIn hidden dark:block">

    <!-- Spinner -->
    <div class="h-16 w-16 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
</div>


@push('scripts')
    <script>
        // Check if we should skip the preloader (e.g. coming from form submission)
        (function() {
            const preloader = document.getElementById('preloader');
            const skipPreloader = sessionStorage.getItem('skipPreloader');

            if (skipPreloader === 'true') {
                // Skip preloader immediately
                sessionStorage.removeItem('skipPreloader');
                if (preloader) {
                    preloader.remove();
                }
            } else {
                // Normal preloader behavior - hide when page loads
                window.addEventListener('load', () => {
                    if (preloader) {
                        preloader.classList.add('opacity-0');
                        setTimeout(() => preloader.remove(), 300);
                    }
                });
            }
        })();
    </script>
@endpush
