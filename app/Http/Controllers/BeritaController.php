<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\BeritaItem;
use App\Models\KategoriDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        $beritas = $query->paginate(10);
        return view('pages.berita.index', compact('beritas'));
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

            $beritas = $query->limit(15)->get(); // Ambil 15 hasil teratas untuk live search

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
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:published,draft',
            'items' => 'required|array|min:1',
            'items.*.type' => 'required|in:text,image,video,embed,quote',
            'items.*.content' => 'nullable|string', // Konten tekstual, atau path lama untuk gambar
            'items.*.file' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:2048', // File upload baru
            'items.*.caption' => 'nullable|string|max:255', // Caption untuk gambar
            'items.*.position' => 'required|integer',
        ]);

        DB::transaction(function () use ($validatedData, $request) {
            // 1. Handle upload cover image
            $coverImagePath = $request->file('cover_image')->store('berita/covers', 'public');

            // 2. Buat record Berita utama
            $berita = Berita::create([
                'user_id' => Auth::id(),
                'title' => $validatedData['title'],
                'slug' => Str::slug($validatedData['title']) . '-' . Str::random(5), // Tambahkan random untuk uniqueness
                'excerpt' => $validatedData['excerpt'],
                'cover_image' => $coverImagePath,
                'status' => $validatedData['status'],
            ]);

            // 3. Simpan setiap item berita
            foreach ($validatedData['items'] as $index => $itemData) {
                $contentToSave = $itemData['content'] ?? null;
                $captionToSave = $itemData['caption'] ?? null;

                // Jika tipe item adalah gambar, handle upload file baru
                if ($itemData['type'] === 'image' && $request->hasFile("items.{$index}.file")) {
                    $contentToSave = $request->file("items.{$index}.file")->store('berita/items', 'public');
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
        // Load relasi 'items' untuk ditampilkan di form
        $berita->load('items');
        return view('pages.berita.edit', compact('berita'));
    }

    /**
     * Mengupdate berita beserta item-item kontennya.
     */
    public function update(Request $request, Berita $berita)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:published,draft',
            'items' => 'sometimes|array',
            'items.*.type' => 'required|in:text,image,video,embed,quote',
            'items.*.content' => 'nullable|string', // Path lama gambar atau konten teks/URL
            'items.*.file' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:2048', // Upload file baru
            'items.*.caption' => 'nullable|string|max:255', // Caption untuk gambar
            'items.*.position' => 'required|integer',
        ]);

        DB::transaction(function () use ($validatedData, $request, $berita) {
            $updateData = [
                'title' => $validatedData['title'],
                // Regenerate slug to ensure uniqueness if title changes
                'slug' => Str::slug($validatedData['title']) . '-' . Str::random(5),
                'excerpt' => $validatedData['excerpt'],
                'status' => $validatedData['status'],
            ];

            // 1. Handle update cover image jika ada yang baru
            if ($request->hasFile('cover_image')) {
                // Hapus gambar lama jika ada
                if ($berita->cover_image) {
                    Storage::disk('public')->delete($berita->cover_image);
                }
                $updateData['cover_image'] = $request->file('cover_image')->store('berita/covers', 'public');
            }

            // 2. Update record Berita utama
            $berita->update($updateData);

            // 3. Sinkronisasi item berita:
            // Ambil path gambar lama untuk item yang akan dihapus dari DB
            $oldImagePaths = $berita->items->where('type', 'image')->pluck('content')->toArray();

            // Hapus semua item lama dari berita ini
            $berita->items()->delete();

            // Buat ulang item-item berdasarkan data form baru
            if (isset($validatedData['items'])) {
                foreach ($validatedData['items'] as $index => $itemData) {
                    $contentToSave = $itemData['content'] ?? null; // Ini akan berisi path lama jika ada
                    $captionToSave = $itemData['caption'] ?? null;

                    if ($itemData['type'] === 'image') {
                        if ($request->hasFile("items.{$index}.file")) {
                            $contentToSave = $request->file("items.{$index}.file")->store('berita/items', 'public');
                            if (in_array($itemData['content'], $oldImagePaths) && $itemData['content'] !== $contentToSave) {
                                Storage::disk('public')->delete($itemData['content']);
                            }
                        } else {
                            if (empty($contentToSave)) {
                                if (in_array($itemData['content'], $oldImagePaths)) {
                                    Storage::disk('public')->delete($itemData['content']);
                                }
                                $contentToSave = null;
                            }
                        }
                    }

                    $berita->items()->create([
                        'type' => $itemData['type'],
                        'content' => $contentToSave,
                        'caption' => $captionToSave,
                        'position' => $itemData['position'],
                    ]);
                }
            }

            foreach ($oldImagePaths as $oldPath) {
                if ($oldPath && !BeritaItem::where('content', $oldPath)->exists()) {
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
        DB::transaction(function () use ($berita) {

            if ($berita->cover_image) {
                Storage::disk('public')->delete($berita->cover_image);
            }

            foreach ($berita->items as $item) {
                if ($item->type === 'image' && $item->content) {
                    Storage::disk('public')->delete($item->content);
                }
            }

            $berita->delete();
        });

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus!');
    }

    public function home()
    {

        $beritas = Berita::with('author')
            ->where('status', 'published')
            ->where('page', 'berita')
            ->latest()
            ->paginate(6);

        return view('landing_page.berita', compact('beritas'));
    }

    /**
     * Menampilkan halaman detail satu berita.
     * (Ini adalah langkah selanjutnya setelah halaman daftar berita selesai)
     */
    public function show(Berita $berita)
    {

        if ($berita->status !== 'published') {
            abort(404);
        }


        $beritaTerkait = Berita::where('status', 'published')
            ->where('id', '!=', $berita->id)
            ->where('page', 'berita')
            ->latest()
            ->take(3)
            ->get();

        return view('landing_page.berita_detail', compact('berita', 'beritaTerkait'));
    }
}
