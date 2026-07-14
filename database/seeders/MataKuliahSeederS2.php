<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use App\Models\MataKuliah;
use Illuminate\Database\Seeder;

class MataKuliahSeederS2 extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $S2PJJPAI = Jurusan::where('kode_jurusan', 'PJJPAI-S2')->first();

        $mataKuliah = [
            // Semester 1
            [
                'jurusan_id' => $S2PJJPAI->id,
                'kode_mata_kuliah' => 'JPA825021',
                'nama_mata_kuliah' => 'Hukum dan Kebijakan Pendidikan Islam',
                'semester' => 1,
                'sks' => 3,
                'deskripsi' => 'Hukum dan Kebijakan Pendidikan Islam',
            ],
            [
                'jurusan_id' => $S2PJJPAI->id,
                'kode_mata_kuliah' => 'JPA825015',
                'nama_mata_kuliah' => 'Filsafat Pendidikan Islam',
                'semester' => 1,
                'sks' => 2,
                'deskripsi' => 'Filsafat Pendidikan Islam',
            ],
            [
                'jurusan_id' => $S2PJJPAI->id,
                'kode_mata_kuliah' => 'JPA825004',
                'nama_mata_kuliah' => 'Sejarah Pemikiran dan Peradaban Islam',
                'semester' => 1,
                'sks' => 3,
                'deskripsi' => 'Sejarah Pemikiran dan Peradaban Islam',
            ],
            [
                'jurusan_id' => $S2PJJPAI->id,
                'kode_mata_kuliah' => 'JPA825006',
                'nama_mata_kuliah' => 'Studi Literatur Pendidikan (Bahasa Arab)',
                'semester' => 1,
                'sks' => 2,
                'deskripsi' => 'Studi Literatur Pendidikan (Bahasa Arab)',
            ],
            [
                'jurusan_id' => $S2PJJPAI->id,
                'kode_mata_kuliah' => 'JPA825007',
                'nama_mata_kuliah' => 'Studi Literatur Pendidikan (Bahasa Inggris)',
                'semester' => 1,
                'sks' => 2,
                'deskripsi' => 'Studi Literatur Pendidikan (Bahasa Inggris)',
            ],

            // Semester 2
            [
                'jurusan_id' => $S2PJJPAI->id,
                'kode_mata_kuliah' => 'JPA825014',
                'nama_mata_kuliah' => 'Pengembangan Kurikulum PAI',
                'semester' => 2,
                'sks' => 3,
                'deskripsi' => 'Pengembangan Kurikulum PAI',
            ],
            [
                'jurusan_id' => $S2PJJPAI->id,
                'kode_mata_kuliah' => 'JPA825028',
                'nama_mata_kuliah' => 'Metodologi Penelitian PAI',
                'semester' => 2,
                'sks' => 3,
                'deskripsi' => 'Metodologi Penelitian PAI',
            ],
            [
                'jurusan_id' => $S2PJJPAI->id,
                'kode_mata_kuliah' => 'JPA825027',
                'nama_mata_kuliah' => 'Penulisan Karya Ilmiah dan Strategi Publikasi',
                'semester' => 2,
                'sks' => 3,
                'deskripsi' => 'Penulisan Karya Ilmiah dan Strategi Publikasi',
            ],
            [
                'jurusan_id' => $S2PJJPAI->id,
                'kode_mata_kuliah' => 'JPA825017',
                'nama_mata_kuliah' => 'Evaluasi Pembelajaran PAI',
                'semester' => 2,
                'sks' => 3,
                'deskripsi' => 'Evaluasi Pembelajaran PAI',
            ],
            [
                'jurusan_id' => $S2PJJPAI->id,
                'kode_mata_kuliah' => 'JPA825010',
                'nama_mata_kuliah' => 'Model dan Desain Pembelajaran PAI',
                'semester' => 2,
                'sks' => 3,
                'deskripsi' => 'Model dan Desain Pembelajaran PAI',
            ],
            [
                'jurusan_id' => $S2PJJPAI->id,
                'kode_mata_kuliah' => 'JPA825018',
                'nama_mata_kuliah' => 'Manajemen Mutu Pendidikan',
                'semester' => 2,
                'sks' => 3,
                'deskripsi' => 'Manajemen Mutu Pendidikan',
            ],
            [
                'jurusan_id' => $S2PJJPAI->id,
                'kode_mata_kuliah' => 'JPA825008',
                'nama_mata_kuliah' => 'Psikologi Pendidikan dan Bimbingan',
                'semester' => 2,
                'sks' => 2,
                'deskripsi' => 'Psikologi Pendidikan dan Bimbingan',
            ],

            // Semester 3
            [
                'jurusan_id' => $S2PJJPAI->id,
                'kode_mata_kuliah' => 'JPA825013',
                'nama_mata_kuliah' => 'Strategi Pengembangan Keberagamaan',
                'semester' => 3,
                'sks' => 3,
                'deskripsi' => 'Strategi Pengembangan Keberagamaan',
            ],
            [
                'jurusan_id' => $S2PJJPAI->id,
                'kode_mata_kuliah' => 'JPA825022',
                'nama_mata_kuliah' => 'Manajemen Branding Pendidikan Islam',
                'semester' => 3,
                'sks' => 3,
                'deskripsi' => 'Manajemen Branding Pendidikan Islam',
            ],
        ];

        foreach ($mataKuliah as $data) {
            MataKuliah::create($data);
        }
    }
}
