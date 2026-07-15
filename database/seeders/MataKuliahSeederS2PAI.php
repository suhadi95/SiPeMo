<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use App\Models\MataKuliah;
use Illuminate\Database\Seeder;

class MataKuliahSeederS2PAI extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jurusan = Jurusan::where('kode_jurusan', 'PJJPAI-S2')->firstOrFail();

        $mataKuliah = [
            [
                'kode_mata_kuliah' => 'JPA825028',
                'nama_mata_kuliah' => 'Metodologi Penelitian Evaluasi dan Pengembangan PAI',
                'semester' => 2,
                'sks' => 3,
            ],
            [
                'kode_mata_kuliah' => 'JPA825018',
                'nama_mata_kuliah' => 'Manajemen Mutu PAI',
                'semester' => 2,
                'sks' => 3,
            ],
            [
                'kode_mata_kuliah' => 'JPA825029',
                'nama_mata_kuliah' => 'Pengembangan Metodologi PAI',
                'semester' => 2,
                'sks' => 3,
            ],
        ];

        $this->seedMataKuliah($jurusan->id, $mataKuliah);
    }

    private function seedMataKuliah(int $jurusanId, array $mataKuliah): void
    {
        foreach ($mataKuliah as $data) {
            MataKuliah::updateOrCreate(
                [
                    'jurusan_id' => $jurusanId,
                    'kode_mata_kuliah' => $data['kode_mata_kuliah'],
                ],
                [
                    'nama_mata_kuliah' => $data['nama_mata_kuliah'],
                    'semester' => $data['semester'],
                    'sks' => $data['sks'],
                    'deskripsi' => $data['nama_mata_kuliah'],
                ]
            );
        }
    }
}
