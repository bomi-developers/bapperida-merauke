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
        <x-sidebar></x-sidebar>
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
    <!-- Toast Notification -->
    <div id="toast"
        class="fixed top-16 right-2 hidden py-6 px-4 rounded-md shadow-md text-white text-sm font-medium 
           transition-all duration-700 z-[100] w-72 opacity-0 translate-y-2 flex flex-col space-y-2">
        <div class="flex justify-between items-center">
            <span id="toast-icon" class="text-xl"></span>
            <span id="toast-message" class="pt-1"></span>
        </div>
        <div id="toast-progress" class="h-1 bg-white/70 rounded-full w-full scale-x-0 origin-left"></div>
    </div>
    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Element selectors
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebar = document.getElementById('sidebar');
            const profileButton = document.getElementById('profile-button');
            const profileDropdown = document.getElementById('profile-dropdown');
            const notificationButton = document.getElementById('notification-button');
            const notificationDropdown = document.getElementById('notification-dropdown');
            const messageButton = document.getElementById('message-button');
            const messageDropdown = document.getElementById('message-dropdown');

            // --- Dark Mode Logic ---
            const themeToggleBtn = document.getElementById('theme-toggle');
            const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

            // Function to set the correct icon based on the current theme
            const updateThemeIcon = () => {
                if (document.documentElement.classList.contains('dark')) {
                    themeToggleDarkIcon.classList.add('hidden');
                    themeToggleLightIcon.classList.remove('hidden');
                } else {
                    themeToggleDarkIcon.classList.remove('hidden');
                    themeToggleLightIcon.classList.add('hidden');
                }
            };

            // Set initial icon state on page load
            updateThemeIcon();

            themeToggleBtn.addEventListener('click', function() {
                // Toggle the 'dark' class on the <html> element
                document.documentElement.classList.toggle('dark');

                // Update localStorage based on the new state
                if (document.documentElement.classList.contains('dark')) {
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    localStorage.setItem('color-theme', 'light');
                }

                // Update the icon to reflect the new theme
                updateThemeIcon();
            });
            // --- End Dark Mode Logic ---

            // --- Dropdown and Sidebar Logic ---
            function toggleDropdown(dropdown) {
                const allDropdowns = [profileDropdown, notificationDropdown, messageDropdown];
                allDropdowns.forEach(d => {
                    if (d !== dropdown) {
                        d.classList.add('hidden');
                    }
                });
                dropdown.classList.toggle('hidden');
            }

            sidebarToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                sidebar.classList.toggle('-translate-x-full');
            });
            profileButton.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleDropdown(profileDropdown);
            });
            notificationButton.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleDropdown(notificationDropdown);
            });
            messageButton.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleDropdown(messageDropdown);
            });

            document.addEventListener('click', function(e) {
                if (window.innerWidth < 1024 && !sidebar.contains(e.target) && !sidebarToggle.contains(e
                        .target)) {
                    sidebar.classList.add('-translate-x-full');
                }

                const isClickInsideProfile = profileButton.contains(e.target) || profileDropdown.contains(e
                    .target);
                const isClickInsideNotification = notificationButton.contains(e.target) ||
                    notificationDropdown.contains(e.target);
                const isClickInsideMessage = messageButton.contains(e.target) || messageDropdown.contains(e
                    .target);

                if (!isClickInsideProfile) profileDropdown.classList.add('hidden');
                if (!isClickInsideNotification) notificationDropdown.classList.add('hidden');
                if (!isClickInsideMessage) messageDropdown.classList.add('hidden');
            });
        });
        // toast helper
        let toastTimeout;

        function showToast(message, success = true, duration = 4000) {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            const toastIcon = document.getElementById('toast-icon');
            const progress = document.getElementById('toast-progress');

            clearTimeout(toastTimeout);

            toastMessage.textContent = message;

            // 🔹 Pilih ikon dan warna background
            const bgColor = success ? 'bg-green-600/85 backdrop-blur-sm' : 'bg-red-600/85 backdrop-blur-sm';
            const icon = success ? ' <i class="bi bi-check-circle"></i>' :
                '<i class="bi bi-x-circle"></i>';

            toastIcon.innerHTML = icon;

            // 🔹 Set style dasar
            toast.className = `
            fixed top-16 right-2 py-6 px-4 rounded-md shadow-md text-white text-sm font-medium
            ${bgColor}
            transition-all duration-700 z-[100] w-72 opacity-0 translate-y-2 flex flex-col space-y-2
        `;

            toast.classList.remove('hidden');
            requestAnimationFrame(() => {
                toast.classList.add('animate-fadeIn');
            });

            // 🔹 Reset animasi progress bar
            progress.classList.remove('animate-progress');
            progress.style.animation = 'none';
            progress.offsetHeight; // reflow
            progress.style.setProperty('--toast-duration', `${duration / 5000}s`);
            progress.style.animation = null;
            progress.classList.add('animate-progress');

            // 🔹 Auto hide
            toastTimeout = setTimeout(() => {
                toast.classList.remove('animate-fadeIn');
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => {
                    toast.classList.add('hidden');
                    // progress.classList.remove('animate-progress');
                    progress.style.animation = 'none';
                }, 1000);
            }, duration);
        }
    </script>
</body>

</html>
