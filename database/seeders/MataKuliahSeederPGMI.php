<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use App\Models\MataKuliah;
use Illuminate\Database\Seeder;

class MataKuliahSeederPGMI extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jurusan = Jurusan::where('kode_jurusan', 'PJJPGMI')->firstOrFail();

        $mataKuliah = [
            [
                'kode_mata_kuliah' => 'PJJPGMI001',
                'nama_mata_kuliah' => 'Konsep Dasar Matematika',
                'semester' => 1,
                'sks' => 2,
            ],
            [
                'kode_mata_kuliah' => 'PJJPGMI002',
                'nama_mata_kuliah' => 'Pengembangan Bahan Ajar Digital di MI/SD',
                'semester' => 1,
                'sks' => 2,
            ],
            [
                'kode_mata_kuliah' => 'PJJPGMI003',
                'nama_mata_kuliah' => 'Konsep Dasar IPS',
                'semester' => 1,
                'sks' => 2,
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
