<x-layout>
    <x-header />

    <main class="p-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <a href="{{ route('admin.lending.index') }}"
                    class="text-sm text-indigo-600 hover:underline inline-block mb-2">
                    &larr; Kembali
                </a>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Template Editor</h3>
            </div>
            <button id="add-template-btn"
                class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-lg shadow-md transition-colors duration-200 flex items-center gap-2">
                <i class="bi bi-plus-circle"></i>
                <span>Tambah Template</span>
            </button>
        </div>

        {{-- Table --}}
        <section
            class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-md overflow-hidden">
            <div class="max-w-full overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">#</th>
                            <th scope="col" class="px-6 py-3">Judul</th>
                            <th scope="col" class="px-6 py-3">Jenis</th>
                            <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($templates as $template)
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-1 font-medium text-gray-900 dark:text-white">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-1 font-medium text-gray-900 dark:text-white">
                                    {{ $template->title }}
                                </td>
                                <td class="px-6 py-1 font-medium text-gray-900 dark:text-white">
                                    @if ($template->is_default)
                                        <span
                                            class="inline-block px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-200">
                                            Default
                                        </span>
                                    @else
                                        <span
                                            class="inline-block px-3 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-200">
                                            Custom
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-1 text-center flex justify-center gap-2">
                                    <a href="{{ url('/admin/template', $template->id) }}" target="_blank"
                                        class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 text-gray-600 dark:text-gray-300"
                                        title="Preview">
                                        <i class="bi bi-eye text-lg"></i>
                                    </a>
                                    <button
                                        class="p-2 rounded-full hover:bg-indigo-100 dark:hover:bg-indigo-700 transition-colors duration-200 text-indigo-600 dark:text-indigo-300"
                                        data-id="{{ $template->id }}" data-title="{{ $template->title }}"
                                        data-content="{{ htmlspecialchars($template->content) }}">
                                        <i class="bi bi-pencil-square text-lg"></i>
                                    </button>

                                    @if (!$template->is_default)
                                        <button
                                            class="p-2 rounded-full hover:bg-red-100 dark:hover:bg-red-700 transition-colors duration-200 text-red-600 dark:text-red-300"
                                            data-id="{{ $template->id }}" title="Hapus"
                                            onclick="handleDeleteClick({{ $template->id }})">
                                            <i class="bi bi-trash text-lg"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    {{-- Modal Template --}}
    <div id="template-modal"
        class="fixed inset-0 hidden bg-black/10 backdrop-blur-sm flex items-center justify-center z-50 transition-all duration-300">
        <div
            class="relative w-full max-w-4xl bg-white dark:bg-gray-900 rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">

            <form id="template-form">

                <h3 id="modal-title" class="text-xl font-semibold mb-5 text-gray-900 dark:text-white">
                    Template
                </h3>
                <button id="close-modal-btn" type="button"
                    class="absolute top-4 right-4 text-red-800 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800 rounded-full w-8 h-8 flex items-center justify-center">&times;</button>
                <div class="modal-content">
                    <input type="hidden" id="template-id">
                    <div>
                        <label for="template-title" class="form-label">Judul Template</label>
                        <input type="text" id="template-title"
                            class="w-full px-4 py-1 text-sm border border-gray-300 dark:border-gray-700 rounded-lg
                                bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100
                                focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-200"
                            required>
                    </div>
                    <div>
                        <label for="editor" class="form-label">Konten (HTML)</label>
                        <div id="editor" class="border rounded-lg dark:border-gray-700"></div>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" id="save-btn"
                        class="px-4 py-2 text-sm font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>



    @push('scripts')
        {{-- CodeMirror --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/codemirror@5.65.16/lib/codemirror.min.css">
        <script src="https://cdn.jsdelivr.net/npm/codemirror@5.65.16/lib/codemirror.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/codemirror@5.65.16/mode/htmlmixed/htmlmixed.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/codemirror@5.65.16/theme/material-darker.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let editor = CodeMirror(document.getElementById('editor'), {
                    mode: 'htmlmixed',
                    lineNumbers: true,
                    theme: 'material-darker',
                    lineWrapping: true,
                    tabSize: 1,
                    viewportMargin: Infinity,
                });
                editor.setSize("100%", 300);

                const modal = document.getElementById('template-modal');
                const addBtn = document.getElementById('add-template-btn');
                const closeModalBtn = document.getElementById('close-modal-btn');
                const form = document.getElementById('template-form');

                const csrfToken = '{{ csrf_token() }}';
                let isEdit = false;

                // --- Modal control ---
                const openModal = () => modal.classList.remove('hidden');
                const closeModal = () => modal.classList.add('hidden');

                const resetForm = () => {
                    form.reset();
                    document.getElementById('template-id').value = '';
                    document.getElementById('template-title').value = '';
                    editor.setValue('');
                };

                // --- Tambah Template ---
                addBtn.addEventListener('click', () => {
                    resetForm();
                    isEdit = false;
                    document.getElementById('modal-title').textContent = 'Tambah Template Baru';
                    openModal();
                });

                // --- Tutup Modal ---
                closeModalBtn.addEventListener('click', closeModal);

                // --- Edit Template ---
                document.querySelectorAll('.edit-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        isEdit = true;
                        const id = this.dataset.id;
                        const title = this.dataset.title;
                        const content = decodeHTMLEntities(this.dataset.content);

                        document.getElementById('template-id').value = id;
                        document.getElementById('template-title').value = title;
                        editor.setValue(content || '');
                        document.getElementById('modal-title').textContent = 'Edit Template';
                        openModal();
                    });
                });

                function decodeHTMLEntities(text) {
                    const textarea = document.createElement('textarea');
                    textarea.innerHTML = text;
                    return textarea.value;
                }

                // --- Simpan Template ---
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();

                    const id = document.getElementById('template-id').value;
                    const title = document.getElementById('template-title').value;
                    const content = editor.getValue();
                    const url = isEdit ? `/admin/templates/${id}` : `/admin/templates`;
                    const method = isEdit ? 'PUT' : 'POST';

                    const response = await fetch(url, {
                        method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            title,
                            content
                        }),
                    });

                    if (response.ok) {
                        location.reload();
                    } else {
                        alert('Gagal menyimpan data');
                    }
                });
            });
        </script>

        <style>
            .CodeMirror {
                font-size: 12px;
                /* ubah sesuai kebutuhan, default biasanya 13px */
                height: 400px;
                /* tetap untuk tinggi editor */
            }
        </style>
    @endpush
</x-layout>
