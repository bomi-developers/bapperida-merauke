<!-- Modal -->
<div id="formModal" class="fixed inset-0 hidden bg-black/50 flex items-center justify-center z-50 backdrop-blur">
    <div class="bg-white dark:bg-boxdark p-6 rounded shadow w-1/2 max-w-3xl relative">
        <h3 id="modalTitle" class="text-lg font-bold mb-4 dark:text-white">Tambah Berita</h3>

        <form id="formBerita">
            @csrf
            <input type="hidden" id="berita_id" name="id">

            <!-- Judul -->
            <div class="mb-3">
                <label for="judul" class="text-sm dark:text-white">Judul</label>
                <input type="text" id="judul" name="judul"
                    class="w-full px-3 py-2 border rounded dark:border-strokedark 
                           dark:bg-boxdark dark:text-white focus:ring-2 focus:ring-primary outline-none">
            </div>

            <!-- Items -->
            <div class="mb-3">
                <label class="text-sm dark:text-white">Konten</label>
                <div id="itemsContainer" class="space-y-3 mt-2">
                    <div class="item">
                        <select name="items[0][type]"
                            class="w-full px-3 py-2 border rounded dark:border-strokedark 
                                   dark:bg-boxdark dark:text-white focus:ring-2 focus:ring-primary outline-none">
                            <option value="text">Text</option>
                            <option value="image">Image URL</option>
                            <option value="video">Video URL</option>
                            <option value="embed">Embed</option>
                        </select>
                        <textarea name="items[0][content]" placeholder="Isi konten"
                            class="w-full mt-2 px-3 py-2 border rounded dark:border-strokedark 
                                   dark:bg-boxdark dark:text-white focus:ring-2 focus:ring-primary outline-none"></textarea>
                    </div>
                </div>
                <button type="button" onclick="addItem()"
                    class="mt-3 px-3 py-1 bg-primary text-white rounded hover:bg-primary/80">
                    + Tambah Item
                </button>
            </div>

            <!-- Action buttons -->
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeForm()" class="px-3 py-1 bg-danger text-white rounded">
                    Batal
                </button>
                <button type="submit" class="px-3 py-1 bg-primary text-white rounded">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    let counter = 1;

    function addItem() {
        const container = document.getElementById('itemsContainer');
        const div = document.createElement('div');
        div.classList.add('item', 'mb-2');
        div.innerHTML = `
        <select name="items[${counter}][type]" class="border rounded p-1">
            <option value="text">Text</option>
            <option value="image">Image URL</option>
            <option value="video">Video URL</option>
            <option value="embed">Embed</option>
        </select>
        <textarea name="items[${counter}][content]" placeholder="Isi konten" class="border rounded w-full p-2 mt-1"></textarea>
    `;
        container.appendChild(div);
        counter++;
    }

    function openCreateForm() {
        document.getElementById('modalTitle').innerText = "Tambah Berita";
        document.getElementById('berita_id').value = "";
        document.getElementById('judul').value = "";
        document.getElementById('itemsContainer').innerHTML = `
        <div class="item mb-2">
            <select name="items[0][type]" class="border rounded p-1">
                <option value="text">Text</option>
                <option value="image">Image URL</option>
                <option value="video">Video URL</option>
                <option value="embed">Embed</option>
            </select>
            <textarea name="items[0][content]" placeholder="Isi konten" class="border rounded w-full p-2 mt-1"></textarea>
        </div>`;
        counter = 1;
        document.getElementById('formModal').classList.remove('hidden');
    }

    function closeForm() {
        document.getElementById('formModal').classList.add('hidden');
    }
</script>
