@forelse ($deletedGaleris as $galeri)
    <tr
        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
        <td class="px-6 py-4">
            <div class="flex items-center gap-4">
                @if ($galeri->firstItem)
                    @if ($galeri->firstItem->tipe_file == 'image')
                        <img src="{{ asset('storage/' . $galeri->firstItem->file_path) }}" alt="Cover"
                            class="w-16 h-12 object-cover rounded-md">
                    @elseif($galeri->firstItem->tipe_file == 'video' || $galeri->firstItem->tipe_file == 'video_url')
                        <div class="w-16 h-12 bg-gray-700 rounded-md flex items-center justify-center">
                            <i class="bi bi-film text-2xl text-gray-400"></i>
                        </div>
                    @endif
                @else
                    <div class="w-16 h-12 bg-gray-200 dark:bg-gray-700 rounded-md flex items-center justify-center">
                        <i class="bi bi-image-alt text-2xl text-gray-400 dark:text-gray-500"></i>
                    </div>
                @endif
                <div>
                    <div class="text-base font-semibold text-gray-900 dark:text-white line-clamp-1"
                        title="{{ $galeri->judul }}">
                        {{ $galeri->judul }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Dihapus: {{ $galeri->deleted_at->format('d M Y H:i') }}
                    </div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 text-center">
            <div class="flex items-center justify-center gap-2">
                <button type="button"
                    class="btn-restore p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors dark:text-green-400 dark:hover:bg-green-900/30"
                    title="Pulihkan" data-id="{{ $galeri->id }}">
                    <i class="bi bi-arrow-counterclockwise text-lg"></i>
                </button>
                <button type="button"
                    class="btn-force-delete p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors dark:text-red-400 dark:hover:bg-red-900/30"
                    title="Hapus Permanen" data-id="{{ $galeri->id }}">
                    <i class="bi bi-trash-fill text-lg"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="2" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
            <div class="flex flex-col items-center justify-center gap-2">
                <i class="bi bi-trash text-4xl mb-2 text-gray-300 dark:text-gray-600"></i>
                <p>Tempat sampah kosong</p>
            </div>
        </td>
    </tr>
@endforelse
