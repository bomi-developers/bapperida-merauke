<div id="searchOverlay"
    class="fixed inset-0 bg-white/5 backdrop-blur-sm z-[800] hidden 
           flex justify-center items-center p-6">

    <div id="searchBox"
        class=" rounded-3xl bg-white/40 backdrop-blur-lg w-full max-w-4xl p-6 
               scale-90 opacity-0 transition-all duration-300 border border-white shadow-lg">

        <div class="relative w-full hover:scale-[102%] transition-transform duration-300 ">
            <!-- Icon -->
            <span class="absolute inset-y-0 left-4 flex items-center text-blue-700">
                <i class="bi bi-search text-xl"></i>
            </span>

            <!-- Input -->
            <input type="text" id="liveSearchInput" placeholder="Cari berita..."
                class="w-full pl-12 p-3 border border-blue-700 rounded-full 
               focus:ring focus:ring-blue-700 bg-white text-black" />
        </div>

        <div id="searchResults" class="mt-4 max-h-80 overflow-auto space-y-2"></div>
    </div>
</div>
<div id="dokumenOverlay"
    class="fixed inset-0 bg-white/5 backdrop-blur-sm z-[800] hidden 
           flex justify-center items-center p-6">

    <div id="dokumenBox"
        class=" rounded-3xl bg-white/40 backdrop-blur-lg w-full max-w-4xl p-6 
               scale-90 opacity-0 transition-all duration-300 border border-white shadow-lg">

        <div class="relative w-full hover:scale-[102%] transition-transform duration-300 ">
            <!-- Icon -->
            <span class="absolute inset-y-0 left-4 flex items-center text-blue-700">
                <i class="bi bi-folder text-xl"></i>
            </span>

            <!-- Input -->
            <input type="text" id="liveDokumenInput" placeholder="Cari Dokumen..."
                class="w-full pl-12 p-3 border border-blue-700 rounded-full 
               focus:ring focus:ring-blue-700 bg-white text-black" />
        </div>

        <div id="dokumenResults" class="mt-4 max-h-80 overflow-auto space-y-2"></div>
    </div>
</div>
{{-- side nav --}}
<div id="side-nav" class="hidden md:flex fixed top-1/2 right-4 transform -translate-y-1/2 z-[500] items-center">
    <div
        class="flex flex-col items-center space-y-3 bg-white/20 backdrop-blur-md px-2 py-3 rounded-full shadow-lg ml-2">
        <button {{-- id="toggle-search-btn" --}}
            class="toggle-search-btn group relative flex justify-center items-center w-12 h-12 bg-white text-blue-600 rounded-full hover:bg-blue-600 hover:text-white transition-all duration-300">
            <i class="bi bi-search"></i>
            <span
                class="absolute right-full mr-3 px-3 py-1.5 bg-gray-800 text-white text-xs font-semibold rounded-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                Pencarian
            </span>
        </button>
        <button {{-- id="toggle-search-btn" --}}
            class="toggle-dokumen-btn group relative flex justify-center items-center w-12 h-12 bg-white text-blue-600 rounded-full hover:bg-blue-600 hover:text-white transition-all duration-300">
            <i class="bi bi-folder"></i>
            <span
                class="absolute right-full mr-3 px-3 py-1.5 bg-gray-800 text-white text-xs font-semibold rounded-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                Dokumen
            </span>
        </button>
        <a href="{{ route('login') }}"
            class="group relative flex justify-center items-center w-12 h-12 bg-white text-blue-600 rounded-full hover:bg-blue-600 hover:text-white transition-all duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            <span
                class="absolute right-full mr-3 px-3 py-1.5 bg-gray-800 text-white text-xs font-semibold rounded-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                Login
            </span>
        </a>
    </div>
</div>
{{-- Script toggle mobile menu --}}
@push('scripts')
    <script>
        document.getElementById('menu-toggle').addEventListener('click', () => {
            const menu = document.getElementById('mobile-menu');
            const icon = document.getElementById('menu-toggle').querySelector('i');
            menu.classList.toggle('hidden');
            icon.classList.toggle('bi-list');
            icon.classList.toggle('bi-x-lg');
        });
        const overlay = document.getElementById('searchOverlay');
        const searchBox = document.getElementById('searchBox');
        const searchInput = document.getElementById('liveSearchInput');
        const searchResult = document.getElementById('searchResults');
        // dokumen
        const dokumenOverlay = document.getElementById('dokumenOverlay');
        const dokumenBox = document.getElementById('dokumenBox');
        const dokumenInput = document.getElementById('liveDokumenInput');
        const dokumenResult = document.getElementById('dokumenResults');

        document.querySelectorAll('.toggle-search-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                overlay.classList.remove('hidden');

                // animasi muncul
                setTimeout(() => {
                    searchBox.classList.remove('opacity-0', 'scale-90');
                    searchBox.classList.add('opacity-100', 'scale-100');
                }, 20);

                searchInput.focus();
            });
        });
        document.querySelectorAll('.toggle-dokumen-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                dokumenOverlay.classList.remove('hidden');

                // animasi muncul
                setTimeout(() => {
                    dokumenBox.classList.remove('opacity-0', 'scale-90');
                    dokumenBox.classList.add('opacity-100', 'scale-100');
                }, 20);

                dokumenInput.focus();
            });
        });

        // Tutup jika klik di luar box
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) closeSearch();
        });
        dokumenOverlay.addEventListener('click', (e) => {
            if (e.target === dokumenOverlay) closeDokumen();
        });

        // Tombol ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === "Escape") closeSearch();
        });

        function closeSearch() {
            searchBox.classList.add('opacity-0', 'scale-90');
            searchBox.classList.remove('opacity-100', 'scale-100');

            setTimeout(() => {
                overlay.classList.add('hidden');
            }, 200);
        }

        function closeDokumen() {
            dokumenBox.classList.add('opacity-0', 'scale-90');
            dokumenBox.classList.remove('opacity-100', 'scale-100');

            setTimeout(() => {
                dokumenOverlay.classList.add('hidden');
            }, 200);
        }

        fetch(`/berita/data?top=5`)
            .then(res => res.json())
            .then(res => renderResults(res.data));
        // 🔍 Live Search OnInput
        searchInput.addEventListener('input', function() {
            let keyword = this.value.trim();

            if (keyword.length < 2) {
                fetch(`/berita/data?top=5`)
                    .then(res => res.json())
                    .then(res => renderResults(res.data));
            }

            fetch(`/berita/data?keyword=${encodeURIComponent(keyword)}`)
                .then(res => res.json())
                .then(res => {
                    renderResults(res.data);
                });
        });
        dokumenInput.addEventListener('input', function() {
            let keyword = this.value.trim();

            if (keyword.length < 2) {
                fetch(`/dokumen/data`)
                    .then(res => res.json())
                    .then(res => renderResultsDokumen(res.data));
            }

            fetch(`/dokumen/data?keyword=${encodeURIComponent(keyword)}`)
                .then(res => res.json())
                .then(res => {
                    renderResultsDokumen(res.data);
                });
        });
        fetch(`/dokumen/data`)
            .then(res => res.json())
            .then(res => renderResultsDokumen(res.data));
        // 🔍 Live Search OnInput
        searchInput.addEventListener('input', function() {
            let keyword = this.value.trim();

            if (keyword.length < 2) {
                fetch(`/dokumen/data`)
                    .then(res => res.json())
                    .then(res => renderResultsDokumen(res.data));
            }

            fetch(`/dokumen/data?keyword=${encodeURIComponent(keyword)}`)
                .then(res => res.json())
                .then(res => {
                    renderResultsDokumen(res.data);
                });
        });

        function formatDate(dateString) {
            const date = new Date(dateString);

            const options = {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                hour12: false
            };

            return date.toLocaleString('id-ID', options);
        }


        function renderResults(items) {
            if (items.length === 0) {
                searchResult.innerHTML = `
                    <div class="p-3 rounded-2xl bg-white border border-red-600  transition text-red-600 shadow-sm">
                        <div class="font-semibold ">Hasil tidak di temukan</div>
                    </div>
                `;
                return;
            }

            let html = "";
            items.forEach(b => {
                html += `<a href="/berita/${b.slug}" >
                        <div class="my-2 mx-2 p-3 rounded-2xl bg-white hover:bg-blue-700 cursor-pointer transition 
                                    text-blue-600 hover:text-white shadow-sm border border-blue-600 hover:border-white flex gap-3 items-center transition-transform duration-300 hover:scale-[102%] ">
                            <!-- Gambar -->
                            <img src="/storage/${b.cover_image}" 
                                class="w-16 h-16 object-cover rounded-xl border border-blue-300 flex-shrink-0"
                                alt="Cover" loading="lazy">

                            <!-- Teks -->
                            <div class="flex flex-col">
                                <div class="font-semibold text-xl leading-tight">${b.title}</div>
                                <div class="text-sm opacity-80">
                                   <i class="bi bi-newspaper"></i> ${b.author?.name ?? 'N/A'} • ${formatDate(b.created_at)}
                                </div>
                            </div>
                        </div></a>`;
            });

            searchResult.innerHTML = html;
        }

        function renderResultsDokumen(items) {
            if (items.length === 0) {
                dokumenResult.innerHTML = `
                    <div class="p-3 rounded-2xl bg-white border border-red-600  transition text-red-600 shadow-sm">
                        <div class="font-semibold ">Dokumen tidak di temukan</div>
                    </div>
                `;
                return;
            }

            let html = "";
            items.forEach(b => {
                html += `<a href="" >
                        <div class="my-2 mx-2 p-3 rounded-2xl bg-white hover:bg-blue-700 cursor-pointer transition 
                                    text-blue-600 hover:text-white shadow-sm border border-blue-600 hover:border-white flex gap-3 items-center transition-transform duration-300 hover:scale-[102%] ">
                            <!-- Gambar -->
                            <img src="/storage/${b.cover}" 
                                class="w-16 h-16 object-cover rounded-xl border border-blue-300 flex-shrink-0"
                                alt="Cover" loading="lazy">

                            <!-- Teks -->
                            <div class="flex flex-col">
                                <div class="font-semibold text-xl leading-tight">${b.judul}</div>
                                <div class="text-sm opacity-80">
                                   <i class="bi bi-folder mr-2"></i> ${b.kategori?.nama_kategori ?? 'N/A'} • ${formatDate(b.created_at)}
                                </div>
                            </div>
                        </div></a>`;
            });

            dokumenResult.innerHTML = html;
        }
    </script>
@endpush
