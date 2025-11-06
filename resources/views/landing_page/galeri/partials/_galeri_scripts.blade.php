{{-- Swiper JS --}}
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
    // Inisialisasi Swiper untuk Galeri Populer
    if (document.querySelector('.popular-gallery-slider')) {

        // Inisialisasi Swiper BARU (Hanya satu)
        const popularSwiper = new Swiper('.popular-gallery-slider', {
            loop: true,
            speed: 1000, // Transisi 1 detik
            autoplay: {
                delay: 5000,
                disableOnInteraction: false
            },
            pagination: {
                el: '.popular-pagination-galeri', // Gunakan class unik
                clickable: true,
                renderBullet: function(index, className) {
                    return '<span class="' + className +
                        ' bg-gray-400 w-2.5 h-2.5 rounded-full mx-1 cursor-pointer"></span>';
                }
            },
            effect: 'fade', // Gunakan 'fade' untuk efek UI yang lebih pas
            fadeEffect: {
                crossFade: true
            },
        });
    }

    // --- SCRIPT UNTUK FILTER AJAX ---
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');
        const sortFilter = document.getElementById('sort-filter');
        const tanggalFilter = document.getElementById('tanggal-filter');
        const gridContainer = document.getElementById('galeri-grid-container');
        const paginationContainer = document.getElementById('pagination-container');
        const filterTabs = document.querySelectorAll('.filter-tab');

        let currentFilterType = 'album'; // Default filter
        let debounceTimer;

        function fetchGaleri(page = 1) {
            const search = searchInput.value;
            const sort = sortFilter.value;
            const tanggal = tanggalFilter.value; // Ambil nilai dari input date

            const params = new URLSearchParams({
                search: search,
                sort: sort,
                tanggal: tanggal,
                type: currentFilterType,
                page: page
            });
            const fetchUrl = `{{ route('galeri.public.search') }}?${params.toString()}`;

            // Tampilkan loading
            gridContainer.innerHTML =
                `<div class="col-span-1 md:col-span-2 lg:col-span-4 text-center py-16"><i class="bi bi-arrow-repeat text-4xl text-gray-400 animate-spin"></i><p class="mt-2 text-gray-500">Memuat konten...</p></div>`;
            paginationContainer.innerHTML = '';

            fetch(fetchUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    gridContainer.innerHTML = data.html;
                    paginationContainer.innerHTML = data.pagination;
                    // PERBAIKAN: Hapus logika disable/enable filter tanggal
                })
                .catch(error => {
                    console.error('Error fetching galeri:', error);
                    gridContainer.innerHTML =
                        `<div class="col-span-1 md:col-span-2 lg:col-span-4 text-center py-16"><p class="text-red-500">Gagal memuat konten.</p></div>`;
                });
        }

        // --- Event Listeners untuk Filter ---

        searchInput.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => fetchGaleri(1), 500);
        });

        sortFilter.addEventListener('change', () => fetchGaleri(1));
        tanggalFilter.addEventListener('change', () => fetchGaleri(1));

        // Event listener untuk Filter Tipe (Tabs)
        filterTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                filterTabs.forEach(t => t.classList.replace('btn-tab-active',
                    'btn-tab-inactive'));
                this.classList.replace('btn-tab-inactive', 'btn-tab-active');
                currentFilterType = this.dataset.filter;
                // Ubah placeholder pencarian
                if (currentFilterType === 'album') {
                    searchInput.placeholder = 'Cari berdasarkan judul album...';
                } else {
                    searchInput.placeholder = 'Cari berdasarkan judul/caption...';
                }
                fetchGaleri(1);
            });
        });

        // Event listener untuk Paginasi (Delegasi Event)
        paginationContainer.addEventListener('click', function(e) {
            let target = e.target;
            if (target.tagName !== 'A') {
                target = target.closest('a');
            }

            if (target && target.tagName === 'A' && (target.hasAttribute('rel') || target.getAttribute(
                    'href').includes('page='))) {
                e.preventDefault();
                const urlString = target.getAttribute('href');
                if (urlString) {
                    try {
                        const urlObj = new URL(urlString);
                        const page = urlObj.searchParams.get('page');
                        if (page) {
                            fetchGaleri(page); // Panggil fetchGaleri DENGAN nomor halaman
                        }
                    } catch (error) {
                        console.error('Error parsing pagination URL:', error);
                    }
                }
            }
        });

        // PERBAIKAN: Hapus inisialisasi disable filter tanggal
    });
</script>
<style>
    /* ... (Style Swiper dan Social Icon Anda) ... */
    .popular-pagination-galeri .swiper-pagination-bullet {
        background-color: #9ca3af;
        /* gray-400 */
        opacity: 0.7;
    }

    .popular-pagination-galeri .swiper-pagination-bullet-active {
        background-color: #ffffff !important;
        /* white */
        opacity: 1;
    }

    /* Style untuk Tombol Filter Tipe */
    .btn-tab-active {
        background-color: #2563eb;
        /* bg-blue-600 */
        color: #ffffff;
        /* text-white */
        padding: 0.5rem 1.25rem;
        /* py-2 px-5 */
        border-radius: 0.5rem;
        /* rounded-lg */
        font-size: 0.875rem;
        /* text-sm */
        font-weight: 600;
        /* font-semibold */
        transition: all 0.2s ease-in-out;
    }

    .btn-tab-inactive {
        background-color: transparent;
        color: #4b5563;
        /* text-gray-600 */
        padding: 0.5rem 1.25rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        /* font-medium */
        transition: all 0.2s ease-in-out;
    }

    .btn-tab-inactive:hover {
        background-color: #f3f4f6;
        /* bg-gray-100 */
    }

    /* Style Paginasi (Tetap ada) */
    .pagination nav[role="navigation"] {
        /* ... */
    }

    /* ... (sisa style paginasi Anda) ... */
</style>
