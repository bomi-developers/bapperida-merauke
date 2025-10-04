<script>
    let pageUrl = "{{ route('admin.berita.data') }}";

    function loadData(url = pageUrl) {
        fetch(url + "?search=" + document.getElementById('search').value)
            .then(res => res.json())
            .then(res => {
                let html = `<table class="w-full table-auto">
                        <thead>
                            <tr class="bg-gray-2 text-left dark:bg-meta-4">
                                <th class="px-4 py-4 font-medium">Judul</th>
                                <th class="px-4 py-4 font-medium">Isi Singkat</th>
                                <th class="px-4 py-4 font-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>`;
                if (res.data.length) {
                    res.data.forEach(b => {
                        html += `<tr>
                    <td class="border px-4 py-2">${b.title}</td>
                    <td class="border px-4 py-2">${b.items.length ? b.items[0].content.substring(0, 50) + '...' : '-'}</td>
                    <td class="border px-4 py-2">
                        <button onclick="editBerita(${b.id})" class="hover:text-primary">Edit</button>
                        <button onclick="deleteBerita(${b.id})" class="hover:text-red-500">Hapus</button>
                    </td>
                </tr>`;
                    });
                } else {
                    html += `<tr><td colspan="3" class="text-center py-4">Tidak ada data</td></tr>`;
                }
                html += `</tbody></table>`;

                // Pagination
                html += `<div class="mt-3 flex gap-2">`;
                res.links.forEach(link => {
                    if (link.url) {
                        html += `<button onclick="loadData('${link.url}')" 
                            class="px-3 py-1 rounded ${link.active ? 'bg-primary text-white' : 'bg-gray-200 dark:bg-gray-700 text-black dark:text-white'}">
                            ${link.label}
                        </button>`;
                    }
                });
                html += `</div>`;

                document.getElementById('berita-table').innerHTML = html;
            });
    }

    document.getElementById('search').addEventListener('input', () => loadData());
    loadData();
</script>
