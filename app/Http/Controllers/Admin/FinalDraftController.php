<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinalDraft;
use App\Models\PenyusunApplication;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FinalDraftController extends Controller
{
    public function index()
    {
        // Ambil semua penyusun yang approved dengan relasi yang diperlukan
        $penyusuns = PenyusunApplication::where('status', 'approved')
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

        // Ambil final drafts untuk statistik
        $finalDrafts = FinalDraft::with(['penyusunApplication', 'mataKuliah', 'lpmValidator'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.final-draft.index', compact('penyusunsByJurusanWithAll', 'finalDrafts'));
    }

    public function show(FinalDraft $finalDraft)
    {
        $finalDraft->load(['penyusunApplication', 'mataKuliah', 'lpmValidator']);
        
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
