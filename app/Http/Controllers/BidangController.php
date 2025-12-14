<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bidang;
use Illuminate\Support\Facades\DB;

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

        $bidangs = $query->orderBy('urutan', 'asc')->paginate(50);

        return response()->json($bidangs);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bidang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tampilkan' => 'nullable',
        ]);

        DB::beginTransaction();
        try {
            $maxUrutan = Bidang::max('urutan') ?? 0;

            $bidang = Bidang::create([
                'nama_bidang' => $request->nama_bidang,
                'deskripsi' => $request->deskripsi,
                'tampilkan' => $request->tampilkan,
                'urutan' => $maxUrutan + 1,
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Bidang berhasil ditambahkan',
                'data' => $bidang
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
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
        DB::beginTransaction();
        try {
            $urutanDihapus = $bidang->urutan;

            // Hapus bidang
            $bidang->delete();

            // Geser urutan yang lebih besar ke atas
            Bidang::where('urutan', '>', $urutanDihapus)
                ->decrement('urutan');

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Bidang berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    public function updateUrutan(Request $request, Bidang $bidang)
    {
        $request->validate([
            'direction' => 'required|in:up,down',
        ]);

        DB::beginTransaction();
        try {
            $direction = $request->direction;
            $currentUrutan = $bidang->urutan;

            if ($direction === 'up') {
                // Cari bidang yang urutannya tepat di atas
                $targetBidang = Bidang::where('urutan', '<', $currentUrutan)
                    ->orderBy('urutan', 'desc')
                    ->first();

                if ($targetBidang) {
                    // Tukar urutan
                    $targetUrutan = $targetBidang->urutan;

                    $targetBidang->update(['urutan' => $currentUrutan]);
                    $bidang->update(['urutan' => $targetUrutan]);
                }
            } elseif ($direction === 'down') {
                // Cari bidang yang urutannya tepat di bawah
                $targetBidang = Bidang::where('urutan', '>', $currentUrutan)
                    ->orderBy('urutan', 'asc')
                    ->first();

                if ($targetBidang) {
                    // Tukar urutan
                    $targetUrutan = $targetBidang->urutan;

                    $targetBidang->update(['urutan' => $currentUrutan]);
                    $bidang->update(['urutan' => $targetUrutan]);
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Urutan berhasil diubah'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
