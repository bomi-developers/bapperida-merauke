<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Golongan;
use App\Models\Jabatan;

class PegawaiController extends Controller
{
    public function index()
    {
        $golongan = Golongan::all();
        $jabatan = Jabatan::all();
        $bidang = Bidang::all();
        return view('pages.pegawai.index', compact('golongan', 'jabatan', 'bidang'));
    }

    public function getData(Request $request)
    {
        $query = Pegawai::with(['golongan', 'jabatan', 'bidang', 'user']);

        if ($request->search) {
            $query->where('nama', 'like', "%{$request->search}%")
                ->orWhere('nip', 'like', "%{$request->search}%")
                ->orWhere('nik', 'like', "%{$request->search}%");
        }
        if ($request->golongan != '-' && $request->golongan) {
            $query->where('id_golongan', $request->golongan);
        }
        if ($request->jabatan != '-' && $request->jabatan) {
            $query->where('id_jabatan', $request->jabatan);
        }
        if ($request->bidang != '-' && $request->bidang) {
            $query->where('id_bidang', $request->bidang);
        }
        if ($request->akun !== '-' && $request->akun !== null) {
            if ($request->akun == 1) {
                $query->whereHas('user');
            } elseif ($request->akun == 0) {
                $query->whereDoesntHave('user');
            }
        }

        $pegawais = $query->orderBy('created_at', 'desc')->paginate(5);
        return response()->json($pegawais);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|max:50|unique:pegawais,nip',
            'nik' => 'nullable|max:50|unique:pegawais,nik',
            'id_golongan' => 'required|exists:golongans,id',
            'id_jabatan' => 'required|exists:jabatans,id',
            'id_bidang' => 'required|exists:bidangs,id',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:5048',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/foto_pegawai', $filename);

            $data['foto'] = $filename;
        }

        $pegawai = Pegawai::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Pegawai berhasil ditambahkan',
            'data' => $pegawai
        ]);
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => "nullable|string|max:50|unique:pegawais,nip,{$pegawai->id}",
            'nik' => "nullable|string|max:50|unique:pegawais,nik,{$pegawai->id}",
            'id_golongan' => 'required|exists:golongans,id',
            'id_jabatan' => 'required|exists:jabatans,id',
            'id_bidang' => 'required|exists:bidangs,id',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:5048',
        ]);
        $data = $request->all();

        if ($request->hasFile('foto')) {
            $filename = $request->file('file')->store('foto_pegawai', 'public');

            $data['foto'] = $filename;
        }

        $pegawai->update($data);

        return response()->json(['success' => true, 'message' => 'Pegawai berhasil diupdate', 'data' => $pegawai]);
    }

    public function destroy(Pegawai $pegawai)
    {
        $pegawai->delete();
        return response()->json(['success' => true, 'message' => 'Pegawai berhasil dihapus']);
    }
}
