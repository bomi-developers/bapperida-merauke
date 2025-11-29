<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\KategoriDocument;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    /**
     * Menampilkan halaman manajemen dokumen.
     */
    public function index(Request $request)
    {
        $query = Document::query();
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where('judul', 'like', $searchTerm . '%');
        }
        $documents = $query->latest()->paginate(20);
        $kategori = KategoriDocument::orderBy('nama_kategori')->get();
        return view('pages.document.index', compact('documents', 'kategori'));
    }
    public function getDokumen(Request $request)
    {
        $search = $request->keyword ?? '';

        $query = Document::with('kategori')
            ->latest();

        // Jika ada pencarian
        if (!empty($search)) {
            $query->where('judul', 'like', '%' . $search . '%');
        }
        $dokumen = $query->limit(20)->get();
        return response()->json([
            'status' => 'success',
            'data' => $dokumen
        ]);
    }

    /**
     * Menyimpan dokumen baru ke database dengan nama file kustom.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'kategori_document_id' => 'required|exists:kategori_documents,id',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:102400', // Maks 10MB
            'lainnya' => 'nullable|json',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        // Logika penamaan file kustom
        $slug = Str::slug($validated['judul']);
        $shortCode = strtolower(Str::random(4));

        if ($request->hasFile('cover')) {
            $coverFile = $request->file('cover');
            $coverExtension = $coverFile->getClientOriginalExtension();
            $coverFilename = "{$slug}-{$shortCode}-cover.{$coverExtension}";
            $validated['cover'] = $coverFile->storeAs('documents/covers', $coverFilename, 'public');
        }

        if ($request->hasFile('file')) {
            $mainFile = $request->file('file');
            $mainExtension = $mainFile->getClientOriginalExtension();
            $mainFilename = "{$slug}-{$shortCode}.{$mainExtension}";
            $validated['file'] = $mainFile->storeAs('documents/files', $mainFilename, 'public');
        }

        $document = Document::create($validated);
        $document->load('kategori');

        return response()->json([
            'success' => true,
            'message' => 'Dokumen berhasil ditambahkan!',
            'data' => $document
        ]);
    }

    /**
     * Mengambil data satu dokumen untuk ditampilkan.
     */
    public function show(Document $document)
    {
        $document->load('kategori');
        return response()->json($document);
    }

    /**
     * Mengupdate data dokumen di database dengan nama file kustom jika ada file baru.
     */
    public function update(Request $request, Document $document)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'kategori_document_id' => 'required|exists:kategori_documents,id',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:102400',
            'lainnya' => 'nullable|json',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        $slug = Str::slug($validated['judul']);
        $shortCode = strtolower(Str::random(4));

        if ($request->hasFile('cover')) {
            if ($document->cover) {
                Storage::disk('public')->delete($document->cover);
            }
            $coverFile = $request->file('cover');
            $coverExtension = $coverFile->getClientOriginalExtension();
            $coverFilename = "{$slug}-{$shortCode}-cover.{$coverExtension}";
            $validated['cover'] = $coverFile->storeAs('documents/covers', $coverFilename, 'public');
        }

        if ($request->hasFile('file')) {
            if ($document->file) {
                Storage::disk('public')->delete($document->file);
            }
            $mainFile = $request->file('file');
            $mainExtension = $mainFile->getClientOriginalExtension();
            $mainFilename = "{$slug}-{$shortCode}.{$mainExtension}";
            $validated['file'] = $mainFile->storeAs('documents/files', $mainFilename, 'public');
        }

        $document->update($validated);
        $document->load('kategori');

        return response()->json([
            'success' => true,
            'message' => 'Dokumen berhasil diperbarui!',
            'data' => $document,
        ]);
    }

    /**
     * Menghapus dokumen dari database dan storage.
     */
    public function destroy(Document $document)
    {
        if ($document->cover) {
            Storage::disk('public')->delete($document->cover);
        }
        if ($document->file) {
            Storage::disk('public')->delete($document->file);
        }
        $document->delete();
        return response()->json(['success' => true, 'message' => 'Dokumen berhasil dihapus!']);
    }

    /**
     * Menangani permintaan unduh file.
     */
    public function downloadFile(Document $document)
    {
        $path = storage_path('app/public/' . $document->file);

        if (!Storage::disk('public')->exists($document->file)) {
            return back()->with('error', 'File tidak ditemukan atau telah dihapus.');
        }

        // Nama file saat diunduh sudah sesuai dengan judul dokumen
        $slug = Str::slug($document->judul);
        $extension = pathinfo($document->file, PATHINFO_EXTENSION);
        $shortCode = substr(sha1($document->id), 0, 4); // Kode unik dari ID dokumen

        $newFilename = "{$slug}-{$shortCode}.{$extension}";

        return response()->download($path, $newFilename);
    }

    /**
     * Menampilkan halaman daftar dokumen berdasarkan kategori untuk publik.
     */
    public function showByCategory(KategoriDocument $kategori)
    {
        $documentsQuery = $kategori->documents()->latest();
        $documents = $documentsQuery->paginate(9);

        // PERBAIKAN: Gunakan reorder() untuk menghapus `order by` sebelumnya
        $years = (clone $documentsQuery)
            ->reorder() // Ini akan menghapus `orderBy('created_at', 'desc')`
            ->select(DB::raw('YEAR(created_at) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('landing_page.document.dokumen_by_kategori', compact('kategori', 'documents', 'years'));
    }

    /**
     * Menangani pencarian dan filter AJAX di halaman publik.
     */
    public function searchPublic(Request $request)
    {
        if ($request->ajax()) {
            $query = Document::with('kategori')->where('kategori_document_id', $request->kategori_id);

            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('judul', 'like', '%' . $searchTerm . '%')
                        ->orWhere('lainnya->visi', 'like', '%' . $searchTerm . '%');
                });
            }

            if ($request->filled('periode') && $request->periode !== 'semua') {
                $query->whereYear('created_at', $request->periode);
            }

            if ($request->input('sort') === 'terlama') {
                $query->oldest();
            } else {
                $query->latest();
            }

            $documents = $query->paginate(9);

            return response()->json([
                'table_html' => view('landing_page.document.partials._document_list_public', compact('documents'))->render(),
                'pagination_html' => $documents->links()->toHtml(),
            ]);
        }
        return abort(404);
    }
}
