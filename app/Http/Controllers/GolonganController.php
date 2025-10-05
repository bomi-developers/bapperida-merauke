<?php

namespace App\Http\Controllers;

use App\Models\Golongan;
use Illuminate\Http\Request;

class GolonganController extends Controller
{
    public function index()
    {
        return view('pages.golongan.index');
    }

    public function getData(Request $request)
    {
        $query = Golongan::query();

        if ($request->search) {
            $query->where('golongan', 'like', '%' . $request->search . '%');
        }

        $golongans = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json($golongans);
    }

    public function store(Request $request)
    {
        $request->validate([
            'golongan' => 'required|string|max:255',
        ]);

        $golongan = golongan::create($request->only('golongan'));

        return response()->json(['success' => true, 'message' => 'golongan berhasil ditambahkan', 'data' => $golongan]);
    }

    public function update(Request $request, golongan $golongan)
    {
        $request->validate([
            'golongan' => 'required|string|max:255',
        ]);

        $golongan->update($request->only('golongan'));

        return response()->json(['success' => true, 'message' => 'golongan berhasil diupdate', 'data' => $golongan]);
    }

    public function destroy(golongan $golongan)
    {
        $golongan->delete();

        return response()->json(['success' => true, 'message' => 'golongan berhasil dihapus']);
    }
}
