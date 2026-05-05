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
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'Akun ditemukan',
            'data' => $user
        ]);
    }
    public function cekAkunOpd($id)
    {
        $user = User::where('id_opd', $id)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Akun untuk pegawai belum ada, sikahkan mengisi form untuk membuat akun'
            ], 200);
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
            'id_pegawai' => 'required',
            'username'   => 'required|string',
            'email'      => 'required|email',
            'role'       => 'required',
        ]);

        if ($request->role == 'opd') {
            $user = User::where('id_opd', $request->id_pegawai)->first();
        } else {
            $user = User::where('id_pegawai', $request->id_pegawai)->first();
        }

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

            // $user = User::create([
            //     'id_pegawai' => $request->id_pegawai,
            //     'name'       => $request->username,
            //     'email'      => $request->email,
            //     'role'       => $request->role,
            //     'password'   => bcrypt($request->role !== 'opd' ? 'PerangkatDaerahMerauke' : 'PegawaiBAPPERIDA'),
            // ]);
            $userData = [
                'name'     => $request->username,
                'email'    => $request->email,
                'role'     => $request->role,
                'password' => bcrypt($request->role == 'opd' ? 'PerangkatDaerahMerauke' : 'PegawaiBAPPERIDA'),
            ];

            if ($request->role === 'opd') {
                $userData['id_opd'] = $request->id_pegawai;
            } else {
                $userData['id_pegawai'] = $request->id_pegawai;
            }
            $user = User::create($userData);

            return response()->json([
                'status'  => true,
                'message' => 'Akun berhasil dibuat',
                'data'    => $user
            ], 200);
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
    public function admin_store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|string',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => $request->role,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Super Admin berhasil dibuat',
            'data'    => $user
        ], 200);
    }

    public function admin_update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name'  => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|string',
        ]);

        $userData = [
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ];

        if ($request->filled('password')) {
            $userData['password'] = bcrypt($request->password);
        }

        $user->update($userData);

        return response()->json([
            'status'  => true,
            'message' => 'Super Admin berhasil diperbarui',
            'data'    => $user
        ], 200);
    }

    public function admin_destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Super Admin berhasil dihapus'
        ], 200);
    }
}
