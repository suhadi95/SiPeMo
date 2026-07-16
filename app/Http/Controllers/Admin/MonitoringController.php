<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class MonitoringController extends Controller
{
    public function index()
    {
        $penyusuns = \App\Models\PenyusunApplication::where('status', 'approved')
            ->with(['mataKuliah.jurusan', 'moduls.tahapPenyusunan', 'finalDrafts', 'publicationModuls'])
            ->orderBy('created_at', 'desc')
            ->get();

        $allJurusans = \App\Models\Jurusan::orderBy('nama_jurusan')->get();

        $penyusunsByJurusan = $penyusuns->groupBy(function ($penyusun) {
            return $penyusun->mataKuliah->jurusan->nama_jurusan ?? 'Lainnya';
        });

        $penyusunsByJurusanWithAll = collect();
        foreach ($allJurusans as $jurusan) {
            $penyusunsByJurusanWithAll->put($jurusan->nama_jurusan, $penyusunsByJurusan->get($jurusan->nama_jurusan, collect()));
        }

        if ($penyusunsByJurusan->has('Lainnya')) {
            $penyusunsByJurusanWithAll->put('Lainnya', $penyusunsByJurusan->get('Lainnya'));
        }

        $totalPenyusun = $penyusuns->count();

        $selesaiPenyusunan = $penyusuns->filter(function ($penyusun) {
            return $penyusun->moduls->where('status', 'approved')->count() >= 6;
        })->count();

        $finalDraftDisetujui = $penyusuns->filter(function ($penyusun) {
            $finalDraft = $penyusun->finalDrafts->first();

            return $finalDraft && $finalDraft->isLpmValidated() && $finalDraft->status === 'approved';
        })->count();

        $selesaiPublikasi = $penyusuns->filter(function ($penyusun) {
            $publication = $penyusun->publicationModuls->first();

            return $publication && $publication->status === 'approved';
        })->count();

        return view('admin.monitoring', compact(
            'penyusunsByJurusanWithAll',
            'totalPenyusun',
            'selesaiPenyusunan',
            'finalDraftDisetujui',
            'selesaiPublikasi'
        ));
    }
}
