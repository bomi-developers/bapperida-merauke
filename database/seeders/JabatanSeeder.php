<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Jabatan;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jabatans = [
            'Jabatan Fungsional Umum',
            'Jabatan Fungsional Tertentu',
            'Kepala Sub Bagian',
            'Kepala Seksi',
            'Kepala Bidang',
            'Kepala Bagian',
            'Sekretaris',
            'Kepala Dinas',
            'Staf Administrasi',
            'Analis Kepegawaian',
            'Analis Keuangan',
            'Analis Data',
            'Bendahara',
            'Pengadministrasi Umum',
            'Pengelola Kepegawaian',
            'Pengelola Barang Milik Negara',
            'Perencana',
            'Pengawas',
            'Pelaksana Lapangan',
            'Peneliti',
            'Penyuluh',
            'Dokter',
            'Perawat',
            'Bidang Teknologi Informasi',
            'Guru',
            'Dosen'
        ];

        foreach ($jabatans as $jabatan) {
            Jabatan::create(['jabatan' => $jabatan]);
        }
    }
}
