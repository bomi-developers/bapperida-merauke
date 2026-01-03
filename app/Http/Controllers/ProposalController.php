<?php

namespace App\Http\Controllers;

use App\Models\ProposalInovasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProposalInovasiMail;
use App\Models\Notifikasi;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProposalController extends Controller
{
    public function index()
    {

        return view('pages.proposal_inovasi.index');
    }
    public function getData(Request $request)
    {
        $query = ProposalInovasi::query();

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('judul', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $proposals = $query->orderBy('created_at', 'desc')->paginate(10);

        // Stats
        $stats = [
            'total' => ProposalInovasi::count(),
            'pending' => ProposalInovasi::where('status', 'pending')->count(),
            'approved' => ProposalInovasi::where('status', 'approved')->count(),
            'rejected' => ProposalInovasi::where('status', 'rejected')->count(),
        ];

        return response()->json([
            'data' => $proposals->items(),
            'links' => $proposals->linkCollection()->toArray(),
            'stats' => $stats
        ]);
    }
    public function show($id)
    {
        $proposal = ProposalInovasi::findOrFail($id);
        return response()->json($proposal);
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'catatan' => 'nullable|string'
        ]);

        $proposal = ProposalInovasi::findOrFail($id);
        $proposal->update([
            'status' => $request->status,
            'catatan' => $request->catatan
        ]);

        Notifikasi::create([
            'title' => 'Proposal Inovasi ' . $request->status,
            'message' => 'Proposal dari ' . $proposal->nama . ' Telah di ' . $request->status . ' Oleh ' . Auth::user()->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diupdate'
        ]);
    }
    public function download($id)
    {
        $proposal = ProposalInovasi::findOrFail($id);


        if (!$proposal->file_path || !Storage::disk('public')->exists($proposal->file_path)) {
            return back()->with('error', 'File tidak ditemukan atau telah dihapus.');
        }

        $path = storage_path('app/public/' . $proposal->file_path);

        Notifikasi::create([
            'title'   => 'Download proposal inovasi',
            'message' => 'Pengguna mendownload proposal : ' . $proposal->judul,
        ]);

        $slug = Str::slug($proposal->judul ?? 'proposal-inovasi');
        $extension = pathinfo($proposal->file_path, PATHINFO_EXTENSION);
        $shortCode = substr(sha1($proposal->id), 0, 4);

        $newFilename = "{$slug}-{$shortCode}.{$extension}";

        return response()->download($path, $newFilename);
    }
    public function destroy($id)
    {
        $proposal = ProposalInovasi::findOrFail($id);

        // Delete file
        if ($proposal->file_path && Storage::disk('public')->exists($proposal->file_path)) {
            Storage::disk('public')->delete($proposal->file_path);
        }

        $proposal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Proposal berhasil dihapus'
        ]);
    }



    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'judul' => 'required',
            'latar_belakang' => 'required|min:100',
            'email' => 'required|email',
            'no_hp' => 'required',
            'file' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'g-recaptcha-response' => 'required|captcha',
            'ide_inovasi' => 'required',
            'tujuan_inovasi' => 'required',
            'target_perubahan' => 'required',
            'stakeholder' => 'required',
            'sdm' => 'required',
            'penerima_manfaat' => 'required',
            'kebaruan' => 'required',
            'deskripsi_ide' => 'required',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'judul.required' => 'Judul wajib diisi.',
            'latar_belakang.required' => 'Latar belakang wajib diisi.',
            'latar_belakang.min' => 'Latar belakang harus minimal 100 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'file.required' => 'File proposal wajib diunggah.',
            'file.mimes' => 'File harus berformat PDF, DOC, atau DOCX.',
            'file.max' => 'Ukuran file maksimal 2MB.',
            'g-recaptcha-response.required' => 'Silakan verifikasi CAPTCHA terlebih dahulu.',
            'g-recaptcha-response.captcha' => 'Verifikasi CAPTCHA gagal, coba lagi.',
            'ide_inovasi.required' => 'Ide inovasi wajib diisi',
            'tujuan_inovasi.required' => 'tujuan inovasi wajib diisi',
            'target_perubahan.required' => 'target perubahan wajib diisi',
            'stakeholder.required' => 'stakeholder wajib diisi',
            'sdm.required' => 'sumber daya manusia wajib diisi',
            'penerima_manfaat.required' => 'penerima manfaat wajib diisi',
            'kebaruan.required' => 'kebaruan wajib diisi',
            'deskripsi_ide.required' => 'deskripsi singkat ide wajib diisi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {

            $path = $request->file('file')->store('proposal', 'public');

            $proposal = ProposalInovasi::create([
                'nama' => $request->nama,
                'judul' => $request->judul,
                'latar_belakang' => $request->latar_belakang,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'link_video' => $request->link_video,
                'file' => $path,
                'ide_inovasi' => $request->ide_inovasi,
                'tujuan_inovasi' => $request->tujuan_inovasi,
                'target_perubahan' => $request->target_perubahan,
                'stakeholder' => $request->stakeholder,
                'sdm' => $request->sdm,
                'penerima_manfaat' => $request->penerima_manfaat,
                'kebaruan' => $request->kebaruan,
                'deskripsi_ide' => $request->deskripsi_ide,
                'keterangan' => $request->keterangan,
            ]);

            Notifikasi::create([
                'title' => 'Proposal Inovasi Baru',
                'message' => 'Pengajuan proposal inovasi baru dari ' . $proposal->nama,
            ]);

            // --- KIRIM EMAIL ---
            try {
                Mail::to($proposal->email)->send(new ProposalInovasiMail($proposal));
            } catch (Exception $e) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Proposal berhasil disimpan, tetapi gagal mengirim email konfirmasi.',
                    'error' => $e->getMessage(),
                ], 200);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menyimpan pengajuan proposal. Silakan cek email Anda untuk konfirmasi.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data atau file.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function checkEmail(Request $request)
    {
        $email = $request->query('email');
        $apiKey = env('HUNTER_API_KEY');

        if (!$email) {
            return response()->json(['valid' => false, 'message' => 'Email tidak boleh kosong'], 400);
        }

        try {
            $response = Http::timeout(5)->get("https://api.hunter.io/v2/email-verifier", [
                'email' => $email,
                'api_key' => $apiKey,
            ]);

            if ($response->failed()) {
                return response()->json(['valid' => false, 'message' => 'Gagal memeriksa email'], 500);
            }

            $data = $response->json();

            $valid = isset($data['data']['status']) && $data['data']['status'] === 'valid';

            return response()->json([
                'valid' => $valid,
                'result' => $data['data'] ?? [],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'valid' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
