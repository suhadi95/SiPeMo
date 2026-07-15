<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinalDraft;
use App\Models\Jurusan;
use App\Models\PenyusunApplication;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FinalDraftController extends Controller
{
    public function index(Request $request)
    {
        $allPenyusuns = PenyusunApplication::where('status', 'approved')
            ->with(['mataKuliah.jurusan', 'moduls.tahapPenyusunan', 'finalDrafts', 'publicationModuls'])
            ->orderBy('created_at', 'desc')
            ->get();

        $finalDrafts = FinalDraft::with(['penyusunApplication', 'mataKuliah', 'lpmValidator'])
            ->orderBy('created_at', 'desc')
            ->get();

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
                return $this->resolveFinalDraftStatus($penyusun) === $status;
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

        return view('admin.final-draft.index', compact(
            'penyusunsByJurusanWithAll',
            'finalDrafts',
            'paginator',
            'jurusans'
        ));
    }

    /**
     * Status Final Draft yang ditampilkan di kolom tabel.
     */
    private function resolveFinalDraftStatus(PenyusunApplication $penyusun): string
    {
        $finalDraft = $penyusun->finalDrafts->first();
        $allTahapsValidated = $penyusun->moduls->where('status', 'approved')->count() >= 6;

        if ($finalDraft) {
            if ($finalDraft->status === 'approved') {
                return 'approved';
            }
            if ($finalDraft->status === 'rejected') {
                return 'rejected';
            }
            if ($finalDraft->status === 'pending') {
                return $finalDraft->isLpmValidated() ? 'approved' : 'pending';
            }
        }

        if ($allTahapsValidated) {
            return 'siap_upload';
        }

        return 'belum_tersedia';
    }

    public function show(FinalDraft $finalDraft)
    {
        $finalDraft->load(['penyusunApplication', 'mataKuliah', 'lpmValidator', 'activityLogs.actor']);
        
        return view('admin.final-draft.show', compact('finalDraft'));
    }

    public function download(FinalDraft $finalDraft)
    {
        if (!$finalDraft->file_path || !Storage::disk('public')->exists($finalDraft->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download(Storage::disk('public')->path($finalDraft->file_path), $finalDraft->file_name);
    }

    /**
     * Download laporan akhir Monitoring Final Draft Modul dalam format PDF.
     * Data: semua pengusul (penyusun) yang sudah disetujui, termasuk yang belum upload / belum memulai.
     */
    public function reportPdf()
    {
        $penyusuns = PenyusunApplication::where('status', 'approved')
            ->with(['mataKuliah.jurusan', 'finalDrafts' => fn ($q) => $q->orderByDesc('created_at')->limit(1)->with('lpmValidator')])
            ->orderBy('created_at', 'desc')
            ->get();

        $total = $penyusuns->count();
        $approved = $penyusuns->filter(fn ($p) => $p->finalDrafts->first()?->status === 'approved')->count();
        $rejected = $penyusuns->filter(fn ($p) => $p->finalDrafts->first()?->status === 'rejected')->count();
        $pending = $penyusuns->filter(fn ($p) => ($fd = $p->finalDrafts->first()) && $fd->status === 'pending')->count();
        $belumUpload = $penyusuns->filter(fn ($p) => $p->finalDrafts->isEmpty())->count();

        $byJurusan = $penyusuns->groupBy(function (PenyusunApplication $p) {
            return $p->mataKuliah && $p->mataKuliah->jurusan
                ? $p->mataKuliah->jurusan->nama_jurusan
                : 'Lainnya';
        });

        $data = [
            'penyusuns' => $penyusuns,
            'byJurusan' => $byJurusan,
            'tanggalCetak' => now()->locale('id')->translatedFormat('d F Y'),
            'total' => $total,
            'approved' => $approved,
            'rejected' => $rejected,
            'pending' => $pending,
            'belumUpload' => $belumUpload,
        ];

        $pdf = Pdf::loadView('admin.final-draft.laporan-akhir-pdf', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'margin_top' => 25,
                'margin_right' => 20,
                'margin_bottom' => 25,
                'margin_left' => 20,
            ]);

        $fileName = 'Laporan_Akhir_Final_Draft_Modul_' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($fileName);
    }

    /**
     * Download data Monitoring Final Draft Modul dalam format Excel untuk keperluan olah data.
     * Data: semua pengusul (penyusun) yang sudah disetujui, termasuk yang belum upload / belum memulai.
     */
    public function reportExcel(): StreamedResponse
    {
        $penyusuns = PenyusunApplication::where('status', 'approved')
            ->with([
                'mataKuliah.jurusan', 
                'finalDrafts' => fn ($q) => $q->orderByDesc('created_at')->limit(1)->with('lpmValidator'),
                'publicationModuls' => fn ($q) => $q->orderByDesc('created_at')
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        $fileName = 'Data_Final_Draft_Modul_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];

        return response()->streamDownload(function () use ($penyusuns) {
            $file = fopen('php://output', 'w');
            
            // Tambahkan UTF-8 BOM agar Excel dapat menampilkan aksen/karakter khusus dengan benar
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Judul kolom CSV
            fputcsv($file, [
                'No',
                'Jurusan',
                'Kode Mata Kuliah',
                'Nama Mata Kuliah',
                'Semester',
                'Nama Penyusun',
                'Email',
                'No. WhatsApp',
                'NIP',
                'NIDN',
                'NPWP',
                'No. Rekening',
                'Nama Penerima',
                'Nama Bank',
                'Judul Modul',
                'Status Final Draft',
                'Tanggal Upload',
                'Tanggal Validasi LPM',
                'Validator LPM'
            ]);

            foreach ($penyusuns as $index => $p) {
                $fd = $p->finalDrafts->first();
                $pub = $p->publicationModuls->first();
                
                $jurusan = $p->mataKuliah && $p->mataKuliah->jurusan
                    ? $p->mataKuliah->jurusan->nama_jurusan
                    : '';
                $statusLabel = $fd
                    ? match ($fd->status) {
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        default => 'Menunggu Validasi',
                    }
                    : 'Belum Upload';
                $judul = $fd ? $fd->judul_modul : ($p->judul_bahan_ajar ?? '');

                fputcsv($file, [
                    $index + 1,
                    $jurusan,
                    $p->mataKuliah->kode_mata_kuliah ?? '',
                    $p->mataKuliah->nama_mata_kuliah ?? '',
                    $p->mataKuliah->semester ?? '',
                    $p->nama_penyusun ?? '',
                    $p->email ?? '',
                    $p->no_wa ? "'" . $p->no_wa : '', // prefix single-quote to preserve leading zeros in Excel
                    $p->nip ? "'" . $p->nip : '',
                    $p->nidn ? "'" . $p->nidn : '',
                    $pub?->npwp ? "'" . $pub->npwp : '',
                    $pub?->nomor_rekening ? "'" . $pub->nomor_rekening : '',
                    $pub?->nama_pemilik_rekening ?? '',
                    $pub?->nama_bank ?? '',
                    $judul,
                    $statusLabel,
                    $fd && $fd->uploaded_at ? $fd->uploaded_at->format('Y-m-d H:i') : '',
                    $fd && $fd->lpm_validated_at ? $fd->lpm_validated_at->format('Y-m-d H:i') : '',
                    $fd && $fd->lpmValidator ? $fd->lpmValidator->name : '',
                ]);
            }

            fclose($file);
        }, $fileName, $headers);
    }
}
