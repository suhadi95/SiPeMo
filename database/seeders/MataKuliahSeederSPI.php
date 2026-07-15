<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use App\Models\MataKuliah;
use Illuminate\Database\Seeder;

class MataKuliahSeederSPI extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jurusan = Jurusan::where('kode_jurusan', 'PJJSPI')->firstOrFail();

        $mataKuliah = [
            [
                'kode_mata_kuliah' => 'PJJSPI001',
                'nama_mata_kuliah' => 'Metodologi Pembelajaran SKI',
                'semester' => 1,
                'sks' => 3,
            ],
            [
                'kode_mata_kuliah' => 'PJJSPI002',
                'nama_mata_kuliah' => 'Historiografi Islam',
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
