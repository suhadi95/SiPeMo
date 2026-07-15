<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\PublicationModul;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PublicationController extends Controller
{
    public function index(Request $request)
    {
        $allPenyusuns = \App\Models\PenyusunApplication::where('status', 'approved')
            ->with(['user', 'mataKuliah.jurusan', 'moduls.tahapPenyusunan', 'finalDrafts', 'publicationModuls'])
            ->orderBy('created_at', 'desc')
            ->get();

        $summary = $this->calculateSummary($allPenyusuns);

        $penyusuns = $allPenyusuns;

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $penyusuns = $penyusuns->filter(function ($penyusun) use ($search) {
                return str_contains(strtolower($penyusun->nama_penyusun ?? ''), $search)
                    || str_contains(strtolower($penyusun->judul_bahan_ajar ?? ''), $search)
                    || str_contains(strtolower($penyusun->email ?? ''), $search)
                    || str_contains(strtolower($penyusun->mataKuliah?->nama_mata_kuliah ?? ''), $search)
                    || str_contains(strtolower($penyusun->mataKuliah?->jurusan?->nama_jurusan ?? ''), $search);
            });
        }

        if ($request->filled('status')) {
            $status = $request->status;
            $penyusuns = $penyusuns->filter(function ($penyusun) use ($status) {
                return $this->resolvePublicationStatus($penyusun) === $status;
            });
        }

        if ($request->filled('jurusan_id')) {
            $jurusanId = (int) $request->jurusan_id;
            $penyusuns = $penyusuns->filter(fn ($p) => ($p->mataKuliah?->jurusan_id ?? null) === $jurusanId);
        }

        $perPage = (int) $request->get('per_page', 15);
        $allowedPerPage = [15, 30, 60, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 15;
        }

        $penyusuns = $penyusuns->values();
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $penyusuns->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginator = new LengthAwarePaginator(
            $currentItems,
            $penyusuns->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $allJurusans = Jurusan::orderBy('nama_jurusan')->get();
        $penyusunsByJurusan = $currentItems->groupBy(fn ($p) => $p->mataKuliah->jurusan->nama_jurusan ?? 'Lainnya');

        $penyusunsByJurusanWithAll = collect();
        foreach ($penyusunsByJurusan as $nama => $items) {
            $penyusunsByJurusanWithAll->put($nama, $items);
        }

        $jurusans = $allJurusans;

        return view('admin.publication.index', compact(
            'penyusunsByJurusanWithAll',
            'summary',
            'paginator',
            'jurusans'
        ));
    }

    /**
     * Status publikasi yang ditampilkan di kolom tabel.
     */
    private function resolvePublicationStatus($penyusun): string
    {
        $publication = $penyusun->publicationModuls->first();
        $finalDraft = $penyusun->finalDrafts->first();
        $isFullyValidated = $finalDraft && $finalDraft->isLpmValidated() && $finalDraft->status === 'approved';

        if ($publication) {
            if ($publication->status == 'approved') {
                return 'approved';
            }
            if ($publication->status == 'rejected') {
                return 'rejected';
            }
            return 'pending';
        }

        if ($isFullyValidated) {
            return 'siap_upload';
        }

        return 'belum_tersedia';
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