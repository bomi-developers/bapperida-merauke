<div class="overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th class="px-6 py-3">Pengirim</th>
                <th class="px-6 py-3">Periode</th>
                <th class="px-6 py-3">File</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Catatan</th>
                <th class="px-6 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laporans as $item)
                <tr
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                    {{-- Pengirim --}}
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900 dark:text-white">{{ $item->user->name }}</div>
                    </td>

                    {{-- Periode --}}
                    <td class="px-6 py-4">
                        <span
                            class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                            TW {{ $item->period->triwulan }} / {{ $item->period->tahun }}
                        </span>
                    </td>

                    {{-- File --}}
                    <td class="px-6 py-4">
                        <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank"
                            class="text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                            <i class="bi bi-file-earmark-text"></i> File
                        </a>
                    </td>

                    {{-- Status --}}
                    <td class="px-6 py-4">
                        @if ($item->status == 'MENUNGGU')
                            <span
                                class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800">Menunggu</span>
                        @elseif($item->status == 'REVISI')
                            <span
                                class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300 border border-red-200 dark:border-red-800">Revisi</span>
                        @else
                            <span
                                class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300 border border-green-200 dark:border-green-800">Disetujui</span>
                        @endif
                    </td>

                    {{-- Catatan --}}
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-2 max-w-[200px]">
                            @if ($item->keterangan_opd)
                                <div
                                    class="text-xs bg-indigo-50 dark:bg-indigo-900/20 p-1.5 rounded border-l-2 border-indigo-400">
                                    <span
                                        class="font-bold text-indigo-700 dark:text-indigo-300 block mb-0.5">OPD:</span>
                                    <span
                                        class="text-gray-600 dark:text-gray-300 italic">"{{ Str::limit($item->keterangan_opd, 30) }}"</span>
                                </div>
                            @endif
                            @if ($item->catatan_admin)
                                <div
                                    class="text-xs bg-red-50 dark:bg-red-900/20 p-1.5 rounded border-l-2 border-red-400">
                                    <span class="font-bold text-red-700 dark:text-red-300 block mb-0.5">Admin:</span>
                                    <span
                                        class="text-gray-600 dark:text-gray-300">"{{ Str::limit($item->catatan_admin, 30) }}"</span>
                                </div>
                            @endif
                            @if (!$item->keterangan_opd && !$item->catatan_admin)
                                <span class="text-gray-400">-</span>
                            @endif
                        </div>
                    </td>

                    {{-- Aksi --}}
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center space-x-2">
                            {{-- Detail (Mata) --}}
                            <button onclick="openDetailModal(this)" data-name="{{ $item->user->name }}"
                                data-triwulan="{{ $item->period->triwulan }}" data-tahun="{{ $item->period->tahun }}"
                                data-start="{{ date('d M Y', strtotime($item->period->start_date)) }}"
                                data-end="{{ date('d M Y', strtotime($item->period->end_date)) }}"
                                data-status="{{ $item->status }}"
                                data-file="{{ asset('storage/' . $item->file_path) }}"
                                data-opd-note="{{ $item->keterangan_opd ?? '-' }}"
                                data-admin-note="{{ $item->catatan_admin ?? '-' }}"
                                class="text-indigo-500 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-200"
                                title="Detail">
                                <i class="bi bi-eye-fill text-lg"></i>
                            </button>

                            @if (Auth::user()->role == 'admin' || Auth::user()->role == 'super_admin')
                                <button onclick="openVerifyModal({{ $item->id }}, '{{ $item->user->name }}')"
                                    class="text-blue-600 dark:text-blue-500 hover:underline"><i
                                        class="bi bi-check-circle-fill text-lg"></i></button>
                            @elseif(Auth::user()->role == 'opd' && $item->status == 'REVISI')
                                <button onclick="openUploadModal('revisi', {{ $item->id }})"
                                    class="text-orange-500 dark:text-orange-400 hover:underline"><i
                                        class="bi bi-pencil-square text-lg"></i></button>
                            @endif

                            @if ($item->histories->count() > 0)
                                <button onclick="openHistoryModal({{ $item->id }})"
                                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white"
                                    title="Riwayat">
                                    <i class="bi bi-clock-history text-lg"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-8 text-gray-500 dark:text-gray-400">Tidak ada data
                        ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
<div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
    {{ $laporans->links() }}
</div>
