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
use App\Jobs\CompressGaleriItem;

class GaleriController extends Controller
{
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
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255|unique:galeris,judul',
            'keterangan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.type' => ['required', Rule::in(['image', 'video', 'video_url'])],
            'items.*.file' => 'required_if:items.*.type,image,video|file|mimes:jpg,jpeg,png,webp,mp4,mov,avi,wmv,JPG|max:51200', // Wajib jika tipe file
            'items.*.url' => 'required_if:items.*.type,video_url|url',
            'items.*.caption' => 'nullable|string|max:255',
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
                // 1. Buat record album Galeri utama
                $galeri = Galeri::create([
                    'judul' => $validatedData['judul'],
                    'keterangan' => $validatedData['keterangan'] ?? null,
                ]);

                $slug = Str::slug($galeri->judul);

                // 2. Loop dan simpan setiap item media
                foreach ($validatedData['items'] as $index => $itemData) {
                    $tipeFile = $itemData['type'];
                    $filePath = null;
                    $caption = $itemData['caption'] ?? null;
                    $itemToDispatch = null; // Variabel untuk job kompresi

                    if ($tipeFile === 'image' || $tipeFile === 'video') {
                        $fileInputKey = "items.{$index}.file"; // Key asli dari form
                        if ($request->hasFile($fileInputKey)) {
                            $file = $request->file($fileInputKey);
                            $shortCode = strtolower(Str::random(6));
                            $extension = $file->getClientOriginalExtension();
                            $filename = "{$slug}-{$index}-{$shortCode}.{$extension}";

                            // Simpan file asli (ukuran besar)
                            $filePath = $file->storeAs('galeri', $filename, 'public');
                        } else {
                            Log::error("File not found for index {$index} despite validation pass.");
                            continue;
                        }
                    } elseif ($tipeFile === 'video_url') {
                        // Simpan URL
                        $filePath = $itemData['url'];
                    }

                    if ($filePath) {
                        $newGaleriItem = $galeri->items()->create([
                            'file_path' => $filePath,
                            'tipe_file' => $tipeFile,
                            'caption' => $caption,
                        ]);

                        // Jika item adalah GAMBAR (bukan video), kirim ke antrian kompresi
                        if ($tipeFile === 'image') {
                            CompressGaleriItem::dispatch($newGaleriItem);
                        }
                    }
                }

                return $galeri;
            });

            // Muat relasi yang dibutuhkan untuk respons JSON
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

    /**
     * Mengambil data satu album galeri beserta item-itemnya untuk modal edit.
     */
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
            'items' => 'nullable|array',
            'items.*.type' => ['required', Rule::in(['image', 'video', 'video_url'])],
            'items.*.file' => 'required_if:items.*.type,image,video|file|mimes:jpg,jpeg,png,webp,mp4,mov,avi,wmv|max:51200',
            'items.*.url' => 'required_if:items.*.type,video_url|url',
            'items.*.caption' => 'nullable|string|max:255',
            'deleted_items' => 'nullable|array',
            'deleted_items.*' => 'integer|exists:galeri_items,id',
            'existing_captions' => 'nullable|array',
            'existing_captions.*' => 'nullable|string|max:255',
        ], [
            'items.*.file.required_if' => 'File media wajib diunggah untuk tipe Foto/Video baru.',
            'items.*.url.required_if' => 'URL Video wajib diisi untuk tipe Link Video baru.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        try {
            DB::transaction(function () use ($validatedData, $request, $galeri) {
                // 1. Update data album utama
                $galeri->update([
                    'judul' => $validatedData['judul'],
                    'keterangan' => $validatedData['keterangan'] ?? null,
                ]);

                $slug = Str::slug($galeri->judul);

                // 2. Hapus item yang ditandai
                if (!empty($validatedData['deleted_items'])) {
                    $itemsToDelete = GaleriItem::whereIn('id', $validatedData['deleted_items'])
                        ->where('galeri_id', $galeri->id)->get();
                    foreach ($itemsToDelete as $item) {
                        if ($item->tipe_file !== 'video_url' && $item->file_path) {
                            Storage::disk('public')->delete($item->file_path);
                        }
                        $item->delete();
                    }
                }

                // 3. Tambahkan item baru
                if (isset($validatedData['items'])) {
                    foreach ($validatedData['items'] as $index => $itemData) {
                        $tipeFile = $itemData['type'];
                        $filePath = null;
                        $caption = $itemData['caption'] ?? null;
                        $fileInputKey = "items.{$index}.file";

                        $itemToDispatch = null;

                        if ($tipeFile === 'image' || $tipeFile === 'video') {
                            if ($request->hasFile($fileInputKey)) {
                                $file = $request->file($fileInputKey);
                                $shortCode = strtolower(Str::random(6));
                                $extension = $file->getClientOriginalExtension();
                                $filename = "{$slug}-{$index}-{$shortCode}-" . time() . ".{$extension}";
                                // Simpan file asli
                                $filePath = $file->storeAs('galeri', $filename, 'public');
                            } else {
                                Log::error("Update: File not found for index {$index}.");
                                continue;
                            }
                        } elseif ($tipeFile === 'video_url') {
                            $filePath = $itemData['url'];
                        }

                        if ($filePath) {
                            $newGaleriItem = $galeri->items()->create([
                                'file_path' => $filePath,
                                'tipe_file' => $tipeFile,
                                'caption' => $caption,
                            ]);

                            // Jika item baru adalah GAMBAR, kirim ke antrian
                            if ($tipeFile === 'image') {
                                CompressGaleriItem::dispatch($newGaleriItem);
                            }
                        }
                    }
                }

                // 4. Update caption item yang ada
                if (isset($validatedData['existing_captions'])) {
                    foreach ($validatedData['existing_captions'] as $itemId => $caption) {
                        GaleriItem::where('id', $itemId)
                            ->where('galeri_id', $galeri->id)
                            ->update(['caption' => $caption]);
                    }
                }
            });

            // Muat ulang relasi untuk respons JSON
            $galeri->load('firstItem')->loadCount('items');

            return response()->json([
                'success' => true,
                'message' => 'Album galeri berhasil diperbarui!',
                'data' => $galeri
            ]);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menghapus album galeri.
     */
    public function destroy(Galeri $galeri)
    {
        try {
            DB::transaction(function () use ($galeri) {
                // 1. Hapus semua file fisik dari storage
                foreach ($galeri->items as $item) {
                    if ($item->tipe_file !== 'video_url' && $item->file_path) {
                        Storage::disk('public')->delete($item->file_path);
                    }
                }

                // 2. Hapus item dari database
                $galeri->items()->delete();

                // 3. Hapus album utama
                $galeri->delete();
            });

            return response()->json(['success' => true, 'message' => 'Album galeri berhasil dihapus!']);
        } catch (\Exception $e) {
            Log::error('Gagal menghapus galeri: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus album. Error: ' . $e->getMessage()
            ], 500);
        }
    }
}

