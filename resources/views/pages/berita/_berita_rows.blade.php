@forelse ($beritas as $berita)
    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
            <div class="flex items-center gap-4">
                <img src="{{ $berita->cover_image ? asset('storage/' . $berita->cover_image) : 'https://placehold.co/100x60/e2e8f0/e2e8f0' }}" 
                     alt="Cover" class="w-24 h-14 object-cover rounded-md flex-shrink-0">
                <span class="font-semibold">{{ $berita->title }}</span>
            </div>
        </th>
        <td class="px-6 py-4">{{ $berita->author->name ?? 'N/A' }}</td>
        <td class="px-6 py-4">
            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $berita->status == 'published' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' }}">
                {{ ucfirst($berita->status) }}
            </span>
        </td>
        <td class="px-6 py-4 text-center">
            <div class="flex items-center justify-center space-x-4">
                <a href="{{ route('admin.berita.edit', $berita) }}" class="font-medium text-indigo-600 dark:text-indigo-500 hover:underline" title="Edit">
                    <i class="bi bi-pencil-square text-base"></i>
                </a>
                <form action="{{ route('admin.berita.destroy', $berita) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus berita ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline" title="Hapus">
                        <i class="bi bi-trash text-base"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="text-center py-12">
            <p class="text-gray-500 dark:text-gray-400">Tidak ada berita yang ditemukan.</p>
        </td>
    </tr>
@endforelse
