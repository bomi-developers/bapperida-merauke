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
            'items.*.content' => 'nullable|string',
            'items.*.file' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:2048',
            'items.*.caption' => 'nullable|string|max:255',
            'items.*.position' => 'required|integer',
        ]);

        DB::transaction(function () use ($validatedData, $request, $berita) {
            $updateData = [
                'title' => $validatedData['title'],
                'slug' => Str::slug($validatedData['title']) . '-' . Str::random(5),
                'excerpt' => $validatedData['excerpt'],
                'status' => $validatedData['status'],
            ];

            if ($request->hasFile('cover_image')) {
                if ($berita->cover_image) {
                    Storage::disk('public')->delete($berita->cover_image);
                }
                $updateData['cover_image'] = $request->file('cover_image')->store('berita/covers', 'public');
            }

            $berita->update($updateData);

            $oldImagePaths = $berita->items->where('type', 'image')->pluck('content')->filter()->toArray(); // filter() untuk menghapus null/empty

            $berita->items()->delete();

            if (isset($validatedData['items'])) {
                foreach ($validatedData['items'] as $index => $itemData) {
                    // Gunakan null coalescing ?? untuk default value
                    $contentToSave = $itemData['content'] ?? null;
                    $captionToSave = $itemData['caption'] ?? null;
                    $oldContentPath = $itemData['content'] ?? null; // Simpan path lama sebelum ditimpa

                    if ($itemData['type'] === 'image') {
                        if ($request->hasFile("items.{$index}.file")) {
                            $contentToSave = $request->file("items.{$index}.file")->store('berita/items', 'public');

                            // PERBAIKAN 1: Cek apakah oldContentPath ada sebelum digunakan
                            if (!empty($oldContentPath) && in_array($oldContentPath, $oldImagePaths) && $oldContentPath !== $contentToSave) {
                                Storage::disk('public')->delete($oldContentPath);
                            }
                        } else {
                            // Jika tidak ada file baru DAN path lama kosong (misal item baru ditambahkan), content tetap null
                            if (empty($contentToSave)) {
                                // PERBAIKAN 2: Cek apakah oldContentPath ada sebelum digunakan
                                if (!empty($oldContentPath) && in_array($oldContentPath, $oldImagePaths)) {
                                    Storage::disk('public')->delete($oldContentPath);
                                }
                                $contentToSave = null;
                            }
                            // Jika tidak ada file baru TAPI path lama ADA, contentToSave sudah berisi path lama dari awal, tidak perlu diubah.
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

            // Hapus gambar lama yang tidak lagi direferensikan oleh item BARU
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

        return view('landing_page.berita.berita', compact('beritas'));
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

        // Kirim data berita utama dan berita terkait ke view.
        return view('landing_page.berita.berita_detail', compact('berita', 'beritaTerkait'));
    }
}
