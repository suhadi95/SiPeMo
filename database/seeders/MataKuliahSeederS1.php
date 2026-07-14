<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use App\Models\MataKuliah;
use Illuminate\Database\Seeder;

class MataKuliahSeederS1 extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $S1PJJPAI = Jurusan::where('kode_jurusan', 'PJJPAI-S1')->first();

        $mataKuliah = [
            // Semester 2
            [
                'jurusan_id' => $S1PJJPAI->id,
                'kode_mata_kuliah' => 'ITK60001J',
                'nama_mata_kuliah' => 'Ushul al-Tarbiyah',
                'semester' => 2,
                'sks' => 3,
                'deskripsi' => 'Ushul al-Tarbiyah',
            ],
            [
                'jurusan_id' => $S1PJJPAI->id,
                'kode_mata_kuliah' => 'PAI60003J',
                'nama_mata_kuliah' => 'Akhlaq Tasawuf',
                'semester' => 2,
                'sks' => 3,
                'deskripsi' => 'Akhlaq Tasawuf',
            ],

            // Semester 3
            [
                'jurusan_id' => $S1PJJPAI->id,
                'kode_mata_kuliah' => 'PAI60019J',
                'nama_mata_kuliah' => 'Creative Content Production Pembelajaran PAI',
                'semester' => 3,
                'sks' => 3,
                'deskripsi' => 'Creative Content Production Pembelajaran PAI',
            ],
            [
                'jurusan_id' => $S1PJJPAI->id,
                'kode_mata_kuliah' => 'PAI60001J',
                'nama_mata_kuliah' => 'Desain Pembelajaran PAI Berbasis Digital',
                'semester' => 3,
                'sks' => 3,
                'deskripsi' => 'Desain Pembelajaran PAI Berbasis Digital',
            ],
            [
                'jurusan_id' => $S1PJJPAI->id,
                'kode_mata_kuliah' => 'PAI60018J',
                'nama_mata_kuliah' => 'Ilmu Tauhid',
                'semester' => 3,
                'sks' => 3,
                'deskripsi' => 'Ilmu Tauhid',
            ],
            [
                'jurusan_id' => $S1PJJPAI->id,
                'kode_mata_kuliah' => 'PJJPAIS1003',
                'nama_mata_kuliah' => 'Tafsir Hadits Tarbawi',
                'semester' => 3,
                'sks' => 3,
                'deskripsi' => 'Tafsir Hadits Tarbawi',
            ],

            // Semester 4
            [
                'jurusan_id' => $S1PJJPAI->id,
                'kode_mata_kuliah' => 'JPA625002',
                'nama_mata_kuliah' => 'Fiqh Muamalat',
                'semester' => 4,
                'sks' => 3,
                'deskripsi' => 'Fiqh Muamalat',
            ],

            // Semester 5
            [
                'jurusan_id' => $S1PJJPAI->id,
                'kode_mata_kuliah' => 'ITK60005J',
                'nama_mata_kuliah' => 'Micro Teaching',
                'semester' => 5,
                'sks' => 3,
                'deskripsi' => 'Micro Teaching',
            ],
            [
                'jurusan_id' => $S1PJJPAI->id,
                'kode_mata_kuliah' => 'PAI60040J',
                'nama_mata_kuliah' => 'Fiqih',
                'semester' => 5,
                'sks' => 2,
                'deskripsi' => 'Fiqih',
            ],
            [
                'jurusan_id' => $S1PJJPAI->id,
                'kode_mata_kuliah' => 'PAI60039J',
                'nama_mata_kuliah' => 'Tafsir dan Ilmu Tafsir',
                'semester' => 5,
                'sks' => 3,
                'deskripsi' => 'Tafsir dan Ilmu Tafsir',
            ],

            // Semester 6
            [
                'jurusan_id' => $S1PJJPAI->id,
                'kode_mata_kuliah' => 'JPA625006',
                'nama_mata_kuliah' => 'Praktek Mengajar Otentik (PLP)',
                'semester' => 6,
                'sks' => 3,
                'deskripsi' => 'Praktek Mengajar Otentik (PLP)',
            ],
            [
                'jurusan_id' => $S1PJJPAI->id,
                'kode_mata_kuliah' => 'PAI60044J',
                'nama_mata_kuliah' => 'Analisis Kurikulum dan Silabus PAI Satuan Pendidikan',
                'semester' => 6,
                'sks' => 3,
                'deskripsi' => 'Analisis Kurikulum dan Silabus PAI Satuan Pendidikan',
            ],
            [
                'jurusan_id' => $S1PJJPAI->id,
                'kode_mata_kuliah' => 'JPA625004',
                'nama_mata_kuliah' => 'Pendidikan PAI Luar Sekolah',
                'semester' => 6,
                'sks' => 3,
                'deskripsi' => 'Pendidikan PAI Luar Sekolah',
            ],
            [
                'jurusan_id' => $S1PJJPAI->id,
                'kode_mata_kuliah' => 'JPA625043',
                'nama_mata_kuliah' => 'Isu - Isu Pendidikan Agama Islam Kontemporer',
                'semester' => 6,
                'sks' => 2,
                'deskripsi' => 'Isu - Isu Pendidikan Agama Islam Kontemporer',
            ],

            // Semester 7
            [
                'jurusan_id' => $S1PJJPAI->id,
                'kode_mata_kuliah' => 'PAIJ.6020',
                'nama_mata_kuliah' => 'Pengembangan Evaluasi dan Penilaian Hasil Belajar PAI',
                'semester' => 7,
                'sks' => 3,
                'deskripsi' => 'Pengembangan Evaluasi dan Penilaian Hasil Belajar PAI',
            ],
            [
                'jurusan_id' => $S1PJJPAI->id,
                'kode_mata_kuliah' => 'ITK.0006',
                'nama_mata_kuliah' => 'Praktek Ibadah',
                'semester' => 7,
                'sks' => 3,
                'deskripsi' => 'Praktek Ibadah',
            ],
            [
                'jurusan_id' => $S1PJJPAI->id,
                'kode_mata_kuliah' => 'PAIJ.6061',
                'nama_mata_kuliah' => 'Tafsir dan Ilmu Tafsir',
                'semester' => 7,
                'sks' => 2,
                'deskripsi' => 'Tafsir dan Ilmu Tafsir',
            ],

            // Semester 8
            [
                'jurusan_id' => $S1PJJPAI->id,
                'kode_mata_kuliah' => 'PJJPAIS1001',
                'nama_mata_kuliah' => 'Leadership Enterpreneurship',
                'semester' => 8,
                'sks' => 3,
                'deskripsi' => 'Leadership Enterpreneurship',
            ],
            [
                'jurusan_id' => $S1PJJPAI->id,
                'kode_mata_kuliah' => 'PJJPAIS1002',
                'nama_mata_kuliah' => 'Manajemen PAI Luar Sekolah',
                'semester' => 8,
                'sks' => 3,
                'deskripsi' => 'Manajemen PAI Luar Sekolah',
            ],
            [
                'jurusan_id' => $S1PJJPAI->id,
                'kode_mata_kuliah' => 'PAI60046J',
                'nama_mata_kuliah' => 'Sejarah Peradaban Islam',
                'semester' => 8,
                'sks' => 3,
                'deskripsi' => 'Sejarah Peradaban Islam',
            ],
            [
                'jurusan_id' => $S1PJJPAI->id,
                'kode_mata_kuliah' => 'JPA625005',
                'nama_mata_kuliah' => 'Teknik Penulisan Proposal Skripsi',
                'semester' => 8,
                'sks' => 2,
                'deskripsi' => 'Teknik Penulisan Proposal Skripsi',
            ],
            [
                'jurusan_id' => $S1PJJPAI->id,
                'kode_mata_kuliah' => 'JPA625032',
                'nama_mata_kuliah' => 'Social Media Branding PAI',
                'semester' => 8,
                'sks' => 2,
                'deskripsi' => 'Social Media Branding PAI',
            ],
            [
                'jurusan_id' => $S1PJJPAI->id,
                'kode_mata_kuliah' => 'PAIJ.6017',
                'nama_mata_kuliah' => 'Analisis Kebijakan Pendidikan',
                'semester' => 8,
                'sks' => 2,
                'deskripsi' => 'Analisis Kebijakan Pendidikan',
            ],
        ];

        foreach ($mataKuliah as $data) {
            MataKuliah::create($data);
        }
    }
}
