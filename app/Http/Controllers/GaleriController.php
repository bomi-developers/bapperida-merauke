<?php

namespace App\Http\Controllers; // Sesuai dengan file Anda

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
use App\Jobs\CompressGaleriItem; // Pastikan ini di-import

class GaleriController extends Controller
{

    /**
     * Menampilkan halaman manajemen galeri (album).
     */
    public function index(Request $request)
    {
        $query = Galeri::with('firstItem')->withCount('items');

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where('judul', 'like', $searchTerm . '%');
        }

        $galeris = $query->latest()->paginate(10);

        return view('pages.galeri.index', compact('galeris'));
    }
    public function getData(Request $request)
    {
        $query = Galeri::with('firstItem')->withCount('items');

        if ($request->search) {
            $query->where('judul', 'like', '%' . $request->search . '%')
                ->orWhere('keterangan', 'like', '%' . $request->search . '%');
        }
        $galeri = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json($galeri);
    }

    /**
     * Menyimpan album galeri baru beserta item-item medianya.
     */
    public function store(Request $request)
    {
        // Validasi untuk item dinamis
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255|unique:galeris,judul',
            'keterangan' => 'nullable|string',
            'is_highlighted' => 'nullable|boolean', // Validasi untuk highlight
            'items' => 'required|array|min:1', // Harus ada minimal 1 item
            'items.*.type' => ['required', Rule::in(['image', 'video', 'video_url'])],
            'items.*.file' => 'required_if:items.*.type,image,video|file|mimes:jpg,jpeg,png,webp,mp4,mov,avi,wmv,JPG|max:51200', // Wajib jika tipe file
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
                // 1. Buat record album Galeri utama
                $galeri = Galeri::create([
                    'judul' => $validatedData['judul'],
                    'keterangan' => $validatedData['keterangan'] ?? null,
                    'is_highlighted' => $request->has('is_highlighted'), // Simpan status highlight
                ]);

                $slug = Str::slug($galeri->judul);

                // 2. Loop dan simpan setiap item media
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

                        // Jika item adalah GAMBAR, kirim ke antrian kompresi
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
        return response()->json($galeri); // Model 'Galeri' sudah punya $casts['is_highlighted']
    }

    /**
     * Mengupdate album galeri.
     */
    public function update(Request $request, Galeri $galeri)
    {
        $validator = Validator::make($request->all(), [
            'judul' => ['required', 'string', 'max:255', Rule::unique('galeris')->ignore($galeri->id)],
            'keterangan' => 'nullable|string',
            'is_highlighted' => 'nullable|boolean', // Validasi untuk highlight
            'items' => 'nullable|array', // Item baru opsional saat update
            'items.*.type' => ['required', Rule::in(['image', 'video', 'video_url'])],
            'items.*.file' => 'required_if:items.*.type,image,video|file|mimes:jpg,jpeg,png,webp,mp4,mov,avi,wmv|max:51200',
            'items.*.url' => 'required_if:items.*.type,video_url|url',
            'items.*.caption' => 'nullable|string|max:255',
            'deleted_items' => 'nullable|array',
            'deleted_items.*' => 'integer|exists:galeri_items,id',
            'existing_captions' => 'nullable|array',
            'existing_captions.*' => 'nullable|string|max:255', // Key adalah item ID
        ], [
            'items.*.file.required_if' => 'File media wajib diunggah untuk tipe Foto/Video baru.',
            'items.*.url.required_if' => 'URL Video wajib diisi untuk tipe Link Video baru.',
            'items.*.file.max' => 'Ukuran file tidak boleh melebihi 50MB.',
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
                    'is_highlighted' => $request->has('is_highlighted'), // Simpan status highlight
                ]);

                $slug = Str::slug($galeri->judul);

                // 2. Hapus item yang ditandai
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

                // 3. Tambahkan item baru
                if (isset($validatedData['items'])) {
                    foreach ($validatedData['items'] as $index => $itemData) {
                        $tipeFile = $itemData['type'];
                        $filePath = null;
                        $caption = $itemData['caption'] ?? null;
                        $fileInputKey = "items.{$index}.file"; // Key asli dari form

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
                            ->where('galeri_id', $galeri->id) // Pastikan milik album ini
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

                // 2. Hapus item dari database (seharusnya otomatis jika cascade, tapi kita lakukan manual)
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

    /**
     * Menampilkan halaman utama galeri publik.
     */
    public function indexPublic(Request $request)
    {
        // 1. Data untuk Carousel (Ambil 5 album yang di-highlight)
        $galeriPopuler = Galeri::with('firstItem')
            ->where('is_highlighted', true) // Ambil yang di-highlight
            ->latest()
            ->take(5)
            ->get();

        if ($galeriPopuler->isEmpty()) {
            $galeriPopuler = Galeri::with('firstItem')
                ->latest() // Ambil yang terbaru
                ->take(3)  // Ambil 3 saja
                ->get();
        }

        // 2. Data untuk Filter (Tahun)
        $years = GaleriItem::select(DB::raw('YEAR(created_at) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $meta_title = 'Galeri foto dan video BAPPERIDA MERAUK';
        $meta_description = 'Yuk lihat Galeri foto dan video BAPPERIDA MERAUK';

        // 3. Data Awal untuk Grid (Default: Semua Album)
        $items = Galeri::with('firstItem')->withCount('items')->latest()->paginate(12);

        $currentFilterType = 'album';

        return view('landing_page.galeri.galeri', compact('galeriPopuler', 'years', 'items', 'currentFilterType', 'meta_title', 'meta_description'));
    }

    /**
     * Menangani pencarian AJAX dan filter untuk galeri publik.
     */
    public function searchPublic(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        $search = $request->input('search');
        $sort = $request->input('sort', 'terbaru');
        $tanggal = $request->input('tanggal'); // Ini adalah tanggal spesifik (atau tahun, tergantung view)
        $type = $request->input('type', 'album');

        $currentFilterType = $type;
        $items = null;

        if ($type === 'album') {
            $query = Galeri::with('firstItem')->withCount('items');
            if ($search) {
                $query->where('judul', 'like', '%' . $search . '%');
            }
            if ($tanggal) {
                // Jika input tanggal adalah tahun (YYYY), gunakan whereYear
                if (strlen($tanggal) == 4 && is_numeric($tanggal)) {
                    $query->whereYear('created_at', $tanggal);
                } else {
                    // Jika input tanggal adalah tanggal penuh (YYYY-MM-DD)
                    $query->whereDate('created_at', $tanggal);
                }
            }
        } else {
            $query = GaleriItem::with('galeri');
            $query->where('tipe_file', $type);
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('caption', 'like', '%' . $search . '%')
                        ->orWhereHas('galeri', function ($q_galeri) use ($search) {
                            $q_galeri->where('judul', 'like', '%' . $search . '%');
                        });
                });
            }
            if ($tanggal) {
                if (strlen($tanggal) == 4 && is_numeric($tanggal)) {
                    $query->whereYear('created_at', $tanggal);
                } else {
                    $query->whereDate('created_at', $tanggal);
                }
            }
        }

        if ($sort === 'terlama') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $items = $query->paginate(12);

        return response()->json([
            'html' => view('landing_page.galeri.partials._galeri_grid', compact('items', 'currentFilterType'))->render(),
            'pagination' => $items->appends($request->except('page'))->links()->toHtml(),
        ]);
    }

    public function showPublic(Galeri $galeri)
    {

        $items = $galeri->items()->orderBy('id', 'asc')->get();

        $albumLainnya = Galeri::with('firstItem')
            ->where('id', '!=', $galeri->id)
            ->latest()
            ->take(4)
            ->get();

        $meta_image = $galeri->items()->orderBy('id', 'asc')->first()?->file_path;

        $currentFilterType = 'all';

        return view('landing_page.galeri.galeri_detail', compact('galeri', 'items', 'albumLainnya', 'currentFilterType', 'meta_image'));
    }

    // ==========================================================
    // === FUNGSI BARU UNTUK FILTER AJAX DI HALAMAN DETAIL ===
    // ==========================================================
    public function filterItems(Request $request, Galeri $galeri)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        $type = $request->input('type', 'all');
        $currentFilterType = $type;

        $query = $galeri->items(); // Mulai query dari relasi

        if ($type !== 'all') {
            $query->where('tipe_file', $type);
        }

        $items = $query->orderBy('id', 'asc')->get();

        return response()->json([
            'html' => view('landing_page.galeri.partials._galeri_masonry_grid', compact('items', 'currentFilterType', 'galeri'))->render()
        ]);
    }
}
