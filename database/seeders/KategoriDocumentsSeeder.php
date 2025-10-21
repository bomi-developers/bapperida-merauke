<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KategoriDocument;
use FontLib\Table\Type\name;

class KategoriDocumentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            'RPJPD',
            'RPJMD',
            'RKPD',
            'Renstra PD',
            'Renja PD',
            'Dokumen Lainnya',
        ];

        foreach ($kategoris as $kategori) {
            KategoriDocument::updateOrCreate(
                ['nama_kategori' => $kategori]
            );
        }
    }
}
