<?php

namespace App\Http\Controllers;

use App\Models\KotakSaran;
use Illuminate\Http\Request;

class KotakSaranController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $kotakSaran = KotakSaran::latest()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('pengirim', 'like', '%' . $search . '%')
                        ->orWhere('isi', 'like', '%' . $search . '%')
                        ->orWhere('ip_address', 'like', '%' . $search . '%');
                });
            })
            ->paginate(20);

        return view('pages.kotak_saran.index', [
            'title'       => 'Kotak Saran',
            'kotakSaran'  => $kotakSaran,
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'pengirim' => 'nullable|string|max:100',
            'isi' => 'required|string'
        ]);

        $saran = KotakSaran::create([
            'pengirim'   => $request->pengirim ?? 'Anonimous',
            'isi'        => $request->isi,
            'ip_address' => $request->ip()
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Saran berhasil dikirim',
            'data' => $saran
        ], 200);
    }
    public function destroy($id)
    {
        $saran = KotakSaran::findOrFail($id);
        $saran->delete();

        return response()->json([
            'status' => true,
            'message' => 'Saran berhasil dihapus'
        ]);
    }
}