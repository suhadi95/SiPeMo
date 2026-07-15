<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use App\Models\MataKuliah;
use Illuminate\Database\Seeder;

class MataKuliahSeederS1PAI extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jurusan = Jurusan::where('kode_jurusan', 'PJJPAI-S1')->firstOrFail();

        $mataKuliah = [
            [
                'kode_mata_kuliah' => 'PJJPAIS1003',
                'nama_mata_kuliah' => 'Tafsir Hadits Tarbawi',
                'semester' => 3,
                'sks' => 3,
            ],
            [
                'kode_mata_kuliah' => 'ITK60005J',
                'nama_mata_kuliah' => 'Micro Teaching',
                'semester' => 5,
                'sks' => 3,
            ],
            [
                'kode_mata_kuliah' => 'PAI60044J',
                'nama_mata_kuliah' => 'Analisis Kurikulum dan Silabus PAI Satuan Pendidikan',
                'semester' => 6,
                'sks' => 3,
            ],
            [
                'kode_mata_kuliah' => 'PJJPAIS1004',
                'nama_mata_kuliah' => 'Bahasa Inggris',
                'semester' => 1,
                'sks' => 3,
            ],
            [
                'kode_mata_kuliah' => 'PJJPAIS1005',
                'nama_mata_kuliah' => 'Bahasa Arab',
                'semester' => 1,
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
