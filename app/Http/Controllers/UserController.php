<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function pegawai()
    {
        $data = [
            'title' => 'Akun Pegawai',
        ];
        return view('pages.users.pegawai', $data);
    }
    public function admin()
    {
        $data = [
            'title' => 'Akun Super Administrator',
        ];
        return view('pages.users.admin', $data);
    }
    public function cekAkun($id)
    {
        $user = User::where('id_pegawai', $id)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Akun untuk pegawai belum ada, sikahkan mengisi form untuk membuat akun'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Akun ditemukan',
            'data' => $user
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'id_pegawai' => 'required|exists:pegawais,id',
            'username'   => 'required|string',
            'email'      => 'required|email',
            'role'       => 'required',
        ]);

        $user = User::where('id_pegawai', $request->id_pegawai)->first();

        if ($user) {
            $request->validate([
                'email' => 'required|email|unique:users,email,' . $user->id,
            ]);

            $user->update([
                'name'  => $request->username,
                'email' => $request->email,
                'role'  => $request->role,
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Akun berhasil diperbarui',
                'data'    => $user
            ], 200);
        } else {
            $request->validate([
                'email' => 'required|email|unique:users,email',
            ]);

            $user = User::create([
                'id_pegawai' => $request->id_pegawai,
                'name'       => $request->username,
                'email'      => $request->email,
                'role'       => $request->role,
                'password'   => bcrypt('PegawaiBAPPERIDA'),
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Akun berhasil dibuat',
                'data'    => $user
            ], 201);
        }
    }
    public function admin_data(Request $request)
    {
        $query = User::where('role', 'super_admin');

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json($users);
    }
}