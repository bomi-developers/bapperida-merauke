<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bidang;

class BidangController extends Controller
{
    public function index()
    {
        return view('pages.bidang.index');
    }

    public function getData(Request $request)
    {
        $query = Bidang::query();

        if ($request->search) {
            $query->where('nama_bidang', 'like', '%' . $request->search . '%')
                ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
        }

        $bidangs = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json($bidangs);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bidang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tampilkan' => 'nullable',
        ]);

        $bidang = Bidang::create($request->only('nama_bidang', 'deskripsi', 'tampilkan'));

        return response()->json(['success' => true, 'message' => 'Bidang berhasil ditambahkan', 'data' => $bidang]);
    }

    public function update(Request $request, Bidang $bidang)
    {
        $request->validate([
            'nama_bidang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tampilkan' => 'nullable',
        ]);

        $bidang->update($request->only('nama_bidang', 'deskripsi', 'tampilkan'));

        return response()->json(['success' => true, 'message' => 'Bidang berhasil diupdate', 'data' => $bidang]);
    }

    public function destroy(Bidang $bidang)
    {
        $bidang->delete();

        return response()->json(['success' => true, 'message' => 'Bidang berhasil dihapus']);
    }
}
