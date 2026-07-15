<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use App\Models\MataKuliah;
use Illuminate\Database\Seeder;

class MataKuliahSeederPBA extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jurusan = Jurusan::where('kode_jurusan', 'PJJPBA')->firstOrFail();

        $mataKuliah = [
            [
                'kode_mata_kuliah' => 'PJJPBA001',
                'nama_mata_kuliah' => 'Multimedia Pendidikan',
                'semester' => 1,
                'sks' => 3,
            ],
            [
                'kode_mata_kuliah' => 'PJJPBA002',
                'nama_mata_kuliah' => 'Literasi Digital Pembelajaran Bahasa Arab',
                'semester' => 1,
                'sks' => 3,
            ],
            [
                'kode_mata_kuliah' => 'PJJPBA003',
                'nama_mata_kuliah' => 'Pengantar Teknologi Mobile',
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
