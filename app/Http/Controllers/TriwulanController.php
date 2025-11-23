<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\LaporanHistory;
use App\Models\TriwulanPeriod;
use App\Models\LaporanTriwulan;
use Illuminate\Support\Facades\Auth;
use App\Models\MasterTemplateTriwulan;
use Illuminate\Support\Facades\Storage;

class TriwulanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $masterTemplate = MasterTemplateTriwulan::where('is_active', true)->latest()->first();
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
            'masterTemplate',
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

        return redirect()->back()->with('success', 'Data Periode berhasil disimpan.');
    }

    // BARU: Method untuk Buka/Tutup via Tombol Switch
    public function togglePeriod($id)
    {
        $period = TriwulanPeriod::findOrFail($id);

        // Ubah status
        $period->is_open = !$period->is_open;
        $period->save();

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
            // Ubah max menjadi 51200 (50MB dalam Kilobytes)
            'file_laporan' => 'required|mimes:pdf,doc,docx,xls,xlsx|max:51200',
            'keterangan_opd' => 'nullable|string',
            'period_id' => 'required|exists:triwulan_periods,id',
        ]);

        $userId = Auth::id();

        // --- LOGIKA PENAMAAN FILE CUSTOM ---
        $file = $request->file('file_laporan');

        // Ambil nama asli tanpa ekstensi
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        // Bersihkan nama dari karakter aneh & spasi (opsional tapi disarankan agar URL aman)
        // Jika ingin benar-benar nama asli mentah, hapus Str::slug()
        $safeName = Str::slug($originalName);

        $extension = $file->getClientOriginalExtension();

        // Format: NamaFile_bapperidaMrk + 4 Angka Acak
        $fileName = $safeName . '_bapperidaMrk' . rand(1000, 9999) . '.' . $extension;

        // Simpan dengan storeAs
        $path = $file->storeAs('laporan_triwulan', $fileName, 'public');
        // -----------------------------------

        $laporan = LaporanTriwulan::where('user_id', $userId)
            ->where('period_id', $request->period_id)
            ->first();

        if ($laporan) {
            // ... (Logic Update/Revisi sama seperti sebelumnya) ...

            LaporanHistory::create([
                'laporan_id' => $laporan->id,
                'file_path' => $laporan->file_path,
                'keterangan_opd' => $laporan->keterangan_opd,
                'catatan_admin' => $laporan->catatan_admin,
                'status_snapshot' => $laporan->status
            ]);

            $laporan->update([
                'file_path' => $path, // Path baru dengan nama custom
                'keterangan_opd' => $request->keterangan_opd,
                'status' => 'MENUNGGU',
                'catatan_admin' => null,
                'verified_at' => null
            ]);

            $message = 'Laporan revisi berhasil diunggah.';
        } else {
            // ... (Logic Create Baru) ...
            LaporanTriwulan::create([
                'user_id' => $userId,
                'period_id' => $request->period_id,
                'file_path' => $path, // Path baru dengan nama custom
                'keterangan_opd' => $request->keterangan_opd,
                'status' => 'MENUNGGU'
            ]);

            $message = 'Laporan berhasil diunggah.';
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

    public function downloadTemplate()
    {
        $template = MasterTemplateTriwulan::where('is_active', true)->first();

        if (!$template) {
            return back()->with('error', 'Template belum tersedia.');
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
            'file_template' => 'required|mimes:doc,docx,xls,xlsx,pdf|max:20480', // Max 20MB
        ]);

        // 1. Nonaktifkan template lama (jika ada)
        MasterTemplateTriwulan::query()->update(['is_active' => false]);

        // 2. Upload file baru
        $file = $request->file('file_template');
        $originalName = $file->getClientOriginalName();
        $path = $file->storeAs('templates_triwulan', 'MASTER_' . time() . '_' . $originalName, 'public');

        // 3. Simpan ke database
        MasterTemplateTriwulan::create([
            'judul' => $originalName, // Atau set nama default
            'file_path' => $path,
            'is_active' => true
        ]);

        return redirect()->back()->with('success', 'Master Template berhasil diperbarui.');
    }
}
