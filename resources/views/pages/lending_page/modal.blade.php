<!-- Modal Create/Edit -->
<div id="lendingModal"
    class="fixed inset-0 hidden bg-black/10 backdrop-blur-sm flex items-center justify-center z-50 transition-all duration-300">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-6xl p-6 relative">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Tambah Section</h3>
            <button onclick="closeModal()"
                class="absolute top-4 right-4 text-red-800 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800 rounded-full w-8 h-8 flex items-center justify-center">&times;</button>
        </div>
        <form id="lendingForm" class="space-y-4">
            @csrf
            <input type="hidden" id="page_id">
            <div class="w-1/4">
                <select id="id_template" name="id_template"
                    class="w-full border rounded-lg p-2 mt-1 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                    <option value="">-- Pilih Template --</option>
                    @foreach (\App\Models\Template::all() as $t)
                        <option value="{{ $t->id }}">{{ $t->title ?? 'Template ' . $t->id }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Preview Template -->
            <div id="templatePreviewContainer" class="mt-4 max-h-screen hidden">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Preview:</p>
                <iframe id="templatePreview"
                    class="w-full h-[60vh] border border-gray-300 dark:border-gray-700 rounded-lg"></iframe>
            </div>

            <!-- Action -->
            <div class="flex justify-end mt-6 space-x-3">
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
<div id="orderModal"
    class="fixed inset-0 hidden bg-black/10 backdrop-blur-sm flex items-center justify-center z-50 transition-all duration-300">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-xl p-6 relative">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Daftar Section</h3>
            <button onclick="closeOrderModal()"
                class="absolute top-4 right-4 text-red-800 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800 rounded-full w-8 h-8 flex items-center justify-center">&times;</button>
        </div>

        <ul id="sectionList" class="space-y-2">
            @foreach ($pages as $index => $item)
                <input type="hidden" id="id_page" value="{{ $item->id }}">
                <li data-id="{{ $item->id }}"
                    class="flex justify-between items-center p-2 border rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 cursor-move">
                    <span>{{ $item->template->title ?? 'Section ' . $item->id }}</span>
                    <div class="flex gap-1">
                        <button onclick="moveUp({{ $item->id }})"
                            class="px-2 py-1 dark:bg-gray-600 bg-gray-200 rounded dark:hover:bg-indigo-600 hover:bg-indigo-400 dark:hover:text-white">↑</button>
                        <button onclick="moveDown({{ $item->id }})"
                            class="px-2 py-1 dark:bg-gray-600 bg-gray-200 rounded dark:hover:bg-red-600 hover:bg-red-400 dark:hover:text-white">↓</button>
                        <button onclick="deleteSection({{ $item->id }}, this)"
                            class="px-2 py-1 dark:bg-red-600 bg-red-500 rounded hover:bg-red-700 text-white">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="mt-4 flex justify-end gap-2">
            <button onclick="saveOrder()"
                class="px-4 py-2 text-sm font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition">Simpan</button>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        function openOrderModal() {
            document.getElementById('orderModal').classList.remove('hidden');
        }

        function closeOrderModal() {
            document.getElementById('orderModal').classList.add('hidden');
        }

        // Pindahkan section ke atas
        function moveUp(id) {
            const list = document.getElementById('sectionList');
            const item = list.querySelector(`li[data-id='${id}']`);
            if (item.previousElementSibling) {
                list.insertBefore(item, item.previousElementSibling);
            }
        }

        // Pindahkan section ke bawah
        function moveDown(id) {
            const list = document.getElementById('sectionList');
            const item = list.querySelector(`li[data-id='${id}']`);
            if (item.nextElementSibling) {
                list.insertBefore(item.nextElementSibling, item);
            }
        }

        // Simpan urutan baru ke server
        function saveOrder() {
            // const id = document.getElementById('id_page').value;
            const order = Array.from(document.querySelectorAll('#sectionList li')).map(li => li.dataset.id);
            const data = {
                id: document.getElementById('id_page').value,
                order: order,
            };
            fetch(`/admin/lending-page/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                })
                .then(res => {
                    if (res.success) {
                        alert('Urutan berhasil disimpan');
                        closeOrderModal();
                        // Reload iframe agar urutan terbaru tampil
                        document.getElementById('lendingIframe').contentWindow.location.reload();
                    } else {
                        alert('Gagal menyimpan urutan');
                    }
                })
                // .then(async res => {
                //     // const text = await res.text();
                //     // console.log("RESPONSE RAW:", text);

                //     try {
                //         // const data = JSON.parse(text);
                //         // console.log("JSON PARSED:", data);
                //         if (data.success) {
                //             alert("Urutan berhasil disimpan");
                //             closeOrderModal();
                //             document.getElementById("lendingIframe").contentWindow.location.reload();
                //         } else {
                //             alert(data.message || "Gagal menyimpan urutan");
                //         }
                //     } catch (e) {
                //         console.error("Bukan JSON valid:", e);
                //         alert("Server mengembalikan data tidak valid, cek konsol untuk detail.");
                //     }
                // })
                .catch(err => console.error("FETCH ERROR:", err));
        }

        function deleteSection(id, btn) {
            if (!confirm('Apakah Anda yakin ingin menghapus section ini?')) return;

            fetch(`/admin/lending-page/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Hapus li dari DOM
                        const li = btn.closest('li');
                        li.remove();
                    } else {
                        alert(data.message || 'Gagal menghapus section.');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Terjadi kesalahan saat menghapus section.');
                });
        }
    </script>
@endpush
