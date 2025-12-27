<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\BeritaItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Jobs\CompressBeritaImage;
use App\Models\Notifikasi;
use App\Models\User;
use Intervention\Image\Facades\Image;

class BeritaController extends Controller
{
    /**
     * Menampilkan daftar berita dengan fitur pencarian.
     */
    public function index(Request $request)
    {
        $query = Berita::with('author')->latest();

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->whereRaw('LOWER(title) LIKE ?', [strtolower($searchTerm) . '%']);
        }
        $authors = User::has('berita')->get();

        $beritas = $query->paginate(10);
        return view('pages.berita.index', compact('beritas', 'authors'));
    }
    public function getData(Request $request)
    {
        $query = Berita::with(['author', 'items']);

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->author != '-' && $request->author) {
            $query->where('user_id', $request->author);
        }
        if ($request->status != '-' && $request->status) {
            $query->where('status', $request->status);
        }
        if ($request->jenis != '-' && $request->jenis) {
            $query->where('page', $request->jenis);
        }

        $beritas = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json($beritas);
    }

    /**
     * Handle AJAX search requests.
     */
    public function search(Request $request)
    {
        if ($request->ajax()) {
            $query = Berita::with('author')->latest();

            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->whereRaw('LOWER(title) LIKE ?', [strtolower($searchTerm) . '%']);
            }

            $beritas = $query->limit(15)->get();

            return response()->json([
                'table_rows' => view('pages.berita._berita_rows', compact('beritas'))->render(),
            ]);
        }
        return abort(404);
    }

    /**
     * Menampilkan form untuk membuat berita baru.
     */
    public function create()
    {
        return view('pages.berita.create');
    }

    /**
     * Menyimpan berita baru beserta item-item kontennya.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:20480', // 20MB
            'status' => 'required|in:published,draft',
            'page' => 'required|in:berita,inovasi_kekayaan_intelektual,inovasi_riset,inovasi_data',
            'items' => 'required|array|min:1',
            'items.*.type' => 'required|in:text,image,video,embed,quote',
            'items.*.content' => 'nullable|string',
            'items.*.file' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:20480', // 20MB
            'items.*.caption' => 'nullable|string|max:255',
            'items.*.position' => 'required|integer',
        ]);

        DB::transaction(function () use ($validatedData, $request) {

            $coverImagePath = null;
            if ($request->hasFile('cover_image')) {
                $coverFile = $request->file('cover_image');
                $slug = Str::slug($validatedData['title']);
                $coverExtension = $coverFile->getClientOriginalExtension();
                $coverFilename = "covers-{$slug}-" . Str::random(5) . ".{$coverExtension}";

                // Simpan file asli (ukuran besar)
                $coverImagePath = $coverFile->storeAs('berita/covers', $coverFilename, 'public');

                // Kirim tugas kompresi ke antrian (Queue)
                CompressBeritaImage::dispatch($coverImagePath);
            }

            $berita = Berita::create([
                'user_id' => Auth::id(),
                'title' => $validatedData['title'],
                'slug' => Str::slug($validatedData['title']) . '-' . Str::random(5),
                'excerpt' => $validatedData['excerpt'],
                'cover_image' => $coverImagePath, // Simpan path asli
                'status' => $validatedData['status'],
                'page' => $validatedData['page'],
                // views_count akan default ke 0
            ]);
            // notifikasi
            Notifikasi::create([
                'title' => 'Berita Baru',
                'message' => $validatedData['title'],
            ]);

            foreach ($validatedData['items'] as $index => $itemData) {
                $contentToSave = $itemData['content'] ?? null;
                $captionToSave = $itemData['caption'] ?? null;

                if ($itemData['type'] === 'image' && $request->hasFile("items.{$index}.file")) {
                    $itemFile = $request->file("items.{$index}.file");
                    $slug = Str::slug($validatedData['title']);
                    $itemExtension = $itemFile->getClientOriginalExtension();
                    $itemFilename = "item-{$slug}-{$index}-" . Str::random(5) . ".{$itemExtension}";

                    // Simpan file asli (ukuran besar)
                    $contentToSave = $itemFile->storeAs('berita/items', $itemFilename, 'public');

                    // Kirim tugas kompresi ke antrian (Queue)
                    CompressBeritaImage::dispatch($contentToSave);
                }

                $berita->items()->create([
                    'type' => $itemData['type'],
                    'content' => $contentToSave,
                    'caption' => $captionToSave,
                    'position' => $itemData['position'],
                ]);
            }
        });

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil ditambahkan!');
    }


    /**
     * Menampilkan form untuk mengedit berita.
     */
    public function edit(Berita $berita)
    {
        // Authorization Check
        if (Auth::user()->role !== 'super_admin' && $berita->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $berita->load('items');
        return view('pages.berita.edit', compact('berita'));
    }

    /**
     * Mengupdate berita beserta item-item kontennya.
     */
    public function update(Request $request, Berita $berita)
    {
        // Authorization Check
        if (Auth::user()->role !== 'super_admin' && $berita->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480', // 20MB
            'status' => 'required|in:published,draft',
            'page' => 'required|in:berita,inovasi_kekayaan_intelektual,inovasi_riset,inovasi_data',
            'items' => 'sometimes|array',
            'items.*.type' => 'required|in:text,image,video,embed,quote',
            'items.*.content' => 'nullable|string',
            'items.*.file' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:20480', // 20MB
            'items.*.caption' => 'nullable|string|max:255',
            'items.*.position' => 'required|integer',
        ]);

        DB::transaction(function () use ($validatedData, $request, $berita) {
            // 1. Siapkan data update untuk Berita
            $updateData = [
                'title' => $validatedData['title'],
                // 'slug' => Str::slug($validatedData['title']) . '-' . Str::random(5),
                'excerpt' => $validatedData['excerpt'],
                'status' => $validatedData['status'],
                'page' => $validatedData['page'],
            ];
            if ($berita->title !== $validatedData['title']) {
                $updateData['slug'] = Str::slug($validatedData['title']) . '-' . Str::random(5);
            } else {
                $updateData['slug'] = $berita->slug;
            }

            // 2. Handle upload Cover Image baru
            if ($request->hasFile('cover_image')) {
                // Hapus cover lama jika ada
                if ($berita->cover_image) {
                    Storage::disk('public')->delete($berita->cover_image);
                }

                $coverFile = $request->file('cover_image');
                $slug = Str::slug($validatedData['title']);
                $coverExtension = $coverFile->getClientOriginalExtension();
                $coverFilename = "covers-{$slug}-" . Str::random(5) . ".{$coverExtension}";

                // Simpan file asli
                $coverImagePath = $coverFile->storeAs('berita/covers', $coverFilename, 'public');

                // Kirim tugas kompresi ke antrian
                CompressBeritaImage::dispatch($coverImagePath);

                $updateData['cover_image'] = $coverImagePath;
            }

            // 3. Update data Berita
            $berita->update($updateData);

            // 4. Sinkronisasi Berita Items
            $oldImagePaths = $berita->items->where('type', 'image')->pluck('content')->filter()->toArray();
            $berita->items()->delete(); // Hapus semua item lama

            if (isset($validatedData['items'])) {
                foreach ($validatedData['items'] as $index => $itemData) {

                    // Inisialisasi variabel untuk item ini
                    $contentToSave = $itemData['content'] ?? null;
                    $captionToSave = $itemData['caption'] ?? null;
                    $oldContentPath = $itemData['content'] ?? null; // Simpan path lama (jika ada)

                    if ($itemData['type'] === 'image') {
                        // Jika ada file BARU yang diupload untuk item ini
                        if ($request->hasFile("items.{$index}.file")) {
                            $itemFile = $request->file("items.{$index}.file");
                            $slug = Str::slug($validatedData['title']);
                            $itemExtension = $itemFile->getClientOriginalExtension();
                            $itemFilename = "item-{$slug}-{$index}-" . Str::random(5) . ".{$itemExtension}";

                            // Simpan file asli baru
                            $contentToSave = $itemFile->storeAs('berita/items', $itemFilename, 'public');

                            // Kirim tugas kompresi
                            CompressBeritaImage::dispatch($contentToSave);

                            // Hapus file gambar lama jika ada dan berbeda
                            if (!empty($oldContentPath) && in_array($oldContentPath, $oldImagePaths) && $oldContentPath !== $contentToSave) {
                                Storage::disk('public')->delete($oldContentPath);
                            }
                        } else {
                            // Jika TIDAK ada file baru diupload
                            // Cek apakah $contentToSave (path lama) kosong
                            if (empty($contentToSave)) {
                                // Jika path lama ada, hapus file terkait
                                if (!empty($oldContentPath) && in_array($oldContentPath, $oldImagePaths)) {
                                    Storage::disk('public')->delete($oldContentPath);
                                }
                                $contentToSave = null; // Pastikan null di database
                            }
                            // Jika $contentToSave TIDAK kosong, berarti itu adalah path lama yg ingin disimpan,
                            // jadi biarkan saja.
                        }
                    }
                    // Untuk tipe 'text', 'video', 'embed', 'quote', $contentToSave sudah benar.

                    $berita->items()->create([
                        'type' => $itemData['type'],
                        'content' => $contentToSave,
                        'caption' => $captionToSave,
                        'position' => $itemData['position'],
                    ]);
                }
            }

            // 5. Hapus file gambar lama yang sudah tidak terpakai
            $newItemImagePaths = $berita->items()->where('type', 'image')->pluck('content')->filter()->toArray();
            foreach ($oldImagePaths as $oldPath) {
                if ($oldPath && !in_array($oldPath, $newItemImagePaths)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
        });

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui!');
    }

    /**
     * Menghapus berita dan file-file terkait.
     */
    public function destroy(Berita $berita)
    {
        // Authorization Check
        if (Auth::user()->role !== 'super_admin' && $berita->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        DB::transaction(function () use ($berita) {
            if ($berita->cover_image) {
                Storage::disk('public')->delete($berita->cover_image);
            }
            foreach ($berita->items as $item) {
                if ($item->type === 'image' && $item->content) {
                    Storage::disk('public')->delete($item->content);
                }
            }
            $berita->delete(); // Hapus berita, dan items akan terhapus via cascade
        });
        // return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus!');
        return response()->json(['success' => true, 'message' => 'Berita berhasil dihapus']);
    }

    // --- METODE PUBLIK ---

    /**
     * Menampilkan halaman daftar berita (home).
     */
    public function home()
    {
        // 1. Ambil 5 Berita Terpopuler (berdasarkan views_count)
        $beritaPopuler = Berita::with('author')
            ->where('status', 'published')
            ->orderBy('views_count', 'desc')
            ->take(5) // Ambil 5 berita
            ->get();

        // 2. Ambil Berita Terkini (dengan paginasi, misal 8 per halaman)
        $beritaTerkini = Berita::with('author')
            ->where('status', 'published')
            ->latest() // Urutkan berdasarkan yang terbaru
            ->paginate(8); // Tampilkan 8 berita per halaman
        $meta_title = 'Berita terlengkap dari BAPPERIDA';
        $meta_description = 'Simak Berita terlengkap dari BAPPERIDA';
        // 3. Kirim kedua data ke view
        return view('landing_page.berita.berita', compact('beritaPopuler', 'beritaTerkini', 'meta_title', 'meta_description'));
    }

    /**
     * Menampilkan halaman detail satu berita.
     */
    public function show(Berita $berita)
    {
        $isAdmin = Auth::check() && Auth::user()->role === 'admin';
        if ($berita->status !== 'published' && !$isAdmin) {
            abort(404);
        }

        // 1. TAMBAHKAN VIEW COUNT
        // Hanya tambahkan jika bukan admin yang melihat
        if (!$isAdmin) {
            $berita->increment('views_count');
        }

        // Load relasi yang dibutuhkan
        $berita->load(['items' => function ($query) {
            $query->orderBy('position');
        }, 'author']);

        // 2. AMBIL BERITA TERKINI (untuk sidebar)
        $beritaTerkini = Berita::where('status', 'published')
            ->where('id', '!=', $berita->id) // Kecualikan berita saat ini
            ->latest() // Urutkan dari yang terbaru
            ->take(5) // Ambil 5 berita
            ->get();

        // 3. AMBIL BERITA TERPOPULER (untuk "Baca Juga")
        $beritaTerpopuler = Berita::where('status', 'published')
            ->where('id', '!=', $berita->id) // Kecualikan berita saat ini
            ->orderBy('views_count', 'desc') // Urutkan berdasarkan views_count
            ->take(6) // Ambil 3 berita
            ->get();

        $meta_title = $berita->title;
        $meta_description = Str::limit(strip_tags($berita->caption), 160);
        $meta_image  = asset('storage/' . $berita->cover_image);

        return view(
            'landing_page.berita.berita_detail',
            compact('berita', 'beritaTerkini', 'beritaTerpopuler', 'meta_title', 'meta_description', 'meta_image')
        );
    }

    public function searchPublic(Request $request)
    {
        if (!$request->ajax()) {
            return abort(404);
        }

        $query = Berita::with('author')->where('status', 'published');

        // 1. Filter Pencarian Judul
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // 2. Filter Tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        // 3. Filter Urutan
        if ($request->input('sort') === 'terlama') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc'); // Default: Paling Baru
        }

        $beritaTerkini = $query->paginate(8); // Samakan dengan jumlah paginasi di home()

        // Kembalikan HTML yang sudah di-render
        return response()->json([
            'html' => view('landing_page.berita._berita_terkini_grid', compact('beritaTerkini'))->render(),
            'pagination' => $beritaTerkini->appends($request->except('page'))->links()->toHtml(),
        ]);
    }
}