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
            <div
                class="mx-auto w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center mb-4">
                <i class="bi bi-exclamation-triangle-fill text-3xl text-red-600 dark:text-red-400"></i>
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
                onSuccess: options.onSuccess || function() {},
                onError: options.onError || function() {}
            };

            // Update modal content
            titleEl.textContent = config.title;
            textEl.textContent = config.text;
            confirmBtnText.textContent = config.confirmText;
            cancelBtn.textContent = config.cancelText;

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
                    var icon = confirmBtn.querySelector('i');
                    if (icon) {
                        icon.className = 'bi bi-trash3';
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
                confirmBtnText.textContent = 'Menghapus...';
                var icon = confirmBtn.querySelector('i');
                if (icon) {
                    icon.className = 'bi bi-arrow-repeat animate-spin';
                }

                var csrfToken = document.querySelector('meta[name="csrf-token"]');
                csrfToken = csrfToken ? csrfToken.getAttribute('content') : '';

                fetch(config.url, {
                        method: config.method,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(function(response) {
                        return response.json().then(function(data) {
                            if (!response.ok) throw new Error(data.message ||
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
