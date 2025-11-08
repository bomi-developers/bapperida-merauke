{{-- 
  PERBAIKAN: 
  Mengganti CSS Grid dengan CSS Columns (layout Masonry/Pinterest).
  - 'columns-2 md:columns-4' menentukan jumlah kolom.
  - 'gap-4' memberi jarak antar kolom.
--}}

@if ($items->isEmpty())
    {{-- Pesan 'Tidak Ada Item' ini tidak akan berfungsi baik di dalam layout kolom, 
         jadi kita letakkan di luar dengan @if sederhana --}}
    <div class="text-center py-16 bg-white rounded-xl shadow-lg">
        <i class="bi bi-search text-6xl text-gray-400"></i>
        <h3 class="mt-4 text-2xl font-semibold text-gray-700">Tidak Ada Item</h3>
        <p class="mt-2 text-gray-500">Tidak ada item yang cocok dengan filter ini.</p>
    </div>
@else

<div class="columns-2 md:columns-4 gap-4">
        @foreach ($items as $item)
            @php
                // Logika $spanClass sudah dihapus karena kita pakai layout Masonry

                // PERBAIKAN: Tentukan $title dan $captionFallback dengan lebih aman
                // $galeri dikirim dari controller (baik showPublic maupun filterItems)
                $captionFallback = $galeri->judul ?? 'Item Galeri';
            @endphp

            {{-- 
              PERBAIKAN: 
              - div pembungkus sekarang menjadi 'card' dengan bg-white, shadow, dan rounded.
              - 'break-inside-avoid' sangat penting agar item tidak terpotong pindah kolom.
            --}}
            <div class="mb-4 break-inside-avoid bg-white rounded-lg shadow-lg overflow-hidden group">
                {{-- 
                  Link <a> sekarang membungkus media.
                --}}
                <a href="javascript:void(0);" class="open-viewer-btn block relative bg-gray-900"
                    data-tipe="{{ $item->tipe_file }}" data-path="{{ $item->file_path }}" {{-- PERBAIKAN: Gunakan $captionFallback --}}
                    data-caption="{{ $item->caption ?? $captionFallback }}">

                    @if ($item->tipe_file == 'image')
                        {{-- 
                          PERBAIKAN: 
                          - Menghapus 'h-full' dan 'object-cover'.
                          - Menggunakan 'w-full h-auto' agar gambar mempertahankan rasio aspek aslinya.
                        --}}
                        <img src="{{ asset('storage/' . $item->file_path) }}" alt="{{ $item->caption }}"
                            class="w-full h-auto transition-transform duration-300 group-hover:scale-105">
                    @elseif($item->tipe_file == 'video')
                        {{-- Video tetap menggunakan aspect-video agar terlihat rapi --}}
                        <div class="w-full aspect-video flex items-center justify-center bg-black">
                            <i class="bi bi-film text-6xl text-gray-500"></i>
                            <i
                                class="bi bi-play-circle-fill text-white text-5xl absolute z-10 opacity-70 group-hover:opacity-100 transition-opacity"></i>
                        </div>
                    @elseif($item->tipe_file == 'video_url')
                        <div class="w-full aspect-video flex items-center justify-center bg-black">
                            <i class="bi bi-youtube text-6xl text-red-600/80"></i>
                            <i
                                class="bi bi-play-circle-fill text-white text-5xl absolute z-10 opacity-70 group-hover:opacity-100 transition-opacity"></i>
                        </div>
                    @endif

                    {{-- Overlay (opsional, bisa dihapus jika ada teks di bawah) --}}
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        @if ($item->caption)
                            <p class="absolute bottom-4 left-4 text-white font-semibold text-sm">{{ $item->caption }}
                            </p>
                        @endif
                    </div>
                </a>

                {{-- PERBAIKAN: Tambahkan bagian teks (caption) di bawah media --}}
                @if ($item->caption)
                    <div class="p-3">
                        <p class="text-sm text-gray-700 truncate" title="{{ $item->caption }}">
                            {{ $item->caption }}
                        </p>
                    </div>
                @endif
            </div>
        @endforeach
    @endif
</div>
