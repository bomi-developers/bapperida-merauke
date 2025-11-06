@forelse ($galeris as $album)
    <tr id="galeri-row-{{ $album->id }}"
        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
        <td class="px-6 py-4">
            @if ($album->firstItem)
                @if ($album->firstItem->tipe_file == 'image')
                    <img src="{{ asset('storage/' . $album->firstItem->file_path) }}" alt="Cover"
                        class="w-16 h-12 object-cover rounded-md">
                @elseif($album->firstItem->tipe_file == 'video' || $album->firstItem->tipe_file == 'video_url')
                    <div class="w-16 h-12 bg-gray-700 rounded-md flex items-center justify-center">
                        <i class="bi bi-film text-2xl text-gray-400"></i>
                    </div>
                @endif
            @else
                <div class="w-16 h-12 bg-gray-200 dark:bg-gray-700 rounded-md flex items-center justify-center">
                    <i class="bi bi-image-alt text-2xl text-gray-400 dark:text-gray-500"></i>
                </div>
            @endif
        </td>
        <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
            {{ $album->judul }}</th>
        <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $album->items_count }} Item
        </td>
        <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
            {{ $album->created_at->format('d M Y') }}</td>

        <td class="px-6 py-4">
            @if ($album->is_highlighted)
                <span
                    class="px-2 py-1 text-xs font-medium rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300">
                    Pilihan
                </span>
            @else
                <span
                    class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                    Normal
                </span>
            @endif
        </td>

        <td class="px-6 py-4 text-center">
            <div class="flex items-center justify-center space-x-4">
                <button class="show-btn font-medium text-green-600 dark:text-green-500 hover:underline" title="Detail"
                    data-id="{{ $album->id }}"><i class="bi bi-eye-fill text-base"></i></button>
                <button class="edit-btn font-medium text-indigo-600 dark:text-indigo-500 hover:underline" title="Edit"
                    data-id="{{ $album->id }}">
                    <i class="bi bi-pencil-square text-base"></i>
                </button>
                <button class="delete-btn font-medium text-red-600 dark:text-red-500 hover:underline" title="Hapus"
                    data-id="{{ $album->id }}">
                    <i class="bi bi-trash text-base"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr id="no-data-row">
        <td colspan="6" class="text-center py-12">
            <p class="text-gray-500 dark:text-gray-400">Belum ada album galeri yang ditambahkan atau cocok dengan
                pencarian Anda.</p>
        </td>
    </tr>
@endforelse
