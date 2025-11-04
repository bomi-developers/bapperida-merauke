{{-- Load Trix Editor JS & CSS (Pastikan ini ada) --}}
<link rel="stylesheet" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
<script src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>

{{-- Library untuk drag-and-drop --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get main elements from the DOM
        const itemsContainer = document.getElementById('items-container');
        // Get all 'add' buttons
        const addTextButton = document.getElementById('add-text');
        const addImageButton = document.getElementById('add-image');
        const addVideoButton = document.getElementById('add-video');
        const addEmbedButton = document.getElementById('add-embed');
        const addQuoteButton = document.getElementById('add-quote');

        let itemCounter = Date.now();

        // --- CORE FUNCTIONS ---

        /**
         * Creates a new content item element and appends it to the container.
         * @param {string} type - The type of the item (e.g., 'text', 'image').
         * @param {string} content - The initial content value.
         * @param {string} caption - The initial caption value (for images).
         */
        function createItemElement(type, content = '', caption = '') {
            const uniqueId = itemCounter++;
            const wrapper = document.createElement('div');
            wrapper.className =
                'item-block relative p-4 border border-gray-300 dark:border-gray-600 rounded-lg mb-4';
            wrapper.setAttribute('data-id', uniqueId);

            let contentHtml = '';

            // Generate the appropriate input based on the item type
            switch (type) {
                case 'text':
                    // ==========================================================
                    // === PERBAIKAN DI SINI ===
                    // ==========================================================
                    // Kita buat HTML mentah untuk Trix Editor, bukan komponen Blade
                    // Kita gunakan uniqueId untuk membuat ID unik
                    const trixId = `trix-content-${uniqueId}`;

                    contentHtml = `
                        <div class="trix-editor-wrapper">
                            {{-- Input hidden ini yang akan menyimpan konten HTML --}}
                            {{-- Perhatikan 'name' dan 'id' yang sekarang menggunakan uniqueId --}}
                            <input id="${trixId}" type="hidden" name="items[${uniqueId}][content]" value="${escapeHtml(content)}">
                            
                            {{-- Tag Trix Editor yang terhubung ke input via 'input' attribute --}}
                            <trix-editor 
                                input="${trixId}" 
                                class="trix-content border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-200"
                            ></trix-editor>
                        </div>
                    `;
                    // ==========================================================
                    // === AKHIR PERBAIKAN ===
                    // ==========================================================
                    break;
                case 'quote':
                    contentHtml =
                        `<textarea name="items[${uniqueId}][content]" class="form-input block w-full" rows="5" placeholder="Masukkan teks kutipan..." required>${content}</textarea>`;
                    break;
                case 'image':
                    const existingImagePathInput = content ?
                        `<input type="hidden" name="items[${uniqueId}][content]" value="${content}">` : '';
                    const existingImagePreview = content ?
                        `<img src="${'{{ asset('storage') }}/' + content}" alt="Gambar lama" class="mt-2 rounded-md w-32 h-auto object-cover border dark:border-gray-700">` :
                        '';
                    contentHtml = `
                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 text-center">
                            <label for="image_file_${uniqueId}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Upload Gambar Baru (Opsional)</label>
                            <input type="file" id="image_file_${uniqueId}" name="items[${uniqueId}][file]" class="form-input block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept="image/*">
                            <label for="image_caption_${uniqueId}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mt-4 mb-2">Keterangan Gambar (Opsional)</label>
                            <input type="text" id="image_caption_${uniqueId}" name="items[${uniqueId}][caption]" class="form-input block w-full" placeholder="Deskripsi untuk gambar" value="${caption}">
                            ${existingImagePreview}
                            ${existingImagePathInput}
                            ${content ? `<p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Gambar saat ini: ${content.split('/').pop()}</p>` : ''}
                        </div>
                    `;
                    break;
                case 'video':
                    contentHtml =
                        `<input type="url" name="items[${uniqueId}][content]" class="form-input block w-full" placeholder="https://www.youtube.com/watch?v=..." value="${content}" required>`;
                    break;
                case 'embed':
                    contentHtml =
                        `<textarea name="items[${uniqueId}][content]" class="form-input block w-full font-mono text-sm" rows="5" placeholder="<iframe src='...'></iframe>" required>${content}</textarea>`;
                    break;
            }

            // Assemble the full HTML for the new item
            wrapper.innerHTML = `
                <div class="flex justify-between items-center mb-2">
                    <span class="font-semibold text-gray-700 dark:text-gray-300 capitalize handle cursor-move"><i class="bi bi-grip-vertical mr-2"></i> ${type.replace(/_/g, ' ')}</span>
                    <button type="button" class="text-red-500 hover:text-red-700 remove-item" title="Hapus item">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                </div>
                ${contentHtml}
                <input type="hidden" name="items[${uniqueId}][type]" value="${type}">
                <input type="hidden" class="item-position" name="items[${uniqueId}][position]" value="">
            `;

            itemsContainer.appendChild(wrapper);

            // Inisialisasi Trix Editor untuk item baru
            if (type === 'text') {
                const editor = wrapper.querySelector('trix-editor');
                if (editor) {
                    // Trix v2: Set HTML ke editor
                    // Ini penting untuk halaman edit agar konten lama dimuat
                    editor.editor.loadHTML(content);
                }
            }

            updatePositions();
        }

        // Fungsi untuk meng-escape HTML (keamanan)
        function escapeHtml(unsafe) {
            if (unsafe === null || unsafe === undefined) return '';
            // Ganti ' dengan &#039; agar tidak merusak string value="..."
            return unsafe.toString()
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        /**
         * Recalculates and updates the hidden position inputs for all items.
         */
        function updatePositions() {
            const items = itemsContainer.querySelectorAll('.item-block');
            items.forEach((item, index) => {
                const positionInput = item.querySelector('.item-position');
                if (positionInput) {
                    positionInput.value = index;
                }

                // Update name attributes untuk memastikan index konsisten
                const uniqueId = item.getAttribute('data-id');
                item.querySelectorAll('[name^="items["]').forEach(input => {
                    const oldName = input.getAttribute('name');
                    // Ganti ID lama (jika ada) dengan ID unik yang benar
                    const newName = oldName.replace(/items\[.*?\]/, `items[${uniqueId}]`);
                    input.setAttribute('name', newName);
                });
            });
        }

        // --- EVENT LISTENERS ---

        if (addTextButton) addTextButton.addEventListener('click', () => createItemElement('text'));
        if (addImageButton) addImageButton.addEventListener('click', () => createItemElement('image'));
        if (addVideoButton) addVideoButton.addEventListener('click', () => createItemElement('video'));
        if (addEmbedButton) addEmbedButton.addEventListener('click', () => createItemElement('embed'));
        if (addQuoteButton) addQuoteButton.addEventListener('click', () => createItemElement('quote'));


        itemsContainer.addEventListener('click', function(e) {
            const removeButton = e.target.closest('.remove-item');
            if (removeButton) {
                const itemToRemove = removeButton.closest('.item-block');
                if (itemToRemove) {
                    itemToRemove.remove();
                    updatePositions();
                }
            }
        });

        // Inisialisasi SortableJS for drag-and-drop
        new Sortable(itemsContainer, {
            animation: 150,
            handle: '.handle',
            onEnd: function() {
                updatePositions();
            }
        });

        // --- INITIALIZATION ---
        // Inisialisasi item yang sudah ada (untuk halaman edit)
        if (typeof existingItems !== 'undefined' && Array.isArray(existingItems)) {
            existingItems.sort((a, b) => a.position - b.position);
            existingItems.forEach(item => {
                createItemElement(item.type, item.content, item.caption || '');
            });
        }

        updatePositions();
    });
</script>

<style>
    /* Style Trix Editor (dari file trix.blade.php Anda) */
    trix-editor {
        min-height: 200px;
        background-color: #fff;
        /* Pastikan background putih di light mode */
    }

    .dark trix-editor {
        background-color: #1f2937;
        color: #f9fafb;
        border-color: #374151;
    }

    /* Membuat ikon di dalam tombol tidak bisa diklik */
    .remove-item i {
        pointer-events: none;
    }

    /* CSS Anda yang sudah ada */
    .form-input {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        background-color: #f9fafb;
        color: #111827;
    }

    .dark .form-input {
        border-color: #4b5563;
        background-color: #374151;
        color: #f9fafb;
    }

    .sortable-ghost {
        opacity: 0.4;
        background: #c2d4ff;
        border: 1px dashed #667eea;
    }

    .item-block .handle {
        cursor: grab;
    }

    .item-block .handle:active {
        cursor: grabbing;
    }
</style>
