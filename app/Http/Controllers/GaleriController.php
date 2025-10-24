<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use App\Models\GaleriItem;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GaleriController extends Controller
{
    // ... (index, show, destroy methods remain the same) ...
    public function index()
    {
        $galeris = Galeri::with('firstItem')->withCount('items')->latest()->get();
        return view('pages.galeri.index', compact('galeris'));
    }

    /**
     * Menyimpan album galeri baru beserta item-item medianya.
     */
    public function store(Request $request)
    {
        // Tambahkan validasi untuk URL
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255|unique:galeris,judul',
            'keterangan' => 'nullable|string',
            'items' => 'required|array|min:1', // Ubah dari 'files' ke 'items'
            'items.*.type' => ['required', Rule::in(['image', 'video', 'video_url'])],
            'items.*.file' => 'required_if:items.*.type,image,video|file|mimes:jpg,jpeg,png,webp,mp4,mov,avi,wmv|max:51200', // Wajib jika tipe file
            'items.*.url' => 'required_if:items.*.type,video_url|url', // Wajib jika tipe URL
            'items.*.caption' => 'nullable|string|max:255', // Caption opsional
        ], [
            'items.required' => 'Minimal tambahkan satu item media.',
            'items.*.file.required_if' => 'File media wajib diunggah untuk tipe Foto/Video.',
            'items.*.url.required_if' => 'URL Video wajib diisi untuk tipe Link Video.',
            'items.*.url.url' => 'Format URL Video tidak valid.',
            'items.*.file.mimes' => 'Format file tidak didukung.',
            'items.*.file.max' => 'Ukuran file tidak boleh melebihi 50MB.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        try {
            $galeri = DB::transaction(function () use ($validatedData, $request) {
                $galeri = Galeri::create([
                    'judul' => $validatedData['judul'],
                    'keterangan' => $validatedData['keterangan'] ?? null,
                ]);

                $slug = Str::slug($galeri->judul);

                foreach ($validatedData['items'] as $index => $itemData) {
                    $tipeFile = $itemData['type'];
                    $filePath = null;
                    $caption = $itemData['caption'] ?? null;

                    if ($tipeFile === 'image' || $tipeFile === 'video') {
                        // Handle file upload
                        $fileInputKey = "items.{$index}.file"; // Gunakan key asli dari form
                        if ($request->hasFile($fileInputKey)) {
                            $file = $request->file($fileInputKey);
                            $shortCode = strtolower(Str::random(6));
                            $extension = $file->getClientOriginalExtension();
                            $filename = "{$slug}-{$index}-{$shortCode}.{$extension}";
                            $filePath = $file->storeAs('galeri', $filename, 'public');
                        } else {
                            // Ini seharusnya tidak terjadi karena validasi, tapi sebagai fallback
                            Log::error("File not found for index {$index} despite validation pass."); // Logging error
                            continue;
                        }
                    } elseif ($tipeFile === 'video_url') {
                        // Simpan URL
                        $filePath = $itemData['url'];
                    }

                    if ($filePath) {
                        $galeri->items()->create([
                            'file_path' => $filePath,
                            'tipe_file' => $tipeFile,
                            'caption' => $caption,
                        ]);
                    }
                }

                return $galeri;
            });

            $galeri->load('firstItem')->loadCount('items');

            return response()->json([
                'success' => true,
                'message' => 'Album galeri berhasil ditambahkan!',
                'data' => $galeri
            ]);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Galeri $galeri)
    {
        $galeri->load('items');
        return response()->json($galeri);
    }

    /**
     * Mengupdate album galeri.
     */
    public function update(Request $request, Galeri $galeri)
    {
        $validator = Validator::make($request->all(), [
            'judul' => ['required', 'string', 'max:255', Rule::unique('galeris')->ignore($galeri->id)],
            'keterangan' => 'nullable|string',
            // Item baru (opsional saat update)
            'items' => 'nullable|array',
            'items.*.type' => ['required', Rule::in(['image', 'video', 'video_url'])],
            'items.*.file' => 'required_if:items.*.type,image,video|file|mimes:jpg,jpeg,png,webp,mp4,mov,avi,wmv|max:51200',
            'items.*.url' => 'required_if:items.*.type,video_url|url',
            'items.*.caption' => 'nullable|string|max:255',
            // Item lama yang dihapus
            'deleted_items' => 'nullable|array',
            'deleted_items.*' => 'integer|exists:galeri_items,id',
            // Caption item lama yang mungkin diupdate
            'existing_captions' => 'nullable|array',
            'existing_captions.*' => 'nullable|string|max:255', // Key adalah item ID
        ], [
            // ... pesan validasi ...
            'items.*.file.required_if' => 'File media wajib diunggah untuk tipe Foto/Video baru.',
            'items.*.url.required_if' => 'URL Video wajib diisi untuk tipe Link Video baru.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        try {
            DB::transaction(function () use ($validatedData, $request, $galeri) {
                $galeri->update([
                    'judul' => $validatedData['judul'],
                    'keterangan' => $validatedData['keterangan'] ?? null,
                ]);

                $slug = Str::slug($galeri->judul);

                // Hapus item yang ditandai
                if (!empty($validatedData['deleted_items'])) {
                    $itemsToDelete = GaleriItem::whereIn('id', $validatedData['deleted_items'])
                        ->where('galeri_id', $galeri->id)->get();
                    foreach ($itemsToDelete as $item) {
                        // Hanya hapus file jika bukan URL
                        if ($item->tipe_file !== 'video_url' && $item->file_path) {
                            Storage::disk('public')->delete($item->file_path);
                        }
                        $item->delete();
                    }
                }

                // Tambahkan item baru
                if (isset($validatedData['items'])) {
                    foreach ($validatedData['items'] as $index => $itemData) {
                        $tipeFile = $itemData['type'];
                        $filePath = null;
                        $caption = $itemData['caption'] ?? null;
                        $fileInputKey = "items.{$index}.file"; // Key asli dari form

                        if ($tipeFile === 'image' || $tipeFile === 'video') {
                            if ($request->hasFile($fileInputKey)) {
                                $file = $request->file($fileInputKey);
                                $shortCode = strtolower(Str::random(6));
                                $extension = $file->getClientOriginalExtension();
                                $filename = "{$slug}-{$index}-{$shortCode}-" . time() . ".{$extension}";
                                $filePath = $file->storeAs('galeri', $filename, 'public');
                            } else {
                                Log::error("Update: File not found for index {$index}.");
                                continue;
                            }
                        } elseif ($tipeFile === 'video_url') {
                            $filePath = $itemData['url'];
                        }

                        if ($filePath) {
                            $galeri->items()->create([
                                'file_path' => $filePath,
                                'tipe_file' => $tipeFile,
                                'caption' => $caption,
                            ]);
                        }
                    }
                }

                // Update caption item yang ada
                if (isset($validatedData['existing_captions'])) {
                    foreach ($validatedData['existing_captions'] as $itemId => $caption) {
                        GaleriItem::where('id', $itemId)
                            ->where('galeri_id', $galeri->id) // Pastikan milik album ini
                            ->update(['caption' => $caption]);
                    }
                }
            });

            $galeri->load('firstItem')->loadCount('items');

            return response()->json([
                'success' => true,
                'message' => 'Album galeri berhasil diperbarui!',
                'data' => $galeri
            ]);
        } catch (\Exception $e) { /* error handling */
        }
    }

    // ... (destroy method - perlu update sedikit) ...
    public function destroy(Galeri $galeri)
    {
        try {
            DB::transaction(function () use ($galeri) {
                // 1. Hapus semua file terkait dari storage
                foreach ($galeri->items as $item) {
                    // Hapus file HANYA jika bukan URL
                    if ($item->tipe_file !== 'video_url' && $item->file_path) {
                        Storage::disk('public')->delete($item->file_path);
                    }
                }

                // PERBAIKAN: Hapus semua item (child records) dari database secara manual
                // Ini diperlukan karena onDelete('cascade') tidak aktif di database Anda
                $galeri->items()->delete();

                // 2. Hapus record album (parent record)
                // Sekarang ini akan berhasil karena semua child record sudah dihapus
                $galeri->delete();
            });

            // Kirim respons JSON sukses
            return response()->json(['success' => true, 'message' => 'Album galeri berhasil dihapus!']);
        } catch (\Exception $e) {
            // PERBAIKAN: Kembalikan respons error dalam format JSON
            Log::error('Gagal menghapus galeri: ' . $e->getMessage()); // Catat error di log
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus album. Error: ' . $e->getMessage()
            ], 500); // Kirim status 500 (Internal Server Error)
        }
    }
}
