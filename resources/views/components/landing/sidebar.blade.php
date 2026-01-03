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

        <div id="searchResults" class="mt-4 max-h-80 overflow-auto space-y-2">
            <div id="searchSpinner" class="absolute inset-0 flex justify-center items-center bg-white/70 hidden">
                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600"></div>
            </div>
        </div>
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

        <div id="dokumenResults" class="mt-4 max-h-80 overflow-auto space-y-2">
            <div id="dokumenSpinner" class="absolute inset-0 flex justify-center items-center bg-white/70 hidden">
                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600"></div>
            </div>
        </div>
    </div>
</div>
{{-- side nav --}}
<div class="hidden md:flex fixed top-1/2 right-4 transform -translate-y-1/2 z-[500] items-center">
    <div class="flex flex-col justify-between h-full py-24">
        <div id="side-nav">

            <div
                class="flex flex-col items-center space-y-3 bg-white/20 backdrop-blur-md px-2 py-3 rounded-full shadow-lg ml-2">
                <button {{-- id="toggle-search-btn" --}}
                    class="toggle-search-btn group relative flex justify-center items-center w-12 h-12 bg-white text-blue-600 border border-blue-600 rounded-full hover:bg-blue-600 hover:text-white transition-all duration-300">
                    <i class="bi bi-search"></i>
                    <span
                        class="absolute right-full mr-3 px-3 py-1.5 bg-gray-800 text-white text-xs font-semibold rounded-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                        Pencarian
                    </span>
                </button>
                <button {{-- id="toggle-search-btn" --}}
                    class="toggle-dokumen-btn group relative flex justify-center items-center w-12 h-12 bg-white text-blue-600 border border-blue-600 rounded-full hover:bg-blue-600  hover:text-white transition-all duration-300">
                    <i class="bi bi-folder"></i>
                    <span
                        class="absolute right-full mr-3 px-3 py-1.5 bg-gray-800 text-white text-xs font-semibold rounded-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                        Dokumen
                    </span>
                </button>
                <a href="{{ route('login') }}"
                    class="group relative flex justify-center items-center w-12 h-12 bg-white text-blue-600 rounded-full hover:bg-blue-600 hover:text-white border border-blue-600 transition-all duration-300">
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
        <div id="to-top" class=" items-center mt-3">
            <div
                class="flex flex-col items-center space-y-3 bg-white/20 backdrop-blur-md px-2 py-3 rounded-full shadow-lg ml-2">
                <a href="#heading"
                    class="toggle-to-top-btn group relative flex justify-center items-center w-12 h-12 bg-white text-blue-600 rounded-full
                            hover:bg-blue-600 hover:text-white border border-blue-600
                            transition-all duration-300
                            hover:scale-110 active:scale-95">
                    <i class="bi bi-arrow-up-short text-4xl"></i>
                    <span
                        class="absolute right-full mr-3 px-3 py-1.5 bg-gray-800 text-white text-xs font-semibold rounded-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                        Ke atas
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>
<div id="to-top" class="md:hidden fixed bottom-4 right-4 z-[500] flex items-center">
    <div class="flex flex-col items-center bg-white/20 backdrop-blur-md px-2 py-3 rounded-full shadow-lg">
        <a href="#heading"
            class="toggle-to-top-btn group relative flex justify-center items-center w-12 h-12 bg-white text-blue-600 rounded-full
                   hover:bg-blue-600 hover:text-white border border-blue-600
                   transition-all duration-300
                   hover:scale-110 active:scale-95">
            <i class="bi bi-arrow-up-short text-4xl"></i>
            <span
                class="absolute right-full mr-3 px-3 py-1.5 bg-gray-800 text-white text-xs font-semibold rounded-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                Ke atas
            </span>
        </a>
    </div>
</div>
{{-- modal dokumen --}}
<div id="show-dokumen"
    class="fixed inset-0 z-50 hidden bg-black/20 backdrop-blur-sm flex items-center justify-center p-4 transition-all duration-300">
    <div id="modalDokumenBox"
        class="bg-white rounded-2xl shadow-md w-full max-w-4xl max-h-[90vh] flex flex-col transition-all duration-300 scale-95">
        <!-- Header -->
        <div class="p-5 border-b border-gray-200 flex justify-between items-center flex-shrink-0">
            <h3 class="text-xl font-semibold text-gray-800 flex items-center gap-3">
                <i class="bi bi-file-earmark-text text-blue-600"></i>
                Detail Dokumen
            </h3>
            <button type="button"
                class="close-show-dokumen text-red-800 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800 rounded-full w-8 h-8 flex items-center justify-center">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <!-- Content -->
        <div class="flex-grow p-6 space-y-6 overflow-y-auto"
            style="scrollbar-width: thin; scrollbar-color: #A0AEC0 #E2E8F0;">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Kolom Kiri: Cover & File -->
                <div class="lg:col-span-1 space-y-6">
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2">Cover Dokumen</h4>
                        {{-- Placeholder for cover image/message --}}
                        <div id="show-cover-dokumen"
                            class="aspect-video bg-gray-100 rounded-md flex items-center justify-center">
                            {{-- Content will be filled by JS --}}
                        </div>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2">File Dokumen</h4>
                        {{-- Placeholder for file download card --}}
                        <div id="show-file-dokumen"></div>
                    </div>
                </div>

                <!-- Kolom Kanan: Detail Teks -->
                <div class="lg:col-span-2 space-y-4">
                    <h2 id="show-judul-dokumen" class="text-2xl font-bold text-gray-900"></h2>
                    <p class="text-sm text-gray-500 -mt-2">Kategori: <span id="show-kategori-dokumen"
                            class="font-medium text-gray-700"></span></p>

                    <div id="show-lainnya-wrapper-dokumen" class="hidden pt-4 border-t border-gray-200 space-y-5">
                        <div id="show-lainnya-container-dokumen">
                            {{-- Visi, Misi, Keterangan will be filled by JS --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Script toggle mobile menu --}}
@push('scripts')
    {{-- smooth scroll to top --}}
    <script>
        function smoothScrollTo(target, duration = 800) {
            const start = window.pageYOffset;
            const end = target.getBoundingClientRect().top + start;
            const distance = end - start;
            let startTime = null;

            function easeInOutCubic(t) {
                return t < 0.5 ?
                    4 * t * t * t :
                    1 - Math.pow(-2 * t + 2, 3) / 2;
            }

            function animation(currentTime) {
                if (!startTime) startTime = currentTime;
                const timeElapsed = currentTime - startTime;
                const progress = Math.min(timeElapsed / duration, 1);

                window.scrollTo(0, start + distance * easeInOutCubic(progress));

                if (timeElapsed < duration) {
                    requestAnimationFrame(animation);
                }
            }

            requestAnimationFrame(animation);
        }

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (!target) return;

                e.preventDefault();
                smoothScrollTo(target, 900); // durasi bisa diatur
            });
        });
    </script>
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
        // modal dokumen
        const showModalDokumen = document.getElementById('show-dokumen');
        const closeModalDokumen = document.querySelectorAll('.close-show-dokumen');
        const modalDokumenBox = document.getElementById('modalDokumenBox');
        // loading
        function showSpinner(spinnerId) {
            const el = document.getElementById(spinnerId);
            if (el) el.classList.remove('hidden');
        }

        function hideSpinner(spinnerId) {
            const el = document.getElementById(spinnerId);
            if (el) el.classList.add('hidden');
        }

        // const openShowModalDokumen = () => showModalDokumen.classList.remove('hidden');
        // const closeShowModalDokumen = () => showModalDokumen.classList.add('hidden');
        const openShowModalDokumen = (delay = 300) => {
            showModalDokumen.classList.remove('hidden');
            setTimeout(() => {
                // animasi
                modalDokumenBox.classList.remove('opacity-0', 'scale-90');
                modalDokumenBox.classList.add('opacity-100', 'scale-100');
            }, 100);
        };

        const closeShowModalDokumen = (delay = 300) => {
            showModalDokumen.classList.add('hidden');
            setTimeout(() => {
                // animasi
                modalDokumenBox.classList.add('opacity-0', 'scale-90');
                modalDokumenBox.classList.remove('opacity-100', 'scale-100');
            }, 100);
        };

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

        // Utility Debounce
        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }

        // Client-Side Cache
        const apiCache = new Map();

        fetch(`/berita/data?top=5`)
            .then(res => res.json())
            .then(res => renderResults(res.data));

        // 🔍 Live Search Berita (Debounced + Cached)
        const perfomSearchBerita = debounce(function(keyword) {
            showSpinner('searchSpinner');

            let url = keyword.length < 2 ?
                `/berita/data?top=5` :
                `/berita/data?keyword=${encodeURIComponent(keyword)}`;

            // Cek Cache JS Dulu
            if (apiCache.has(url)) {
                console.log('Serving from cache:', url);
                renderResults(apiCache.get(url));
                hideSpinner('searchSpinner');
                return;
            }

            fetch(url)
                .then(res => res.json())
                .then(res => {
                    // Simpan ke Cache JS
                    apiCache.set(url, res.data);
                    renderResults(res.data);
                    hideSpinner('searchSpinner');
                })
                .catch(err => {
                    console.error(err);
                    hideSpinner('searchSpinner');
                });
        }, 500); // Wait 500ms

        searchInput.addEventListener('input', function() {
            perfomSearchBerita(this.value.trim());
        });

        // Load Default Dokumen
        fetch(`/dokumen/data`)
            .then(res => res.json())
            .then(res => renderResultsDokumen(res.data));

        // 🔍 Live Search Dokumen (Debounced + Cached)
        const performSearchDokumen = debounce(function(keyword) {
            showSpinner('dokumenSpinner');

            let url = keyword.length < 2 ?
                `/dokumen/data` :
                `/dokumen/data?keyword=${encodeURIComponent(keyword)}`;

            if (apiCache.has(url)) {
                renderResultsDokumen(apiCache.get(url));
                hideSpinner('dokumenSpinner');
                return;
            }

            fetch(url)
                .then(res => res.json())
                .then(res => {
                    apiCache.set(url, res.data);
                    renderResultsDokumen(res.data);
                    hideSpinner('dokumenSpinner');
                })
                .catch(err => {
                    console.error(err);
                    hideSpinner('dokumenSpinner');
                });
        }, 500);

        dokumenInput.addEventListener('input', function() {
            performSearchDokumen(this.value.trim());
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
                html += `<div onclick="openDokumenModal(${b.id})">
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
                        </div></div>`;
            });

            dokumenResult.innerHTML = html;
        }

        function openDokumenModal(id) {
            closeDokumen();

            fetch(`/admin/documents/${id}`)
                .then(res => res.json())
                .then(data => {
                    const d = data;
                    console.log(d);
                    // Set cover
                    document.getElementById('show-cover-dokumen').innerHTML = `
                        <img src="/storage/${d.cover}" class="w-full h-full object-cover rounded-md">
                    `;

                    // Set file dokumen
                    const fileName = d.file.split('/').pop();
                    const downloadUrl = `{{ url('admin/documents') }}/${d.id}/download`;
                    document.getElementById('show-file-dokumen').innerHTML = `
                        <a href="${downloadUrl}" class="relative block w-full h-24 rounded-lg overflow-hidden border border-slate-700 group transition-all duration-300 hover:shadow-lg hover:border-blue-500" title="Unduh ${fileName}">
                        <div class="absolute inset-0 bg-slate-900/50 flex items-center justify-center">
                            <i class="bi bi-file-earmark-zip-fill text-4xl text-slate-500"></i>
                        </div>
                        <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
                        <div class="absolute inset-0 flex flex-col items-center justify-center bg-blue-600/80 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <i class="bi bi-download text-2xl"></i>
                            <span class="mt-1 font-semibold text-xs">Unduh File</span>
                        </div>
                        <div class="absolute bottom-0 left-0 right-0 p-2 bg-black/50 text-white text-xs font-medium text-center truncate">
                            ${fileName}
                        </div>
                    </a>
                    `;

                    // Judul & kategori
                    document.getElementById('show-judul-dokumen').textContent = d.judul;
                    document.getElementById('show-kategori-dokumen').textContent = d.kategori?.nama_kategori ?? 'N/A';

                    // Lainnya
                    let extra = "";
                    if (d.visi) extra += `<p><strong>Visi:</strong> ${d.visi}</p>`;
                    if (d.misi) extra += `<p><strong>Misi:</strong> ${d.misi}</p>`;
                    if (d.keterangan) extra += `<p><strong>Keterangan:</strong> ${d.keterangan}</p>`;

                    const wrapper = document.getElementById('show-lainnya-wrapper-dokumen');
                    const container = document.getElementById('show-lainnya-container-dokumen');

                    if (extra.trim() === "") {
                        wrapper.classList.add('hidden');
                    } else {
                        container.innerHTML = extra;
                        wrapper.classList.remove('hidden');
                    }

                    // Tampilkan modal
                    openShowModalDokumen();
                });
        }
        // --- Close Modal Dokumen ---
        closeModalDokumen.forEach(btn => {
            btn.addEventListener('click', closeShowModalDokumen);
        });

        // Klik area overlay menutup modal
        showModalDokumen.addEventListener('click', (e) => {
            if (e.target === showModalDokumen) {
                closeShowModalDokumen();
            }
        });
    </script>
@endpush
