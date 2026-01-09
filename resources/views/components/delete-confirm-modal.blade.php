{{-- 
    Reusable Delete Confirmation Modal Component
    Usage: Include this component once in your layout, then use the global deleteConfirm() function
    
    JavaScript API:
    deleteConfirm({
        title: 'Anda yakin?',
        text: 'Data ini akan dihapus permanen!',
        url: '/admin/resource/1',
        onSuccess: function(response) { },
        onError: function(error) { }
    });
--}}

<!-- Delete Confirmation Modal -->
<div id="delete-confirm-modal"
    class="fixed inset-0 z-[9999] hidden items-center justify-center p-4 transition-all duration-300"
    style="background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-md transform transition-all duration-300 border border-gray-200 dark:border-gray-700"
        id="delete-confirm-content" style="transform: scale(0.95); opacity: 0;">
        <!-- Header with Icon -->
        <div class="pt-6 px-6 text-center">
            <div id="delete-confirm-icon-container"
                class="mx-auto w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center mb-4 transition-colors duration-200">
                <i id="delete-confirm-icon"
                    class="bi bi-exclamation-triangle-fill text-3xl text-red-600 dark:text-red-400 transition-colors duration-200"></i>
            </div>
            <h3 id="delete-confirm-title" class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                Anda yakin?
            </h3>
            <p id="delete-confirm-text" class="text-gray-600 dark:text-gray-400 text-sm">
                Data ini akan dihapus permanen dan tidak dapat dikembalikan.
            </p>
        </div>

        <!-- Footer with Buttons -->
        <div class="p-6 flex gap-3 justify-center">
            <button type="button" id="delete-confirm-cancel"
                class="px-5 py-2.5 text-sm font-medium rounded-xl border border-gray-300 dark:border-gray-600 
                       text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 
                       hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                Batal
            </button>
            <button type="button" id="delete-confirm-btn"
                class="px-5 py-2.5 text-sm font-medium rounded-xl text-white 
                       bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-600
                       transition-colors duration-200 flex items-center gap-2">
                <i class="bi bi-trash3"></i>
                <span id="delete-confirm-btn-text">Ya, hapus!</span>
            </button>
        </div>
    </div>
</div>

<script>
    (function() {
        // Global delete confirmation function
        window.deleteConfirm = function(options) {
            var modal = document.getElementById('delete-confirm-modal');
            var content = document.getElementById('delete-confirm-content');
            var titleEl = document.getElementById('delete-confirm-title');
            var textEl = document.getElementById('delete-confirm-text');
            var confirmBtn = document.getElementById('delete-confirm-btn');
            var confirmBtnText = document.getElementById('delete-confirm-btn-text');
            var cancelBtn = document.getElementById('delete-confirm-cancel');

            // New Elements for Dynamic Styling
            var iconContainer = document.getElementById('delete-confirm-icon-container');
            var icon = document.getElementById('delete-confirm-icon');

            if (!modal) {
                console.error('Delete confirm modal not found');
                return;
            }

            // Set options with defaults
            var config = {
                title: options.title || 'Anda yakin?',
                text: options.text || 'Data ini akan dihapus permanen.',
                confirmText: options.confirmText || 'Ya, hapus!',
                cancelText: options.cancelText || 'Batal',
                url: options.url || '',
                method: options.method || 'DELETE',
                type: options.type || 'danger', // danger, success, info
                iconClass: options.iconClass || null, // custom icon override
                onSuccess: options.onSuccess || function() {},
                onError: options.onError || function() {}
            };

            // Update modal content
            titleEl.textContent = config.title;
            textEl.textContent = config.text;
            confirmBtnText.textContent = config.confirmText;
            cancelBtn.textContent = config.cancelText;

            // Apply Styling based on Type
            // Reset base classes
            iconContainer.className =
                'mx-auto w-16 h-16 rounded-full flex items-center justify-center mb-4 transition-colors duration-200';
            icon.className = 'text-3xl transition-colors duration-200';
            confirmBtn.className =
                'px-5 py-2.5 text-sm font-medium rounded-xl text-white transition-colors duration-200 flex items-center gap-2';

            if (config.type === 'success') {
                iconContainer.classList.add('bg-green-100', 'dark:bg-green-900/30');
                icon.className += ' bi ' + (config.iconClass || 'bi-check-lg') +
                    ' text-green-600 dark:text-green-400';
                confirmBtn.classList.add('bg-green-600', 'hover:bg-green-700', 'dark:bg-green-700',
                    'dark:hover:bg-green-600');
            } else if (config.type === 'info') {
                iconContainer.classList.add('bg-blue-100', 'dark:bg-blue-900/30');
                icon.className += ' bi ' + (config.iconClass || 'bi-info-circle-fill') +
                    ' text-blue-600 dark:text-blue-400';
                confirmBtn.classList.add('bg-blue-600', 'hover:bg-blue-700', 'dark:bg-blue-700',
                    'dark:hover:bg-blue-600');
            } else {
                // Default: danger
                iconContainer.classList.add('bg-red-100', 'dark:bg-red-900/30');
                icon.className += ' bi ' + (config.iconClass || 'bi-exclamation-triangle-fill') +
                    ' text-red-600 dark:text-red-400';
                confirmBtn.classList.add('bg-red-600', 'hover:bg-red-700', 'dark:bg-red-700',
                    'dark:hover:bg-red-600');
            }

            // Show modal
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            setTimeout(function() {
                content.style.transform = 'scale(1)';
                content.style.opacity = '1';
            }, 10);

            // Close modal function
            function closeModal() {
                content.style.transform = 'scale(0.95)';
                content.style.opacity = '0';
                setTimeout(function() {
                    modal.classList.add('hidden');
                    modal.style.display = 'none';
                    // Reset button
                    confirmBtn.disabled = false;
                    confirmBtnText.textContent = config.confirmText;

                    // Reset Icon Spinner
                    if (config.type === 'success') icon.className = 'bi ' + (config.iconClass ||
                        'bi-check-lg') + ' text-green-600 dark:text-green-400';
                    else if (config.type === 'info') icon.className = 'bi ' + (config.iconClass ||
                        'bi-info-circle-fill') + ' text-blue-600 dark:text-blue-400';
                    else icon.className = 'bi ' + (config.iconClass || 'bi-exclamation-triangle-fill') +
                        ' text-red-600 dark:text-red-400';

                    // Remove button icon spinner
                    var btnIcon = confirmBtn.querySelector('i');
                    if (btnIcon) {
                        if (config.type === 'success') btnIcon.className = 'bi bi-check-lg';
                        else if (config.type === 'info') btnIcon.className =
                            'bi bi-check-lg'; // or save
                        else btnIcon.className = 'bi bi-trash3';
                    }

                }, 200);
                // Remove listeners
                cancelBtn.onclick = null;
                confirmBtn.onclick = null;
                modal.onclick = null;
            }

            // Cancel handler
            cancelBtn.onclick = closeModal;

            // Backdrop click
            modal.onclick = function(e) {
                if (e.target === modal) closeModal();
            };

            // Confirm handler
            confirmBtn.onclick = function() {
                confirmBtn.disabled = true;
                confirmBtnText.textContent = 'Memproses...';
                var btnIcon = confirmBtn.querySelector('i');
                if (btnIcon) {
                    btnIcon.className = 'bi bi-arrow-repeat animate-spin';
                }

                var csrfToken = document.querySelector('meta[name="csrf-token"]');
                csrfToken = csrfToken ? csrfToken.getAttribute('content') : '';

                fetch(config.url, {
                        method: config.method,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json' // Ensure content type is set
                        }
                    })
                    .then(function(response) {
                        return response.json().then(function(data) {
                            if (!response.ok) throw new Error(data.message ||
                            'Gagal menghapus');
                            if (data.success === false) throw new Error(data.message ||
                                'Gagal menghapus');
                            return data;
                        });
                    })
                    .then(function(data) {
                        closeModal();
                        // Show success toast
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: data.message || 'Berhasil dihapus!',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                        config.onSuccess(data);
                    })
                    .catch(function(error) {
                        // Reset button
                        confirmBtn.disabled = false;
                        confirmBtnText.textContent = config.confirmText;
                        var icon = confirmBtn.querySelector('i');
                        if (icon) {
                            icon.className = 'bi bi-trash3';
                        }
                        // Show error
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: error.message || 'Terjadi kesalahan'
                            });
                        }
                        config.onError(error);
                    });
            };
        };
    })();
</script>

<style>
    #delete-confirm-content {
        transition: transform 0.2s ease-out, opacity 0.2s ease-out;
    }
</style>
