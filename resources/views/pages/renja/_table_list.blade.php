<div class="overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th class="px-6 py-3">OPD</th>
                <th class="px-6 py-3">Tahapan</th>
                <th class="px-6 py-3">Dokumen Naskah</th>
                <th class="px-6 py-3">Matriks Excel</th>
                <th class="px-6 py-3 text-center">Status Global & Feedback</th>
                <th class="px-6 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($renjas as $item)
                <tr
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                        {{ $item->opd->name }}
                        <div class="text-xs text-gray-500 mt-1">{{ $item->created_at->format('d M Y H:i') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span
                            class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded border border-gray-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                            {{ $item->tahapan->nama_tahapan }} {{ $item->tahapan->tahun }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button onclick="openDetailModal(this)" data-name="{{ $item->opd->name }} (Dokumen)"
                                data-status="{{ $item->status_dokumen }}"
                                data-file="{{ asset('storage/' . $item->file_dokumen_renja) }}"
                                data-admin-note="{{ $item->catatan_dokumen }}"
                                class="text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1 text-xs font-bold">
                                <i class="bi bi-file-pdf text-red-500 text-sm"></i> Lihat
                            </button>
                            @if ($item->status_dokumen == 'DISETUJUI')
                                <i class="bi bi-check-circle-fill text-green-500 text-xs" title="Sesuai"></i>
                            @elseif($item->status_dokumen == 'REVISI')
                                <i class="bi bi-exclamation-circle-fill text-red-500 text-xs" title="Revisi"></i>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button onclick="openDetailModal(this)" data-name="{{ $item->opd->name }} (Matriks)"
                                data-status="{{ $item->status_matriks }}"
                                data-file="{{ asset('storage/' . $item->file_matriks_renja) }}"
                                data-admin-note="{{ $item->catatan_matriks }}"
                                class="text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1 text-xs font-bold">
                                <i class="bi bi-file-earmark-excel text-green-600 text-sm"></i> Lihat
                            </button>
                            @if ($item->status_matriks == 'DISETUJUI')
                                <i class="bi bi-check-circle-fill text-green-500 text-xs" title="Sesuai"></i>
                            @elseif($item->status_matriks == 'REVISI')
                                <i class="bi bi-exclamation-circle-fill text-red-500 text-xs" title="Revisi"></i>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if ($item->status == 'MENUNGGU')
                            <span
                                class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-300 dark:bg-yellow-900 dark:text-yellow-300 dark:border-yellow-800">Menunggu</span>
                        @elseif($item->status == 'REVISI')
                            <span
                                class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-300 dark:bg-red-900 dark:text-red-300 dark:border-red-800">Perlu
                                Revisi</span>
                        @else
                            <span
                                class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-300 dark:bg-green-900 dark:text-green-300 dark:border-green-800">Disetujui</span>
                        @endif

                        @if ($item->file_dokumen_verifikasi || $item->file_matriks_verifikasi || $item->catatan_verifikasi)
                            <div
                                class="mt-3 text-left bg-gray-50 dark:bg-gray-700/50 p-2 rounded border border-gray-200 dark:border-gray-600 space-y-1">
                                <p class="text-[10px] font-bold text-gray-500 uppercase mb-1">Feedback Admin:</p>
                                @if ($item->file_dokumen_verifikasi)
                                    <a href="{{ asset('storage/' . $item->file_dokumen_verifikasi) }}" target="_blank"
                                        class="flex items-center gap-1 text-xs text-red-600 hover:underline"><i
                                            class="bi bi-file-earmark-word"></i> Koreksi Naskah</a>
                                @endif
                                @if ($item->file_matriks_verifikasi)
                                    <a href="{{ asset('storage/' . $item->file_matriks_verifikasi) }}" target="_blank"
                                        class="flex items-center gap-1 text-xs text-green-600 hover:underline"><i
                                            class="bi bi-file-earmark-excel"></i> Koreksi Matriks</a>
                                @endif
                                @if (!$item->file_dokumen_verifikasi && !$item->file_matriks_verifikasi && $item->catatan_verifikasi)
                                    <p class="text-xs text-gray-600 italic">
                                        "{{ Str::limit($item->catatan_verifikasi, 40) }}"</p>
                                @endif
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            @if (Auth::user()->role == 'admin' || Auth::user()->role == 'super_admin')
                                <button onclick="openVerifyModal({{ $item->id }}, '{{ $item->opd->name }}')"
                                    class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-xl"
                                    title="Verifikasi"><i class="bi bi-check-circle-fill"></i></button>
                            @endif
                            @if (Auth::user()->role == 'opd' &&
                                    $item->status == 'REVISI' &&
                                    isset($tahapanAktif) &&
                                    $tahapanAktif &&
                                    $tahapanAktif->id == $item->tahapan_id)
                                <button
                                    onclick="openUploadModal({{ $item->tahapan_id }}, '{{ $item->status_dokumen }}', '{{ $item->status_matriks }}')"
                                    class="text-orange-500 hover:text-orange-700 dark:text-orange-400 dark:hover:text-orange-300 text-xl"
                                    title="Upload Revisi"><i class="bi bi-pencil-square"></i></button>
                            @endif
                            @if ($item->histories->count() > 0)
                                <button onclick="openHistoryModal({{ $item->id }})"
                                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white text-2xl"
                                    title="Riwayat"><i class="bi bi-clock-history"></i></button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-8 text-gray-500 dark:text-gray-400">Belum ada dokumen
                        Renja.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="p-4 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">{{ $renjas->links() }}</div>
