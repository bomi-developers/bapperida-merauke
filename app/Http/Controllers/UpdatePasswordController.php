<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'password' => 'required',              // password lama
            'new_password' => 'required|min:6',    // password baru
        ]);

        $user = Auth::user();

        // Cek password lama
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Password lama tidak sesuai'
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Password berhasil diperbarui'
        ], 200);
    }
}