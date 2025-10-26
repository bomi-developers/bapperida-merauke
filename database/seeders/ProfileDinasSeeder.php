<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileDinasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('profile_dinas')->insert([
            'visi' => 'Menjadi lembaga perencana pembangunan daerah yang profesional, inovatif, dan berintegritas dalam mendukung pembangunan berkelanjutan untuk meningkatkan kesejahteraan masyarakat.',
            'misi' => '<ol class="list-decimal list-inside space-y-2 text-gray-700">
                            <li>Menyusun rencana pembangunan daerah yang terintegrasi, partisipatif, dan berbasis data.</li>
                            <li>Mendorong sinergi antar instansi dan pemangku kepentingan dalam pelaksanaan pembangunan daerah.</li>
                            <li>Mengembangkan inovasi dan solusi pembangunan yang berorientasi pada kebutuhan masyarakat dan lingkungan.</li>
                            <li>Meningkatkan kapasitas sumber daya manusia di bidang perencanaan pembangunan daerah.</li>
                            <li>Menjamin transparansi dan akuntabilitas dalam proses perencanaan dan pengendalian pembangunan.</li>
                        </ol>',
            'sejarah' => 'Badan Perencanaan Pembangunan, Penelitian dan Pengembangan Daerah (BAPPERIDA) Merauke dibentuk untuk merumuskan arah pembangunan daerah dan mengintegrasikan hasil penelitian dengan kebijakan publik.',
            'tugas_fungsi' => 'Melaksanakan penyusunan rencana pembangunan daerah, penelitian, serta pengembangan kebijakan yang mendukung pencapaian visi daerah.',
            'struktur_organisasi' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
