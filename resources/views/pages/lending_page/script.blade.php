<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const swiper = new Swiper('.berita-slider', {
                effect: 'coverflow',
                grabCursor: true,
                centeredSlides: true,
                slidesPerView: 'auto',
                loop: true,
                autoplay: {
                    delay: 3500,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                },
                coverflowEffect: {
                    rotate: -20,
                    stretch: -20,
                    depth: 150,
                    modifier: 1,
                    slideShadows: true,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
            });
        });
    </script>
    <script>
        let editingId = null;

        function openCreateForm() {
            editingId = null;
            $('#modalTitle').text('Tambah Section');
            $('#lendingForm')[0].reset();
            $('#templatePreviewContainer').addClass('hidden');
            $('#lendingModal').removeClass('hidden');
        }

        function closeModal() {
            $('#lendingModal').addClass('hidden');
        }

        $('#id_template').on('change', function() {
            const id = $(this).val();
            const previewContainer = $('#templatePreviewContainer');
            const iframe = document.getElementById('templatePreview');

            if (!id) {
                previewContainer.addClass('hidden');
                iframe.srcdoc = '';
                return;
            }

            // Ambil HTML mentah langsung dari server
            $.get(`/admin/template/${id}`, function(html) {
                if (html && html.trim() !== '') {
                    iframe.srcdoc = html; // tampilkan HTML penuh
                    previewContainer.removeClass('hidden');
                } else {
                    previewContainer.addClass('hidden');
                    iframe.srcdoc = '';
                }
            }).fail(function(xhr) {
                console.error('Gagal memuat template:', xhr);
                previewContainer.addClass('hidden');
                iframe.srcdoc = '';
            });
        });

        $('#lendingForm').on('submit', function(e) {
            e.preventDefault();
            const formData = $(this).serialize();

            let method = editingId ? 'PUT' : 'POST';
            let url = editingId ? `/admin/lending-page/${editingId}` : `/admin/lending-page`;

            $.ajax({
                url,
                method,
                data: formData,
                success: function(res) {
                    alert(res.message);
                    closeModal();
                    loadData();
                },
                error: function(err) {
                    alert('Gagal menyimpan data');
                }
            });
        });
    </script>
@endpush
