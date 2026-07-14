<?php

namespace App\Http\Controllers\Lpm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index()
    {
        // Ambil semua penyusun yang approved dengan relasi yang diperlukan
        $penyusuns = \App\Models\PenyusunApplication::where('status', 'approved')
            ->with(['mataKuliah.jurusan', 'moduls.tahapPenyusunan', 'finalDrafts', 'publicationModuls'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil semua jurusan
        $allJurusans = \App\Models\Jurusan::orderBy('nama_jurusan')->get();

        // Kelompokkan penyusun berdasarkan jurusan
        $penyusunsByJurusan = $penyusuns->groupBy(function($penyusun) {
            return $penyusun->mataKuliah->jurusan->nama_jurusan ?? 'Lainnya';
        });

        // Pastikan semua jurusan ada dalam array, meskipun kosong
        $penyusunsByJurusanWithAll = collect();
        foreach($allJurusans as $jurusan) {
            $penyusunsByJurusanWithAll->put($jurusan->nama_jurusan, $penyusunsByJurusan->get($jurusan->nama_jurusan, collect()));
        }

        // Tambahkan jurusan "Lainnya" jika ada penyusun tanpa jurusan
        if($penyusunsByJurusan->has('Lainnya')) {
            $penyusunsByJurusanWithAll->put('Lainnya', $penyusunsByJurusan->get('Lainnya'));
        }

        // Hitung statistik summary
        $totalPenyusun = $penyusuns->count();
        
        // Selesai Penyusunan: yang sudah validasi tahap 1 sampai 6
        $selesaiPenyusunan = $penyusuns->filter(function($penyusun) {
            return $penyusun->moduls->where('status', 'approved')->count() >= 6;
        })->count();
        
        // Final Draft Disetujui: final draft yang sudah disetujui LPM
        $finalDraftDisetujui = $penyusuns->filter(function($penyusun) {
            $finalDraft = $penyusun->finalDrafts->first();
            return $finalDraft && $finalDraft->isLpmValidated() && $finalDraft->status === 'approved';
        })->count();
        
        // Selesai Publikasi: publikasi yang sudah divalidasi admin
        $selesaiPublikasi = $penyusuns->filter(function($penyusun) {
            $publication = $penyusun->publicationModuls->first();
            return $publication && $publication->status === 'approved';
        })->count();
        
        return view('lpm.monitoring', compact('penyusunsByJurusanWithAll', 'totalPenyusun', 'selesaiPenyusunan', 'finalDraftDisetujui', 'selesaiPublikasi'));
    }
}
