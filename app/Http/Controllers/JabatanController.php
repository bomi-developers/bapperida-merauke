<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index()
    {
        return view('pages.jabatan.index');
    }

    public function getData(Request $request)
    {
        $query = Jabatan::query();

        if ($request->search) {
            $query->where('jabatan', 'like', '%' . $request->search . '%');
        }

        $jabatans = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json($jabatans);
    }

    public function store(Request $request)
    {
        $request->validate([
            'jabatan' => 'required|string|max:255',
        ]);

        $jabatan = jabatan::create($request->only('jabatan'));

        return response()->json(['success' => true, 'message' => 'jabatan berhasil ditambahkan', 'data' => $jabatan]);
    }

    public function update(Request $request, jabatan $jabatan)
    {
        $request->validate([
            'jabatan' => 'required|string|max:255',
        ]);

        $jabatan->update($request->only('jabatan'));

        return response()->json(['success' => true, 'message' => 'jabatan berhasil diupdate', 'data' => $jabatan]);
    }

    public function destroy(jabatan $jabatan)
    {
        $jabatan->delete();

        return response()->json(['success' => true, 'message' => 'jabatan berhasil dihapus']);
    }
}
