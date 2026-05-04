<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\LaporanHistory;
use App\Models\TriwulanPeriod;
use App\Models\LaporanTriwulan;
use Illuminate\Support\Facades\Auth;
use App\Models\MasterTemplateTriwulan;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Storage;

class TriwulanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $masterTemplates = MasterTemplateTriwulan::getAllActive();
        $allPeriods = TriwulanPeriod::orderBy('tahun', 'desc')->orderBy('triwulan', 'desc')->get();
        $openPeriods = $allPeriods->where('is_open', true);

        // --- QUERY ---
        $query = LaporanTriwulan::with(['user', 'period', 'histories']);

        // 1. Filter Role
        if ($user->role !== 'admin' && $user->role !== 'super_admin') {
            $query->where('user_id', $user->id);
        }

        // 2. Filter Search
        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // 3. Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 4. Filter Periode
        if ($request->filled('period_filter')) {
            $query->where('period_id', $request->period_filter);
        }

        // PENTING: Gunakan withQueryString() agar pagination mengingat filter search/status
        $laporans = $query->latest()->paginate(10)->withQueryString();

        // --- CEK AJAX (Wajib Ada) ---
        if ($request->ajax()) {
            return view('pages.triwulan._table_list', compact('laporans'))->render();
        }

        $periods = TriwulanPeriod::orderBy('tahun', 'desc')->orderBy('triwulan', 'desc')->paginate(4, ['*'], 'period_page');

        return view('pages.triwulan.index', compact(
            'laporans',
            'periods',
            'masterTemplates',
            'openPeriods',
            'allPeriods'
        ));
    }

    // Update/Buat Periode Baru
    public function updatePeriod(Request $request)
    {
        $request->validate([
            'triwulan' => 'required',
            'tahun' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // KITA HAPUS logic yang menonaktifkan periode lain.
        // Jadi Admin bisa punya banyak periode buka sekaligus.

        TriwulanPeriod::updateOrCreate(
            [
                'triwulan' => $request->triwulan,
                'tahun' => $request->tahun
            ],
            [
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                // Default true saat dibuat/diupdate
                'is_open' => true
            ]
        );

        Notifikasi::create([
            'title' => 'Periode Triwulan',
            'message' => 'Triwulan ke-' . $request->triwulan . ' Tahun ' . $request->tahun,
        ]);

        return redirect()->back()->with('success', 'Data Periode berhasil disimpan.');
    }

    // BARU: Method untuk Buka/Tutup via Tombol Switch
    public function togglePeriod($id)
    {
        $period = TriwulanPeriod::findOrFail($id);

        // Ubah status
        $period->is_open = !$period->is_open;
        $period->save();

        Notifikasi::create([
            'title' => 'Periode Triwulan',
            'message' => Auth::user()->name . ($period->is_open ? ' Membuka Periode Upload' : ' Menutup periode Upload'),
        ]);
        // Return JSON agar tidak refresh halaman
        return response()->json([
            'success' => true,
            'is_open' => $period->is_open,
            'message' => $period->is_open ? 'Periode berhasil DIBUKA' : 'Periode berhasil DITUTUP'
        ]);
    }

    // ... method store, verify, downloadTemplate TETAP SAMA ...
    public function store(Request $request)
    {
        $request->validate([
            'file_laporan_1' => 'nullable|mimes:pdf,doc,docx,xls,xlsx|max:102400',
            'file_laporan_2' => 'nullable|mimes:pdf,doc,docx,xls,xlsx|max:102400',
            'file_laporan_3' => 'nullable|mimes:pdf,doc,docx,xls,xlsx|max:102400',
            'file_laporan_4' => 'nullable|mimes:pdf,doc,docx,xls,xlsx|max:102400',
            'keterangan_opd' => 'nullable|string',
            'period_id' => 'required|exists:triwulan_periods,id',
        ]);

        // Minimal 1 file harus diupload
        if (!$request->hasFile('file_laporan_1') && !$request->hasFile('file_laporan_2') && !$request->hasFile('file_laporan_3') && !$request->hasFile('file_laporan_4')) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Minimal 1 file laporan harus diunggah.'], 422);
            }
            return redirect()->back()->withErrors(['file_laporan' => 'Minimal 1 file laporan harus diunggah.']);
        }

        $userId = Auth::id();

        // --- PROSES UPLOAD 4 FILE ---
        $filePaths = ['file_path' => null, 'file_path_2' => null, 'file_path_3' => null, 'file_path_4' => null];
        $fileFields = [
            'file_laporan_1' => 'file_path',
            'file_laporan_2' => 'file_path_2',
            'file_laporan_3' => 'file_path_3',
            'file_laporan_4' => 'file_path_4',
        ];

        foreach ($fileFields as $inputName => $dbColumn) {
            if ($request->hasFile($inputName)) {
                $file = $request->file($inputName);
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeName = Str::slug($originalName);
                $extension = $file->getClientOriginalExtension();
                $fileName = $safeName . '_bapperidaMrk' . rand(1000, 9999) . '.' . $extension;
                $filePaths[$dbColumn] = $file->storeAs('laporan_triwulan', $fileName, 'public');
            }
        }

        // Cari laporan yang BELUM disetujui (bisa di-update)
        $laporan = LaporanTriwulan::where('user_id', $userId)
            ->where('period_id', $request->period_id)
            ->where('status', '!=', 'DISETUJUI')
            ->first();

        if ($laporan) {
            // Simpan history sebelum update
            LaporanHistory::create([
                'laporan_id' => $laporan->id,
                'file_path' => $laporan->file_path,
                'file_path_2' => $laporan->file_path_2,
                'file_path_3' => $laporan->file_path_3,
                'file_path_4' => $laporan->file_path_4,
                'keterangan_opd' => $laporan->keterangan_opd,
                'catatan_admin' => $laporan->catatan_admin,
                'status_snapshot' => $laporan->status
            ]);

            // Update: hanya timpa file yang diupload baru, sisanya tetap
            $updateData = [
                'keterangan_opd' => $request->keterangan_opd,
                'status' => 'MENUNGGU',
                'catatan_admin' => null,
                'verified_at' => null
            ];

            foreach ($filePaths as $col => $path) {
                if ($path !== null) {
                    $updateData[$col] = $path;
                }
            }

            $laporan->update($updateData);

            $message = 'Laporan revisi berhasil diunggah.';
        } else {
            // Buat laporan baru (termasuk jika yang lama sudah DISETUJUI)
            $createData = [
                'user_id' => $userId,
                'period_id' => $request->period_id,
                'file_path' => $filePaths['file_path'] ?? '',
                'file_path_2' => $filePaths['file_path_2'],
                'file_path_3' => $filePaths['file_path_3'],
                'file_path_4' => $filePaths['file_path_4'],
                'keterangan_opd' => $request->keterangan_opd,
                'status' => 'MENUNGGU'
            ];

            LaporanTriwulan::create($createData);

            $message = 'Laporan berhasil diunggah.';
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    // ... verify & downloadTemplate copy dari sebelumnya ...
    public function verify(Request $request, $id)
    {
        $laporan = LaporanTriwulan::findOrFail($id);
        if ($request->action == 'revisi') {
            $laporan->update(['status' => 'REVISI', 'catatan_admin' => $request->catatan_admin]);
        } else {
            $laporan->update(['status' => 'DISETUJUI', 'verified_at' => now(), 'catatan_admin' => 'Laporan diterima.']);
        }
        return redirect()->back()->with('success', 'Status laporan diperbarui.');
    }

    public function downloadTemplate($slot)
    {
        $template = MasterTemplateTriwulan::where('is_active', true)
            ->where('slot', $slot)
            ->first();

        if (!$template) {
            return back()->with('error', 'Template untuk slot ini belum tersedia.');
        }
        try {
            // Dapatkan path fisik file pada disk 'public'
            $filePath = Storage::disk('public')->path($template->file_path);

            // Pastikan file benar-benar ada
            if (!file_exists($filePath)) {
                return back()->with('error', 'File template tidak ditemukan di server.');
            }

            // Gunakan response()->download untuk mengirim file
            return response()->download($filePath, $template->judul ?? basename($template->file_path));
        } catch (\Exception $e) {
            return back()->with('error', 'File template tidak ditemukan di server.');
        }
    }

    public function getHistory($id)
    {
        $histories = LaporanHistory::where('laporan_id', $id)->latest()->get();
        return response()->json($histories);
    }

    public function uploadTemplate(Request $request)
    {
        $request->validate([
            'file_template' => 'required|mimes:doc,docx,xls,xlsx,pdf|max:102400', // Max 100MB
            'slot' => 'required|in:1,2,3,4',
        ]);

        $slot = $request->slot;

        // 1. Nonaktifkan template lama HANYA pada slot yang sama
        MasterTemplateTriwulan::where('slot', $slot)->update(['is_active' => false]);

        // 2. Upload file baru
        $file = $request->file('file_template');
        $originalName = $file->getClientOriginalName();
        $path = $file->storeAs('templates_triwulan', 'MASTER_SLOT' . $slot . '_' . time() . '_' . $originalName, 'public');

        // 3. Simpan ke database
        MasterTemplateTriwulan::create([
            'judul' => $originalName,
            'file_path' => $path,
            'is_active' => true,
            'slot' => $slot,
        ]);

        $slotNames = [1 => 'Indikator', 2 => 'Realisasi', 3 => 'OPD', 4 => 'Distrik'];
        return redirect()->back()->with('success', 'Template ' . $slotNames[$slot] . ' berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $laporan = LaporanTriwulan::findOrFail($id);
        $user = Auth::user();

        // Hanya boleh hapus jika belum disetujui
        if ($laporan->status === 'DISETUJUI') {
            return response()->json(['success' => false, 'message' => 'Laporan yang sudah disetujui tidak bisa dihapus.'], 403);
        }

        // OPD hanya bisa hapus miliknya sendiri
        if ($user->role === 'opd' && $laporan->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses untuk menghapus laporan ini.'], 403);
        }

        // Hapus semua file dari storage
        foreach (['file_path', 'file_path_2', 'file_path_3', 'file_path_4'] as $col) {
            if ($laporan->$col && Storage::disk('public')->exists($laporan->$col)) {
                Storage::disk('public')->delete($laporan->$col);
            }
        }

        // Hapus history terkait
        LaporanHistory::where('laporan_id', $laporan->id)->delete();

        // Hapus laporan
        $laporan->delete();

        return response()->json(['success' => true, 'message' => 'Laporan berhasil dihapus.']);
    }
}
