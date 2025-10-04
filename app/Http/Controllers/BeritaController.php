<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\BeritaItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    public function index()
    {
        return view('pages.berita.index');
    }


    public function data(Request $request)
    {
        $query = Berita::with('items');

        if ($search = $request->search) {
            $query->where('title', 'like', "%{$search}%");
        }

        $beritas = $query->latest()->paginate(20);

        // $links = $beritas->links()->render();
        // $links = json_decode(json_encode($beritas->links()->elements), true);

        return response()->json([
            'data' => $beritas->items(),
            'links' => collect($beritas->links())->map(fn($link) => [
                'url' => $link['url'] ?? null,
                'label' => $link['label'],
                'active' => $link['active'] ?? false
            ])
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'items' => 'required|array',
            'items.*.type' => 'required|in:text,image,video,embed',
            'items.*.content' => 'required|string',
        ]);

        $berita = Berita::create([
            'title' => $request->judul,
            'slug' => Str::slug($request->judul)
        ]);

        foreach ($request->items as $index => $item) {
            BeritaItem::create([
                'berita_id' => $berita->id,
                'type' => $item['type'],
                'content' => $item['content'],
                'position' => $index
            ]);
        }

        return response()->json(['message' => 'Berita berhasil ditambahkan!']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'items' => 'required|array',
            'items.*.type' => 'required|in:text,image,video,embed',
            'items.*.content' => 'required|string',
        ]);

        $berita = Berita::findOrFail($id);
        $berita->update(['title' => $request->judul, 'slug' => Str::slug($request->judul)]);


        $berita->items()->delete();
        foreach ($request->items as $index => $item) {
            BeritaItem::create([
                'berita_id' => $berita->id,
                'type' => $item['type'],
                'content' => $item['content'],
                'position' => $index
            ]);
        }

        return response()->json(['message' => 'Berita berhasil diperbarui!']);
    }

    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);
        $berita->delete();

        return response()->json(['message' => 'Berita berhasil dihapus!']);
    }
}
