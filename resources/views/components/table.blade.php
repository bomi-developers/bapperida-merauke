@props(['headers' => [], 'rows' => []])

<div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
    <table class="min-w-full text-sm text-left text-gray-600 dark:text-gray-300">
        <thead class="bg-gray-100 dark:bg-gray-800">
            <tr>
                @foreach ($headers as $header)
                    <th class="px-5 py-3 font-semibold uppercase tracking-wide text-xs text-gray-700 dark:text-gray-400">
                        {{ $header }}
                    </th>
                @endforeach
                <th
                    class="px-5 py-3 text-center font-semibold uppercase tracking-wide text-xs text-gray-700 dark:text-gray-400">
                    Aksi
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr
                    class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900 transition">
                    @foreach ($row as $cell)
                        <td class="px-5 py-3">
                            {{ $cell }}
                        </td>
                    @endforeach
                    <td class="px-5 py-3 text-center">
                        <button onclick="editData('{{ $row['id'] ?? '' }}')"
                            class="inline-flex items-center px-2 py-1.5 text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                            Edit
                        </button>
                        <button onclick="deleteData('{{ $row['id'] ?? '' }}')"
                            class="inline-flex items-center px-2 py-1.5 text-xs font-medium text-red-600 dark:text-red-400 hover:underline">
                            Hapus
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($headers) + 1 }}"
                        class="px-5 py-4 text-center text-gray-500 dark:text-gray-400">
                        Tidak ada data ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
{{-- contoh
<x-table 
    :headers="['No', 'Nama Bidang', 'Keterangan']" 
    :rows="$bidangs->map(fn($b, $i) => [
        'id' => $b->id,
        $i + 1,
        $b->nama_bidang,
        $b->keterangan
    ])" 
/>
--}}
