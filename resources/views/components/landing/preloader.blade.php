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

    <div class="h-16 w-16 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
</div>


{{-- @push('scripts')
    <script>
        window.addEventListener('load', () => {
            setTimeout(() => {
                const preloader = document.getElementById('preloader');
                preloader.classList.add('opacity-0');
                setTimeout(() => preloader.remove(), 300);
            }, 300);
        });
    </script>
@endpush --}}
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const preloader = document.getElementById('preloader');
            let alreadyHidden = false;

            // Paksa hilang setelah 2 detik
            const forceHide = setTimeout(() => {
                if (!alreadyHidden) {
                    preloader.classList.add('opacity-0');
                    setTimeout(() => preloader.remove(), 300);
                    alreadyHidden = true;
                }
            }, 1000);

            // Jika load selesai lebih cepat, ikuti event load
            window.addEventListener('load', () => {
                if (!alreadyHidden) {
                    alreadyHidden = true;
                    clearTimeout(forceHide);

                    setTimeout(() => {
                        preloader.classList.add('opacity-0');
                        setTimeout(() => preloader.remove(), 300);
                    }, 300);
                }
            });
        });
    </script>
@endpush
