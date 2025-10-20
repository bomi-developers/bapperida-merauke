<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\KategoriDocument;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KategoriDocumentController extends Controller
{
    public function index()
    {
        $kategori = KategoriDocument::latest()->get();
        return view('pages.document.kategori', compact('kategori'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_documents',
        ]);

        $kategori = KategoriDocument::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan!',
            'data' => $kategori,
        ]);
    }

    public function show(KategoriDocument $kategori)
    {
        return response()->json($kategori);
    }

    public function update(Request $request, KategoriDocument $kategori)
    {
        $validated = $request->validate([
            'nama_kategori' => [
                'required',
                'string',
                'max:255',
                Rule::unique('kategori_documents')->ignore($kategori->id),
            ],
        ]);

        $kategori->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui!',
            'data' => $kategori,
        ]);
    }

    public function destroy(KategoriDocument $kategori)
    {
        $kategori->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus!',
        ]);
    }
}
