@forelse ($documents as $doc)
    <tr class="hover:bg-blue-50/50">
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
            <div class="flex items-center gap-4">
                <img class="h-16 w-16 object-cover rounded-md flex-shrink-0"
                    src="{{ $doc->cover ? asset('storage/' . $doc->cover) : 'https://placehold.co/150x150/e2e8f0/e2e8f0?text=Doc' }}"
                    alt="Cover {{ $doc->judul }}">
                <span class="font-semibold">{{ $doc->judul }}</span>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-normal text-sm text-gray-600 max-w-xs">
            {{ Str::limit(data_get($doc->lainnya, 'visi', '-'), 100) }}
        </td>
        <td class="px-6 py-4 whitespace-normal text-sm text-gray-600 max-w-xs">
            {{-- Menampilkan poin pertama dari Misi jika ada --}}
            {{ Str::limit(data_get($doc->lainnya, 'misi.0', '-'), 100) }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
            {{ $doc->created_at->translatedFormat('d F Y') }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium space-x-2">
            <button type="button"
                class="review-btn p-2 rounded-full text-green-600 hover:bg-green-100 dark:hover:bg-green-900/50 transition-colors"
                data-id="{{ $doc->id }}" title="Lihat Detail">
                <i class="bi bi-eye-fill text-lg"></i>
            </button>
            <a href="{{ route('admin.documents.download', $doc->id) }}"
                class="inline-block p-2 rounded-full text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors"
                title="Unduh">
                <i class="bi bi-download text-lg"></i>
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center py-16">
            <i class="bi bi-folder-x text-5xl text-gray-400"></i>
            <p class="mt-4 text-lg font-semibold text-gray-600">Dokumen Tidak Ditemukan</p>
            <p class="text-gray-500">Tidak ada dokumen yang cocok dengan filter atau pencarian Anda.</p>
        </td>
    </tr>
@endforelse