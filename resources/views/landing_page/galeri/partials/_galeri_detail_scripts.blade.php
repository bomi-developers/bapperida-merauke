<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterTabs = document.querySelectorAll('.filter-tab');
        const gridContainer = document.getElementById('galeri-masonry-grid');
        const galeriId = '{{ $galeri->id }}';

        filterTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // ... (Logika filter AJAX Anda tetap sama) ...
                filterTabs.forEach(t => t.classList.replace('btn-tab-active',
                    'btn-tab-inactive'));
                this.classList.replace('btn-tab-inactive', 'btn-tab-active');

                const filterType = this.dataset.filter;

                // 2. Tampilkan loading
                gridContainer.innerHTML =
                    `<div class="col-span-2 md:col-span-4 text-center py-16"><i class="bi bi-arrow-repeat text-4xl text-gray-400 animate-spin"></i><p class="mt-2 text-gray-500">Memuat item...</p></div>`;

                // 3. Buat URL AJAX
                const fetchUrl = `/galeri/${galeriId}/filter?type=${filterType}`;

                // 4. Kirim request AJAX
                fetch(fetchUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        gridContainer.innerHTML = data.html;
                    })
                    .catch(error => {
                        console.error('Error filtering galeri items:', error);
                        gridContainer.innerHTML =
                            `<div class="col-span-2 md:col-span-4 text-center py-16"><p class="text-red-500">Gagal memuat item.</p></div>`;
                    });
            });
        });

        // ===================================
        // === SCRIPT BARU UNTUK MODAL VIEWER ===
        // ===================================
        const viewerModal = document.getElementById('item-viewer-modal');
        const viewerModalContent = viewerModal.querySelector(
            '.relative.bg-white'); // Target konten untuk animasi
        const viewerContent = document.getElementById('viewer-content');
        const viewerCaption = document.getElementById('viewer-caption');
        const viewerCaptionContainer = document.getElementById('viewer-caption-container');
        const closeViewerBtn = viewerModal.querySelector('.close-viewer-btn');
        const masonryGrid = document.getElementById('galeri-masonry-grid');

        // Fungsi helper untuk mengubah URL YouTube
        const getYouTubeEmbedUrl = (url) => {
            if (!url) return null;
            let videoId = null;
            const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
            const match = url.match(regExp);
            if (match && match[2].length === 11) {
                videoId = match[2];
            } else {
                // Coba jika URL-nya adalah ID saja (meskipun tidak umum)
                if (url.length === 11) videoId = url;
            }
            return videoId ? `https://www.youtube.com/embed/${videoId}?autoplay=1` :
                null; // Tambah autoplay
        };

        // Fungsi untuk membuka modal
        const openViewerModal = (tipe, path, caption) => {
            let mediaHtml = '';

            if (tipe === 'image') {
                mediaHtml =
                    `<img src="{{ asset('storage') }}/${path}" alt="${caption}" class="max-w-full max-h-[75vh] object-contain rounded-lg" loading="lazy">`;
            } else if (tipe === 'video') {
                mediaHtml =
                    `<video controls preload="none" autoplay class="w-full max-h-[75vh] rounded-lg"><source src="{{ asset('storage') }}/${path}" type="video/mp4">Browser Anda tidak mendukung tag video.</video>`;
            } else if (tipe === 'video_url') {
                const embedUrl = getYouTubeEmbedUrl(path);
                if (embedUrl) {
                    mediaHtml =
                        `<iframe loading="lazy" class="w-full aspect-video max-h-[75vh] rounded-lg" src="${embedUrl}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>`;
                } else {
                    mediaHtml =
                        `<div class="text-white p-4">URL Video tidak valid atau tidak didukung: ${path}</div>`;
                }
            }

            viewerContent.innerHTML = mediaHtml;

            if (caption && caption.trim() !== '') {
                viewerCaption.textContent = caption;
                viewerCaptionContainer.classList.remove('hidden');
            } else {
                viewerCaption.textContent = '';
                viewerCaptionContainer.classList.add('hidden');
            }

            viewerModal.classList.remove('hidden');
            setTimeout(() => { // Memicu transisi
                viewerModal.classList.remove('opacity-0');
                viewerModalContent.classList.remove('scale-95');
            }, 10);
        };

        // Fungsi untuk menutup modal
        const closeViewerModal = () => {
            viewerModal.classList.add('opacity-0');
            viewerModalContent.classList.add('scale-95');
            setTimeout(() => { // Tunggu transisi selesai
                viewerModal.classList.add('hidden');
                viewerContent.innerHTML = ''; // Hentikan video/audio
                viewerCaption.textContent = '';
            }, 300);
        };

        // Event listener untuk klik pada grid
        masonryGrid.addEventListener('click', function(e) {
            const target = e.target.closest('.open-viewer-btn');
            if (target) {
                e.preventDefault(); // Mencegah link default
                const tipe = target.dataset.tipe;
                const path = target.dataset.path;
                const caption = target.dataset.caption;
                openViewerModal(tipe, path, caption);
            }
        });

        // Event listener untuk tombol close
        closeViewerBtn.addEventListener('click', closeViewerModal);

        // Opsional: Tutup modal jika klik di luar area konten
        viewerModal.addEventListener('click', function(e) {
            if (e.target === viewerModal) {
                closeViewerModal();
            }
        });

        // Opsional: Tutup modal dengan tombol Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !viewerModal.classList.contains('hidden')) {
                closeViewerModal();
            }
        });

    });
</script>
<style>
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
</style>
