<?php

namespace App\Http\Controllers;

use App\Models\Opd;
use Illuminate\Http\Request;

class OpdController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Akun Perangkat Daerah',
        ];
        return view('pages.opd.index', $data);
    }
    public function getData(Request $request)
    {
        $query = Opd::with(['user']);

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                ->orWhere('nip', 'like', "%{$request->search}%")
                ->orWhere('instansi', 'like', "%{$request->search}%");
        }

        if ($request->akun !== '-' && $request->akun !== null) {
            if ($request->akun == 1) {
                $query->whereHas('user');
            } elseif ($request->akun == 0) {
                $query->whereDoesntHave('user');
            }
        }

        $opd = $query->orderBy('created_at', 'desc')->paginate(5);
        return response()->json($opd);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|unique:opd,nip',
            'no_hp' => 'required',
            'email' => 'required',
            'instansi' => 'required',
        ]);

        $data = $request->all();

        $opd = opd::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Pegawai berhasil ditambahkan',
            'data' => $opd
        ]);
    }

    public function update(Request $request, Opd $opd)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => "requidred|string|unique:opd,nip,{$opd->id}",
            'no_hp' => 'required|string|max:255',
            'email' => 'required',
            'instansi' => 'required|string|max:255',
        ]);

        $opd->update($request->all());

        return response()->json(['success' => true, 'message' => 'Pegawai berhasil diupdate', 'data' => $opd]);
    }

    public function destroy(Opd $opd)
    {
        $opd->delete();
        return response()->json(['success' => true, 'message' => 'Pegawai berhasil dihapus']);
    }
}
