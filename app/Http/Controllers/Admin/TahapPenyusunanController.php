<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PenyusunApplication;
use App\Models\TahapPenyusunan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TahapPenyusunanController extends Controller
{
    public function index()
    {
        $tahaps = TahapPenyusunan::global()->orderBy('tahap')->get();
        
        return view('admin.tahap-penyusunan.index', compact('tahaps'));
    }

    public function create()
    {
        return view('admin.tahap-penyusunan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_periode' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        DB::transaction(function() use ($request) {
            // Buat 6 tahap penyusunan global
            for ($i = 1; $i <= 4; $i++) {
                $tanggalMulai = $request->tanggal_mulai;
                $tanggalSelesai = $request->tanggal_selesai;
                
                // Hitung tanggal untuk setiap tahap
                $daysPerTahap = (strtotime($tanggalSelesai) - strtotime($tanggalMulai)) / (4 * 24 * 3600);
                $startDate = date('Y-m-d', strtotime($tanggalMulai) + (($i - 1) * $daysPerTahap * 24 * 3600));
                $endDate = date('Y-m-d', strtotime($tanggalMulai) + ($i * $daysPerTahap * 24 * 3600) - (24 * 3600));
                
                // Untuk tahap terakhir, gunakan tanggal selesai yang ditentukan
                if ($i == 4) {
                    $endDate = $tanggalSelesai;
                }

                TahapPenyusunan::create([
                    'tahap' => $i,
                    'nama_tahap' => "Tahap {$i}",
                    'nama_periode' => $request->nama_periode,
                    'tanggal_mulai' => $startDate,
                    'tanggal_selesai' => $endDate,
                    'deskripsi' => $this->getTahapDescription($i),
                ]);
            }
        });

        return redirect()->route('admin.tahap-penyusunan.index')
            ->with('success', 'Periode tahap penyusunan berhasil dibuat.');
    }

    public function edit(TahapPenyusunan $tahap)
    {
        return view('admin.tahap-penyusunan.edit', compact('tahap'));
    }

    public function update(Request $request, TahapPenyusunan $tahap)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        $tahap->update([
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
        ]);

        return redirect()->route('admin.tahap-penyusunan.index')
            ->with('success', 'Tahap penyusunan berhasil diperbarui.');
    }

    public function activate(TahapPenyusunan $tahap)
    {
        // Set tanggal mulai ke hari sebelumnya dari tanggal saat ini
        $tanggalMulai = now()->subDay()->toDateString();
        
        $tahap->update([
            'tanggal_mulai' => $tanggalMulai,
        ]);

        return redirect()->route('admin.tahap-penyusunan.index')
            ->with('success', 'Tahap penyusunan berhasil diaktifkan.');
    }

    public function reset()
    {
        DB::transaction(function() {
            // Hapus data yang terkait dengan urutan yang benar untuk menghindari foreign key constraint
            // Menggunakan delete() instead of truncate() untuk menghindari foreign key constraint issues
            
            // 1. Hapus publication_moduls terlebih dahulu (mereferensikan final_drafts)
            \App\Models\PublicationModul::query()->delete();
            
            // 2. Hapus final_drafts (mereferensikan penyusun_applications dan mata_kuliahs)
            \App\Models\FinalDraft::query()->delete();
            
            // 3. Hapus moduls (mereferensikan tahap_penyusunans, penyusun_applications, mata_kuliahs)
            \App\Models\Modul::query()->delete();
            
            // 4. Hapus tahap_penyusunans terakhir (tidak ada yang mereferensikan)
            TahapPenyusunan::query()->delete();
        });

        return redirect()->route('admin.tahap-penyusunan.index')
            ->with('success', 'Semua periode tahap penyusunan dan data terkait berhasil direset. Anda dapat membuat periode baru.');
    }

    private function getTahapDescription($tahap)
    {
        $descriptions = [
            1 => "Penyusunan modul 1-2",
            2 => "Penyusunan modul 3-4",
            3 => "Penyusunan modul 5",
            4 => "Penyusunan modul 6",
        ];

        return $descriptions[$tahap] ?? "Tahap {$tahap} penyusunan modul";
    }
}
