<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use Illuminate\Database\Seeder;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jurusan = [
            [
                'kode_jurusan' => 'PJJPAI-S1',
                'nama_jurusan' => 'S1 PJJ Pendidikan Agama Islam',
                'deskripsi' => 'Program Studi S1 PJJ Pendidikan Agama Islam',
            ],
            [
                'kode_jurusan' => 'PJJPAI-S2',
                'nama_jurusan' => 'S2 PJJ Pendidikan Agama Islam',
                'deskripsi' => 'Program Studi S2 PJJ Pendidikan Agama Islam',
            ],
            [
                'kode_jurusan' => 'PJJPBA',
                'nama_jurusan' => 'PJJ Pendidikan Bahasa Arab',
                'deskripsi' => 'Program Studi PJJ Pendidikan Bahasa Arab',
            ],
            [
                'kode_jurusan' => 'PJJSPI',
                'nama_jurusan' => 'PJJ Sejarah Peradaban Islam',
                'deskripsi' => 'Program Studi PJJ Sejarah Peradaban Islam',
            ],
            [
                'kode_jurusan' => 'PJJHKI',
                'nama_jurusan' => 'PJJ Hukum Keluarga Islam',
                'deskripsi' => 'Program Studi PJJ Hukum Keluarga Islam',
            ],
            [
                'kode_jurusan' => 'PJJPGMI',
                'nama_jurusan' => 'PJJ Pendidikan Guru Madrasah Ibtidaiyah',
                'deskripsi' => 'Program Studi PJJ Pendidikan Guru Madrasah Ibtidaiyah',
            ],
        ];

        foreach ($jurusan as $data) {
            Jurusan::updateOrCreate(
                ['kode_jurusan' => $data['kode_jurusan']],
                $data
            );
        }
    }
}
