<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PublicationModul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PublicationController extends Controller
{
    public function index()
    {
        // Ambil semua penyusun yang approved dengan relasi yang diperlukan
        $penyusuns = \App\Models\PenyusunApplication::where('status', 'approved')
            ->with(['user', 'mataKuliah.jurusan', 'moduls.tahapPenyusunan', 'finalDrafts', 'publicationModuls'])
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

        // Hitung summary data
        $summary = $this->calculateSummary($penyusuns);

        return view('admin.publication.index', compact('penyusunsByJurusanWithAll', 'summary'));
    }

    private function calculateSummary($penyusuns)
    {
        $totalPenyusun = $penyusuns->count();
        $belumTersedia = 0;
        $menungguValidasi = 0;
        $selesai = 0;

        foreach ($penyusuns as $penyusun) {
            $publication = $penyusun->publicationModuls->first();
            $finalDraft = $penyusun->finalDrafts->first();
            $isFullyValidated = $finalDraft && $finalDraft->isLpmValidated() && $finalDraft->status === 'approved';

            if ($publication) {
                if ($publication->status == 'approved') {
                    $selesai++;
                } elseif ($publication->status == 'rejected') {
                    $selesai++; // Ditolak juga dianggap selesai
                } else {
                    $menungguValidasi++;
                }
            } elseif ($isFullyValidated) {
                $belumTersedia++; // Siap upload tapi belum upload
            } else {
                $belumTersedia++; // Belum siap upload
            }
        }

        return [
            'total_penyusun' => $totalPenyusun,
            'belum_tersedia' => $belumTersedia,
            'menunggu_validasi' => $menungguValidasi,
            'selesai' => $selesai
        ];
    }

    public function show(PublicationModul $publicationModul)
    {
        $publicationModul->load([
            'penyusunApplication.user',
            'penyusunApplication.mataKuliah.jurusan',
            'finalDraft',
            'validator'
        ]);

        return view('admin.publication.show', compact('publicationModul'));
    }

    public function downloadFinalModul(PublicationModul $publicationModul)
    {
        if (!$publicationModul->final_modul_file_path || !Storage::disk('public')->exists($publicationModul->final_modul_file_path)) {
            abort(404, 'File final modul tidak ditemukan.');
        }

        return response()->download(
            Storage::disk('public')->path($publicationModul->final_modul_file_path), 
            $publicationModul->final_modul_file_name
        );
    }

    public function downloadSertifikatHki(PublicationModul $publicationModul)
    {
        if (!$publicationModul->sertifikat_hki_file_path || !Storage::disk('public')->exists($publicationModul->sertifikat_hki_file_path)) {
            abort(404, 'File sertifikat HKI tidak ditemukan.');
        }

        return response()->download(
            Storage::disk('public')->path($publicationModul->sertifikat_hki_file_path), 
            $publicationModul->sertifikat_hki_file_name
        );
    }

    public function validate(Request $request, PublicationModul $publicationModul)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'catatan_admin' => 'nullable|string|max:1000',
        ]);

        try {
            $publicationModul->update([
                'status' => $request->status,
                'catatan_admin' => $request->catatan_admin,
                'validated_at' => now(),
                'validated_by' => Auth::id(),
            ]);

            Log::info('Publication modul validated by admin', [
                'publication_modul_id' => $publicationModul->id,
                'status' => $request->status,
                'admin_id' => Auth::id(),
                'admin_name' => Auth::user()->name,
                'penyusun_name' => $publicationModul->penyusunApplication->user->name
            ]);

            $message = $request->status === 'approved' 
                ? 'Publikasi modul disetujui. Transfer akan dilakukan sesuai informasi rekening yang diberikan.'
                : 'Publikasi modul ditolak. Silakan periksa catatan admin.';

            return redirect()->route('admin.publication.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            Log::error('Error validating publication modul: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memvalidasi publikasi modul: ' . $e->getMessage());
        }
    }

    public function resetValidation(PublicationModul $publicationModul)
    {
        try {
            $publicationModul->update([
                'status' => 'pending',
                'catatan_admin' => null,
                'validated_at' => null,
                'validated_by' => null,
            ]);

            Log::info('Publication modul validation reset by admin', [
                'publication_modul_id' => $publicationModul->id,
                'admin_id' => Auth::id(),
                'admin_name' => Auth::user()->name
            ]);

            return redirect()->route('admin.publication.index')
                ->with('success', 'Validasi publikasi modul berhasil direset.');
                
        } catch (\Exception $e) {
            Log::error('Error resetting publication modul validation: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mereset validasi publikasi modul: ' . $e->getMessage());
        }
    }
}