<?php

namespace App\Http\Controllers;

use App\Models\ProfileDinas;
use Illuminate\Http\Request;

class ProfileDinasController extends Controller
{
    public function index()
    {
        $profile = ProfileDinas::first();
        return view('pages.profile_dinas.index', compact('profile'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'sejarah' => 'nullable|string',
            'tugas_fungsi' => 'nullable|string',
            'struktur_organisasi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('struktur_organisasi')) {
            $data['struktur_organisasi'] = $request->file('struktur_organisasi')->store('struktur', 'public');
        }

        ProfileDinas::updateOrCreate(['id' => 1], $data);

        return response()->json(['message' => 'Profil berhasil disimpan!']);
    }
}