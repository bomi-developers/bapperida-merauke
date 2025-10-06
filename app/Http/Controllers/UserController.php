<?php

namespace App\Http\Controllers;

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
}
