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
                'deskripsi' => 'PJJ Pendidikan Agama Islam S1',
            ],
            [
                'kode_jurusan' => 'PJJPAI-S2',
                'nama_jurusan' => 'S2 PJJ Pendidikan Agama Islam',
                'deskripsi' => 'PJJ Pendidikan Agama Islam S2',
            ],
        ];

        foreach ($jurusan as $data) {
            Jurusan::create($data);
        }
    }
}