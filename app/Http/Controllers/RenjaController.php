<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RenjaDocument;
use App\Models\RenjaHistory;
use App\Models\MasterTahapanRkpd;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RenjaController extends Controller
{
    // === HALAMAN UTAMA (FILTER AJAX) ===
    public function index(Request $request)
    {
        $user = Auth::user();

        $tahapanAktif = MasterTahapanRkpd::where('is_active', true)->first();
        $allTahapan = MasterTahapanRkpd::orderBy('tahun', 'desc')->get();

        $myRenja = null;
        if ($tahapanAktif && $user->role == 'opd') {
            $myRenja = RenjaDocument::where('opd_id', $user->id)
                ->where('tahapan_id', $tahapanAktif->id)
                ->first();
        }

        // --- QUERY FILTER ---
        $query = RenjaDocument::with(['opd', 'tahapan', 'histories']);

        // 1. Filter Role
        if ($user->role == 'opd') {
            $query->where('opd_id', $user->id);
        }

        // 2. Filter Search (Nama OPD)
        if ($request->filled('search')) {
            $query->whereHas('opd', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // 3. Filter Status Global
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 4. Filter Tahapan
        if ($request->filled('tahapan_filter')) {
            $query->where('tahapan_id', $request->tahapan_filter);
        }

        $renjas = $query->latest()->paginate(10)->withQueryString();

        // --- RESPON AJAX (PENTING UNTUK SEARCH) ---
        if ($request->ajax()) {
            return view('pages.renja._table_list', compact('renjas', 'tahapanAktif', 'user'))->render();
        }

        return view('pages.renja.index', compact('renjas', 'tahapanAktif', 'allTahapan', 'myRenja'));
    }

    // === ADMIN: SETTING TAHAPAN ===
    public function updateTahapan(Request $request)
    {
        $rules = [
            'tahun' => 'required',
            'nama_tahapan' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'file_template' => 'nullable|mimes:doc,docx,xls,xlsx,pdf|max:20480'
        ];
        $request->validate($rules);

        if (!$request->filled('id') || ($request->filled('id') && MasterTahapanRkpd::find($request->id)->is_active)) {
            MasterTahapanRkpd::query()->update(['is_active' => false]);
        }

        $data = [
            'tahun' => $request->tahun,
            'nama_tahapan' => $request->nama_tahapan,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ];

        if ($request->hasFile('file_template')) {
            $file = $request->file('file_template');
            $filename = 'TEMPLATE_RKPD_' . $request->nama_tahapan . '_' . $request->tahun . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('templates_rkpd', $filename, 'public');
            $data['file_template_rkpd'] = $path;
        }

        if ($request->filled('id')) {
            $tahapan = MasterTahapanRkpd::findOrFail($request->id);
            $tahapan->update($data);
            $msg = 'Tahapan diperbarui.';
        } else {
            $data['is_active'] = true;
            MasterTahapanRkpd::create($data);
            $msg = 'Tahapan baru dibuat.';
        }

        return back()->with('success', $msg);
    }

    public function toggleTahapan($id)
    {
        $tahapan = MasterTahapanRkpd::findOrFail($id);

        if (!$tahapan->is_active) {
            MasterTahapanRkpd::query()->update(['is_active' => false]);
            $tahapan->is_active = true;
            $message = "Tahapan DIBUKA.";
        } else {
            $tahapan->is_active = false;
            $message = "Tahapan DITUTUP.";
        }
        $tahapan->save();

        return response()->json([
            'success' => true,
            'is_active' => $tahapan->is_active,
            'message' => $message
        ]);
    }

    // === OPD: UPLOAD (SPLIT LOGIC) ===
    public function store(Request $request)
    {
        $renja = RenjaDocument::where('opd_id', Auth::id())
            ->where('tahapan_id', $request->tahapan_id)
            ->first();

        $rules = ['tahapan_id' => 'required|exists:master_tahapan_rkpds,id'];

        if (!$renja) {
            $rules['file_dokumen'] = 'required|mimes:pdf,doc,docx|max:51200';
            $rules['file_matriks'] = 'required|mimes:xls,xlsx|max:51200';
        } else {
            if ($renja->status_dokumen != 'DISETUJUI') {
                $rules['file_dokumen'] = 'nullable|mimes:pdf,doc,docx|max:51200';
            }
            if ($renja->status_matriks != 'DISETUJUI') {
                $rules['file_matriks'] = 'nullable|mimes:xls,xlsx|max:51200';
            }
        }

        $request->validate($rules);

        $opdName = Str::slug(Auth::user()->name);
        $time = time();
        $docPath = null;
        $matriksPath = null;

        if ($request->hasFile('file_dokumen')) {
            $docFile = $request->file('file_dokumen');
            if ($docFile->isValid()) {
                $docName = "RENJA_DOC_{$opdName}_{$time}." . $docFile->getClientOriginalExtension();
                $docPath = $docFile->storeAs('renja_uploads/dokumen', $docName, 'public');
            }
        }

        if ($request->hasFile('file_matriks')) {
            $matriksFile = $request->file('file_matriks');
            if ($matriksFile->isValid()) {
                $matriksName = "RENJA_MATRIKS_{$opdName}_{$time}." . $matriksFile->getClientOriginalExtension();
                $matriksPath = $matriksFile->storeAs('renja_uploads/matriks', $matriksName, 'public');
            }
        }

        if ($renja) {
            RenjaHistory::create([
                'renja_document_id' => $renja->id,
                'file_dokumen_renja' => $renja->file_dokumen_renja,
                'file_matriks_renja' => $renja->file_matriks_renja,
                'status_snapshot' => $renja->status,
                'catatan_verifikasi' => "Dok: " . ($renja->catatan_dokumen ?? '-') . " | Matriks: " . ($renja->catatan_matriks ?? '-'),
                'file_matriks_verifikasi' => $renja->file_matriks_verifikasi,
                'file_dokumen_verifikasi' => $renja->file_dokumen_verifikasi
            ]);

            $updateData = [];
            if ($docPath) {
                $updateData['file_dokumen_renja'] = $docPath;
                $updateData['status_dokumen'] = 'MENUNGGU';
                $updateData['catatan_dokumen'] = null;
                $updateData['file_dokumen_verifikasi'] = null;
            }
            if ($matriksPath) {
                $updateData['file_matriks_renja'] = $matriksPath;
                $updateData['status_matriks'] = 'MENUNGGU';
                $updateData['catatan_matriks'] = null;
                $updateData['file_matriks_verifikasi'] = null;
            }
            if ($docPath || $matriksPath) {
                $updateData['status'] = 'MENUNGGU';
                $updateData['verified_at'] = null;
            }
            $renja->update($updateData);
            $msg = 'Dokumen berhasil diperbarui.';
        } else {
            if (!$docPath || !$matriksPath) {
                return back()->with('error', 'Gagal mengupload file. Cek ukuran file.');
            }
            RenjaDocument::create([
                'opd_id' => Auth::id(),
                'tahapan_id' => $request->tahapan_id,
                'file_dokumen_renja' => $docPath,
                'file_matriks_renja' => $matriksPath,
                'status' => 'MENUNGGU',
                'status_dokumen' => 'MENUNGGU',
                'status_matriks' => 'MENUNGGU'
            ]);
            $msg = 'Dokumen Renja berhasil diupload.';
        }

        return back()->with('success', $msg);
    }

    public function verify(Request $request, $id)
    {
        $renja = RenjaDocument::findOrFail($id);
        $request->validate([
            'status_dokumen' => 'required|in:DISETUJUI,REVISI',
            'status_matriks' => 'required|in:DISETUJUI,REVISI',
            'file_feedback_dokumen' => 'nullable|mimes:pdf,doc,docx|max:20480',
            'file_feedback_matriks' => 'nullable|mimes:xls,xlsx|max:20480'
        ]);

        $data = [
            'status_dokumen' => $request->status_dokumen,
            'catatan_dokumen' => $request->catatan_dokumen,
            'status_matriks' => $request->status_matriks,
            'catatan_matriks' => $request->catatan_matriks,
        ];

        if ($request->hasFile('file_feedback_dokumen')) {
            $file = $request->file('file_feedback_dokumen');
            $path = $file->storeAs('renja_feedback', 'FEEDBACK_DOC_' . time() . '_' . $file->getClientOriginalName(), 'public');
            $data['file_dokumen_verifikasi'] = $path;
        }
        if ($request->hasFile('file_feedback_matriks')) {
            $file = $request->file('file_feedback_matriks');
            $path = $file->storeAs('renja_feedback', 'FEEDBACK_MTX_' . time() . '_' . $file->getClientOriginalName(), 'public');
            $data['file_matriks_verifikasi'] = $path;
        }

        if ($request->status_dokumen == 'DISETUJUI' && $request->status_matriks == 'DISETUJUI') {
            $data['status'] = 'DISETUJUI';
            $data['verified_at'] = now();
            $data['catatan_verifikasi'] = 'Seluruh dokumen disetujui.';
        } else {
            $data['status'] = 'REVISI';
            $note = [];
            if ($request->status_dokumen == 'REVISI') $note[] = "Dokumen: " . ($request->catatan_dokumen ?? 'Revisi');
            if ($request->status_matriks == 'REVISI') $note[] = "Matriks: " . ($request->catatan_matriks ?? 'Revisi');
            $data['catatan_verifikasi'] = implode(' | ', $note);
        }

        $renja->update($data);
        return back()->with('success', 'Hasil verifikasi disimpan.');
    }

    public function getHistory($id)
    {
        $histories = RenjaHistory::where('renja_document_id', $id)->latest()->get();
        return response()->json($histories);
    }
}
