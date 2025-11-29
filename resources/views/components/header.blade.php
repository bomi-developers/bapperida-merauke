<!-- Header -->
@php
    switch (Auth::user()->role) {
        case 'super_admin':
            $roleUser = 'Super Admin';
            break;
        case 'opd':
            $roleUser = 'OPD';
            break;
        case 'admin':
            $roleUser = 'Admin';
            break;
        default:
            $roleUser = 'Pegawai';
            break;
    }
@endphp
<header class="bg-white dark:bg-slate-800 dark:border-slate-700">
    <div class="flex items-center justify-between p-4">
        <div class="flex items-center gap-4 w-full">
            <button id="sidebar-toggle"
                class="lg:hidden text-slate-500 dark:text-slate-400 hover:text-indigo-500 dark:hover:text-white">
                <i class="bi bi-list text-2xl"></i>
            </button>
            <div class="relative w-full">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" placeholder="klik [ / ] cari menu.." id="searchInput"
                    class="bg-slate-100 dark:bg-slate-700 dark:border-slate-600 rounded-lg w-full pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-gray-800 dark:text-white transition-all duration-300">
            </div>
        </div>
        <div class="flex items-center gap-4">
            <!-- Dark Mode Toggle -->
            <button id="theme-toggle"
                class="w-10 h-10 flex items-center justify-center text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg">
                <i id="theme-toggle-dark-icon" class="bi bi-moon-fill hidden"></i>
                <i id="theme-toggle-light-icon" class="bi bi-sun-fill hidden"></i>
            </button>

            <!-- Notification Dropdown -->
            <div class="relative">
                <button id="notification-button"
                    class="w-10 h-10 flex items-center justify-center relative text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg">
                    <span class="absolute -top-0.5 -right-0.5 h-2.5 w-2.5 rounded-full bg-red-500 animate-ping"></span>
                    <span class="absolute -top-0.5 -right-0.5 h-2.5 w-2.5 rounded-full bg-red-500"></span>
                    <i class="bi bi-bell-fill text-lg"></i>
                </button>
                <div id="notification-dropdown"
                    class="absolute right-0 mt-2 w-80 bg-white dark:bg-slate-700 rounded-lg shadow-xl hidden z-20 border border-gray-200 dark:border-slate-600">
                    <div class="p-3 border-b border-gray-200 dark:border-slate-600">
                        <h5 class="text-sm font-medium text-gray-500 dark:text-slate-400">Notifikasi
                            <span id="count-notifikasi" class="bg-red-500 text-white rounded-full px-2 py-1">
                                0
                            </span>
                        </h5>
                    </div>
                    <ul id="list-notifikasi-navbar" class="flex flex-col max-h-80 overflow-y-auto no-scrollbar">

                    </ul>
                    <div class="p-3 border-b border-gray-200 dark:border-slate-600 text-center">
                        <button type="button" id="btn-notifikasi"
                            class="text-sm font-medium text-gray-500 dark:text-slate-400 ">
                            Semua Notifikasi
                        </button>
                    </div>
                </div>
            </div>

            <!-- Message Dropdown -->
            <div class="relative">
                <button id="message-button"
                    class="w-10 h-10 flex items-center justify-center relative text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg">
                    <span class="absolute -top-0.5 -right-0.5 h-2.5 w-2.5 rounded-full bg-red-500"></span>
                    <i class="bi bi-chat-dots-fill text-lg"></i>
                </button>
                <div id="message-dropdown"
                    class="absolute right-0 mt-2 w-80 bg-white dark:bg-slate-700 rounded-lg shadow-xl hidden z-20 border border-gray-200 dark:border-slate-600">
                    <div class="p-3 border-b border-gray-200 dark:border-slate-600">
                        <h5 class="text-sm font-medium text-gray-500 dark:text-slate-400">Messages</h5>
                    </div>
                    <ul class="flex flex-col max-h-80 overflow-y-auto no-scrollbar">
                        <li><a href="#"
                                class="flex gap-4 p-3 border-b border-gray-200 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-600"><img
                                    src="https://placehold.co/48x48/16a34a/ffffff?text=MD" alt="User"
                                    class="w-12 h-12 rounded-full">
                                <div class="text-left">
                                    <h6 class="text-sm font-medium text-gray-800 dark:text-white">Mariya
                                        Desoja</h6>
                                    <p class="text-sm text-gray-600 dark:text-slate-400">I like your
                                        confidence 💪</p>
                                    <p class="text-xs text-gray-500 dark:text-slate-500">2min ago</p>
                                </div>
                            </a></li>
                        <li><a href="#"
                                class="flex gap-4 p-3 border-b border-gray-200 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-600"><img
                                    src="https://placehold.co/48x48/7e22ce/ffffff?text=RJ" alt="User"
                                    class="w-12 h-12 rounded-full">
                                <div class="text-left">
                                    <h6 class="text-sm font-medium text-gray-800 dark:text-white">Robert
                                        Jhon</h6>
                                    <p class="text-sm text-gray-600 dark:text-slate-400">Can you share your
                                        offer?</p>
                                    <p class="text-xs text-gray-500 dark:text-slate-500">10min ago</p>
                                </div>
                            </a></li>
                        <li><a href="#" class="flex gap-4 p-3 hover:bg-slate-100 dark:hover:bg-slate-600"><img
                                    src="https://placehold.co/48x48/be123c/ffffff?text=HD" alt="User"
                                    class="w-12 h-12 rounded-full">
                                <div class="text-left">
                                    <h6 class="text-sm font-medium text-gray-800 dark:text-white">Henry
                                        Dholi</h6>
                                    <p class="text-sm text-gray-600 dark:text-slate-400">I came across your
                                        profile...</p>
                                    <p class="text-xs text-gray-500 dark:text-slate-500">1day ago</p>
                                </div>
                            </a></li>
                    </ul>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div class="relative">
                <button id="profile-button" class="flex items-center gap-3">
                    @php
                        $initials = strtoupper(substr(Auth::user()->name ?? 'User', 0, 2));
                    @endphp
                    <img src="https://placehold.co/40x40/7e22ce/ffffff?text={{ $initials }}"
                        alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-full">
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-semibold text-gray-800 dark:text-white">
                            {{ Auth::user()->name ?? 'User' }}</p>
                        <p class="text-xs text-gray-500 dark:text-slate-400">{{ $roleUser }}</p>
                    </div>
                    <i class="bi bi-chevron-down hidden md:block text-slate-400"></i>
                </button>
                <div id="profile-dropdown"
                    class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-700 rounded-lg shadow-xl hidden z-20 border border-gray-200 dark:border-slate-600">
                    <a href="{{ route('profile') }}"
                        class="block px-4 py-2 text-sm text-gray-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600 rounded-t-lg">
                        Akun Saya</a>

                    <div class="border-t border-gray-200 dark:border-slate-600"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left block px-4 py-2 text-sm text-red-500 dark:text-red-400 
                                        hover:bg-slate-100 dark:hover:bg-slate-600 rounded-b-lg focus:outline-none">
                            <i class="bi bi-box-arrow-right"></i>
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- Overlay Search -->
<div id="searchOverlay" class="fixed inset-0 bg-black/20 backdrop-blur-sm hidden flex items-center justify-center z-50">

    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-lg px-6 py-8 w-full max-w-md relative">
        <div class="mb-4">

            <button id="closeOverlay"
                class="absolute top-3 right-3 text-red-800 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800 rounded-full w-8 h-8 flex items-center justify-center">✕</button>
        </div>
        <input id="overlaySearchInput" type="text" placeholder="Cari menu..."
            class="w-full border border-slate-300 dark:border-slate-600 rounded-lg py-3 px-4 mb-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-slate-700 dark:text-white">

        <!-- Daftar hasil pencarian -->
        <div class="max-h-[70vh] overflow-y-auto pr-2">
            <ul id="searchResults" class="space-y-2"></ul>
        </div>

        <!-- Tombol close -->
    </div>
</div>
<x-modal id="modalTemplate" title="Semua Notifikasi">
    <div class="max-h-96 overflow-y-auto no-scrollbar space-y-4">
        <div id="list-notifikasi-modal" class="space-y-2">

        </div>
    </div>

</x-modal>
@push('scripts')
    <script>
        const btnNotif = document.getElementById('btn-notifikasi');
        const modal = document.getElementById('modalTemplate');

        btnNotif.addEventListener('click', () => {
            modal.classList.remove('hidden');
        });

        function closeForm() {
            modal.classList.add('hidden');
        }

        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeForm('modalTemplate');
        });
    </script>
    <script>
        const countNotif = document.getElementById('count-notifikasi');
        const listNavbar = document.getElementById('list-notifikasi-navbar');
        const listModal = document.getElementById('list-notifikasi-modal');

        async function loadNotifikasi() {
            const res = await fetch('/notifikasi/json');
            const data = await res.json();
            countNotif.textContent = data.count;

            // ===== Navbar =====
            listNavbar.innerHTML = '';
            if (data.notifikasi.length === 0) {
                listNavbar.innerHTML = `
            <li>
                <a href="#" class="flex flex-col gap-1 p-3 border-b border-gray-200 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-600">
                    <p class="text-sm text-gray-800 dark:text-slate-200">Belum ada notifikasi</p>
                </a>
            </li>`;
            } else {
                data.notifikasi.forEach(notif => {
                    const li = document.createElement('li');
                    li.innerHTML = `
                <a href="#" class="flex flex-col gap-1 p-3 border-b border-gray-200 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-600">
                    <p class="text-sm text-gray-800 dark:text-slate-200">
                        <span class="font-semibold">${notif.title}</span><br>${notif.message}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-slate-400">${new Date(notif.created_at).toLocaleString()}</p>
                </a>`;
                    listNavbar.appendChild(li);
                });
            }

            // ===== Modal =====
            listModal.innerHTML = '';
            if (data.notifikasi.length === 0) {
                listModal.innerHTML = `
            <li>
                <a href="#" class="flex flex-col gap-1 p-3 border-b border-gray-200 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-600">
                    <p class="text-sm text-gray-800 dark:text-slate-200">Belum ada notifikasi</p>
                </a>
            </li>`;
            } else {
                data.notifikasi.forEach(notif => {
                    const div = document.createElement('div');
                    div.id = `notif-${notif.id}`;
                    div.className =
                        `relative p-4 bg-slate-100 dark:bg-slate-700 rounded-lg border-l-4 ${notif.read_at ? 'border-slate-400' : 'border-indigo-500'} flex justify-between items-start`;
                    div.innerHTML = `
                <div class="flex-1">
                    <a href="#" class="font-semibold text-indigo-600 dark:text-white">${notif.title}</a>
                    <p class="text-sm text-gray-600 dark:text-slate-300">${notif.message}</p>
                    <p class="text-xs text-gray-500 dark:text-slate-400 mt-2">${new Date(notif.created_at).toLocaleString()}</p>
                </div>
                <button onclick="hapusNotifikasi(${notif.id})" class="ml-4 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200 text-xl font-bold">
                    <i class="bi bi-trash"></i>
                </button>
            `;
                    listModal.appendChild(div);
                });
            }
        }

        async function hapusNotifikasi(id) {
            const res = await fetch(`/notifikasi/${id}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            if (data.success) loadNotifikasi();
        }

        loadNotifikasi();

        setInterval(loadNotifikasi, 30000);
    </script>
    </script>
    <script>
        @if (Auth::user()->role == 'super_admin')
            const menuData = [{
                    name: "Dashboard",
                    url: "/home"
                },
                {
                    icon: "bi bi-newspaper",
                    name: "Berita",
                    url: "/admin/berita"
                },
                {
                    icon: "bi bi-image-fill",
                    name: "Galeri",
                    url: "/admin/galeri"
                },
                {
                    icon: "bi bi-folder-fill",
                    name: "Bidang",
                    url: "/admin/bidang"
                },
                {
                    icon: "bi bi-briefcase-fill",
                    name: "Jabatan",
                    url: "/admin/jabatan"
                },
                {
                    icon: "bi bi-people-fill",
                    name: "Golongan",
                    url: "/admin/golongan"
                },
                {
                    icon: "bi bi-file-earmark-fill",
                    name: "Kategori Dokument",
                    url: "/admin/document-kategori"
                },
                {
                    icon: "bi bi-file-earmark-text-fill",
                    name: "Dokument",
                    url: "/admin/documents"
                },
                {
                    icon: "bi bi-people-fill",
                    name: "Pegawai",
                    url: "/admin/pegawai"
                },
                {
                    icon: "bi bi-box-arrow-in-right",
                    name: "Aktifitas Login",
                    url: "/admin/login-logs"
                },
                {
                    icon: "bi bi-activity",
                    name: "Aktifitas Log Data",
                    url: "/admin/activity-logs"
                },
                {
                    icon: "bi bi-eye-fill",
                    name: "Aktifitas Akses Tampilan",
                    url: "/admin/view-logs"
                },
                {
                    icon: "bi bi-gear-fill",
                    name: "Pengaturan Website",
                    url: "/admin/website-setting"
                },
                {
                    icon: "bi bi-person-circle",
                    name: "Profile",
                    url: "/admin/profile"
                },
                {
                    icon: "bi bi-gear",
                    name: "Lending Page Setting",
                    url: "/admin/lending-page"
                },
                {
                    icon: "bi bi-code-slash",
                    name: "Template Editor",
                    url: "/admin/lending-page/template"
                },
                {
                    icon: "bi bi-folder",
                    name: "Laporan Triwulan",
                    url: "/triwulan"
                },
            ];
        @else
            const menuData = [{
                    name: "Dashboard",
                    url: "/home"
                },
                {
                    icon: "bi bi-newspaper",
                    name: "Berita",
                    url: "/admin/berita"
                },
                {
                    icon: "bi bi-image-fill",
                    name: "Galeri",
                    url: "/admin/galeri"
                },

                {
                    icon: "bi bi-file-earmark-text-fill",
                    name: "Dokument",
                    url: "/admin/documents"
                },

                {
                    icon: "bi bi-person-circle",
                    name: "Profile",
                    url: "/admin/profile"
                },
                @if (Auth::user()->role == 'admin' || Auth::user()->role == 'opd')
                    {
                        icon: "bi bi-folder",
                        name: "Laporan Triwulan",
                        url: "/triwulan"
                    },
                @endif
            ];
        @endif

        const searchInput = document.getElementById('searchInput');
        const searchOverlay = document.getElementById('searchOverlay');
        const overlaySearchInput = document.getElementById('overlaySearchInput');
        const searchResults = document.getElementById('searchResults');
        const closeOverlay = document.getElementById('closeOverlay');

        let currentIndex = -1;
        let currentItems = [];

        function renderResults(filtered) {
            currentItems = filtered;
            currentIndex = -1;

            searchResults.innerHTML = filtered.map(item => `
            <li class="p-3 bg-slate-100 dark:bg-slate-700 rounded-lg 
                       hover:bg-indigo-500 dark:hover:bg-indigo-600 
                       hover:text-white transition cursor-pointer"
                data-url="${item.url}"
                onclick="window.location='${item.url}'">
                <i class="${item.icon ?? 'bi bi-grid-1x2-fill'} mr-4"></i> ${item.name}
            </li>
        `).join('');

            if (filtered.length === 0) {
                searchResults.innerHTML = `<li class="text-center text-slate-500">Tidak ditemukan</li>`;
            }
        }
        // shortcuts to open search overlay
        document.addEventListener("keydown", (e) => {
            // Abaikan jika sedang mengetik di input/textarea
            const tag = e.target.tagName.toLowerCase();
            if (tag === "input" || tag === "textarea") return;

            if (e.key === "/") {
                e.preventDefault();
                searchOverlay.classList.remove("hidden");
                overlaySearchInput.focus();
                renderResults(menuData);
            }
            if (e.key === "Escape") {
                searchOverlay.classList.add('hidden');
                overlaySearchInput.value = '';
                searchResults.innerHTML = '';
                currentIndex = -1;
            }
        });

        // Buka overlay saat klik search di header
        searchInput.addEventListener('click', () => {
            renderResults(menuData);
            searchOverlay.classList.remove('hidden');
            overlaySearchInput.focus();
        });

        // Tutup overlay
        closeOverlay.addEventListener('click', () => {
            searchOverlay.classList.add('hidden');
            overlaySearchInput.value = '';
            searchResults.innerHTML = '';
            currentIndex = -1;
        });

        // Filter hasil pencarian berdasarkan input
        overlaySearchInput.addEventListener('input', () => {
            const query = overlaySearchInput.value.toLowerCase();
            const filtered = menuData.filter(item => item.name.toLowerCase().includes(query));
            currentIndex = -1;
            currentItems = filtered;

            searchResults.innerHTML = filtered.map(item =>
                `<li class="p-3 bg-slate-100 dark:bg-slate-700 rounded-lg 
                        hover:bg-indigo-500 dark:hover:bg-indigo-600 
                        hover:text-white transition cursor-pointer" 
                        data-url="${item.url}"
                    onclick="window.location='${item.url}'">
                   <i class="${item.icon ?? 'bi bi-grid-1x2-fill'} mr-4"></i>  ${item.name}
                </li>`
            ).join('');

            if (filtered.length === 0 && query) {
                searchResults.innerHTML = `<li class="text-center text-slate-500">Tidak ditemukan</li>`;
            }
        });
        // Navigasi keyboard di hasil
        overlaySearchInput.addEventListener('keydown', (e) => {
            const items = Array.from(searchResults.querySelectorAll('li'));
            if (items.length === 0) return;

            if (e.key === "ArrowDown") {
                e.preventDefault();
                currentIndex = (currentIndex + 1) % items.length;
                highlightItem(items);
            } else if (e.key === "ArrowUp") {
                e.preventDefault();
                currentIndex = (currentIndex - 1 + items.length) % items.length;
                highlightItem(items);
            } else if (e.key === "Enter") {
                e.preventDefault();
                if (currentIndex >= 0 && items[currentIndex]) {
                    const url = items[currentIndex].dataset.url;
                    if (url) window.location.href = url;
                }
            }
        });

        function highlightItem(items) {
            items.forEach((item, i) => {
                if (i === currentIndex) {
                    item.classList.add('bg-indigo-500', 'text-white');
                    item.scrollIntoView({
                        block: 'nearest',
                        behavior: 'smooth'
                    });
                } else {
                    item.classList.remove('bg-indigo-500', 'text-white');
                }
            });
        }


        // Tutup overlay dengan klik di luar kotak
        searchOverlay.addEventListener('click', (e) => {
            if (e.target === searchOverlay) {
                searchOverlay.classList.add('hidden');
            }
        });
    </script>
@endpush
