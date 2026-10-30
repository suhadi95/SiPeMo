<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\FinalDraft;
use App\Models\FinalDraftActivityLog;
use App\Models\FinalDraftReview;
use App\Models\MataKuliah;
use App\Models\ReviewAspek;
use App\Models\ReviewerApplication;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FinalDraftController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $assignedAppIds = $user->assignedPenyusunApplications()->pluck('penyusun_applications.id')->toArray();
        $legacyMkIds = MataKuliah::where('reviewer_id', $user->id)->pluck('id')->toArray();

        $query = FinalDraft::where(function ($q) use ($assignedAppIds, $legacyMkIds) {
            if (!empty($assignedAppIds)) {
                $q->whereIn('penyusun_application_id', $assignedAppIds);
            }
            if (!empty($legacyMkIds)) {
                $q->orWhereIn('mata_kuliah_id', $legacyMkIds);
            }
            if (empty($assignedAppIds) && empty($legacyMkIds)) {
                $q->whereRaw('1 = 0');
            }
        })->with(['mataKuliah.jurusan', 'penyusunApplication', 'reviews']);

        if ($request->filled('search')) {
            $search = $request->search;
            $draftId = null;
            if (preg_match('/^(?:FD-?)?(\d+)$/i', trim($search), $matches)) {
                $draftId = (int) $matches[1];
            }

            $query->where(function ($q) use ($search, $draftId) {
                $q->where('judul_modul', 'like', "%{$search}%");
                if ($draftId !== null) {
                    $q->orWhere('id', $draftId);
                }
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $finalDrafts = $query->orderBy('uploaded_at', 'desc')->paginate(15)->withQueryString();

        return view('reviewer.final-draft.index', compact('finalDrafts'));
    }

    public function show(FinalDraft $finalDraft)
    {
        if (!$this->isAuthorizedReviewer($finalDraft)) {
            abort(403, 'Anda tidak memiliki hak akses untuk mereview final draft ini.');
        }

        $finalDraft->load([
            'mataKuliah.jurusan',
            'reviewerValidator',
            'lpmValidator',
            'activityLogs.actor',
            'reviews.answers',
            'reviews.reviewer',
        ]);

        $myReview = $finalDraft->reviewBy(Auth::id());

        $hasActiveKriteria = ReviewAspek::active()
            ->whereHas('activePertanyaans')
            ->exists();

        return view('reviewer.final-draft.show', compact('finalDraft', 'hasActiveKriteria', 'myReview'));
    }

    public function assess(FinalDraft $finalDraft)
    {
        if (!$this->isAuthorizedReviewer($finalDraft)) {
            abort(403, 'Anda tidak memiliki hak akses untuk mereview final draft ini.');
        }

        if ($finalDraft->isReviewCompletedBy(Auth::id())) {
            return redirect()->route('reviewer.final-draft.show', $finalDraft)
                ->with('error', 'Anda sudah menyelesaikan penilaian untuk final draft ini.');
        }

        if (!in_array($finalDraft->status, ['pending_review', 'pending'], true)) {
            return redirect()->route('reviewer.final-draft.show', $finalDraft)
                ->with('error', 'Final draft ini tidak dalam status menunggu review.');
        }

        $finalDraft->load(['mataKuliah.jurusan']);

        $aspeks = ReviewAspek::active()
            ->ordered()
            ->with(['activePertanyaans' => fn ($q) => $q->ordered()])
            ->get()
            ->filter(fn ($aspek) => $aspek->activePertanyaans->isNotEmpty())
            ->values();

        $likertLabels = FinalDraftReview::LIKERT_LABELS;
        $hasilOptions = FinalDraftReview::HASIL_OPTIONS;
        $totalPertanyaan = $aspeks->sum(fn ($a) => $a->activePertanyaans->count());
        $maksimalSkor = $totalPertanyaan * 5;

        return view('reviewer.final-draft.assess', compact(
            'finalDraft',
            'aspeks',
            'likertLabels',
            'hasilOptions',
            'totalPertanyaan',
            'maksimalSkor'
        ));
    }

    public function download(FinalDraft $finalDraft)
    {
        if (!$this->isAuthorizedReviewer($finalDraft)) {
            abort(403, 'Anda tidak memiliki hak akses untuk mendownload file ini.');
        }

        if (!$finalDraft->file_path || !Storage::disk('public')->exists($finalDraft->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        $extension = pathinfo($finalDraft->file_name, PATHINFO_EXTENSION);
        $anonymousName = 'FinalDraft_FD-' . str_pad((string) $finalDraft->id, 5, '0', STR_PAD_LEFT) . ($extension ? '.' . $extension : '');

        return response()->download(Storage::disk('public')->path($finalDraft->file_path), $anonymousName);
    }

    public function validationReportForm(FinalDraft $finalDraft)
    {
        if (!$this->isAuthorizedReviewer($finalDraft)) {
            abort(403, 'Anda tidak memiliki hak akses untuk melengkapi laporan validasi ini.');
        }

        $finalDraft->load(['mataKuliah.jurusan', 'penyusunApplication']);
        $review = $finalDraft->reviewBy(Auth::id());

        if (!$review || !$review->requiresValidationReport()) {
            return redirect()->route('reviewer.final-draft.show', $finalDraft)
                ->with('error', 'Form laporan validasi hanya tersedia untuk penilaian Sangat Layak.');
        }

        $reviewerApp = ReviewerApplication::where('email', Auth::user()->email)->first();
        $isEditing = $review->hasCompletedValidationReport();

        return view('reviewer.final-draft.validation-report', compact('finalDraft', 'review', 'reviewerApp', 'isEditing'));
    }

    public function storeValidationReport(Request $request, FinalDraft $finalDraft)
    {
        if (!$this->isAuthorizedReviewer($finalDraft)) {
            abort(403, 'Anda tidak memiliki hak akses untuk menyimpan laporan validasi ini.');
        }

        $review = $finalDraft->reviewBy(Auth::id());

        if (!$review || !$review->requiresValidationReport()) {
            return redirect()->route('reviewer.final-draft.show', $finalDraft)
                ->with('error', 'Form laporan validasi hanya tersedia untuk penilaian Sangat Layak.');
        }

        $hasExistingSignature = $review->validator_signature
            && Storage::disk('public')->exists($review->validator_signature);

        $request->validate([
            'validator_institusi' => 'required|string|max:255',
            'validator_bidang_keahlian' => 'required|string|max:255',
            'validator_kontak' => 'required|string|max:255',
            'rekomendasi_validator' => 'required|string|max:2000',
            'signature_file' => [
                $hasExistingSignature ? 'nullable' : 'required',
                'file',
                'mimes:jpg,jpeg,png,webp,svg',
                'max:3072', // Max 3MB
            ],
        ], [
            'validator_institusi.required' => 'Institusi / unit kerja wajib diisi.',
            'validator_bidang_keahlian.required' => 'Bidang keahlian wajib diisi.',
            'validator_kontak.required' => 'Email / kontak wajib diisi.',
            'rekomendasi_validator.required' => 'Rekomendasi validator wajib diisi.',
            'signature_file.required' => 'Berkas tanda tangan wajib diunggah.',
            'signature_file.mimes' => 'Format berkas tanda tangan harus berupa gambar (JPG, PNG, WEBP, atau SVG).',
            'signature_file.max' => 'Ukuran berkas tanda tangan maksimal 3MB.',
        ]);

        $signaturePath = $review->validator_signature;

        if ($request->hasFile('signature_file')) {
            $file = $request->file('signature_file');
            $extension = $file->getClientOriginalExtension();
            $fileName = 'review_' . $review->id . '_' . time() . '.' . $extension;
            $path = $file->storeAs('validation-signatures', $fileName, 'public');

            if ($review->validator_signature && $review->validator_signature !== $path && Storage::disk('public')->exists($review->validator_signature)) {
                Storage::disk('public')->delete($review->validator_signature);
            }

            $signaturePath = $path;
        }

        try {
            $isEditing = $review->hasCompletedValidationReport();

            $review->update([
                'validator_institusi' => $request->validator_institusi,
                'validator_bidang_keahlian' => $request->validator_bidang_keahlian,
                'validator_email_kontak' => $request->validator_kontak,
                'rekomendasi_validator' => $request->rekomendasi_validator,
                'validator_signature' => $signaturePath,
                'validator_report_completed_at' => $review->validator_report_completed_at ?? now(),
            ]);

            $message = $isEditing
                ? 'Laporan validasi berhasil diperbarui.'
                : 'Laporan validasi berhasil dilengkapi. Anda dapat mengunduh PDF laporan.';

            return redirect()->route('reviewer.final-draft.show', $finalDraft)
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error storing validation report: ' . $e->getMessage());

            return redirect()->route('reviewer.final-draft.validation-report.form', $finalDraft)
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan laporan validasi: ' . $e->getMessage());
        }
    }

    public function validationReportPdf(FinalDraft $finalDraft)
    {
        if (!$this->isAuthorizedReviewer($finalDraft)) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengunduh laporan validasi ini.');
        }

        $review = $finalDraft->reviewBy(Auth::id());
        if (!$review) {
            $review = $finalDraft->latestReview;
        }

        return $this->downloadValidationReportPdf($finalDraft, $review, 'reviewer.final-draft.show');
    }

    public function validateDraft(Request $request, FinalDraft $finalDraft)
    {
        if (!$this->isAuthorizedReviewer($finalDraft)) {
            abort(403, 'Anda tidak memiliki hak akses untuk memvalidasi draft ini.');
        }

        if ($finalDraft->isReviewCompletedBy(Auth::id())) {
            return redirect()->route('reviewer.final-draft.show', $finalDraft)
                ->with('error', 'Anda sudah menilai final draft ini.');
        }

        $aspeks = ReviewAspek::active()
            ->ordered()
            ->with(['activePertanyaans' => fn ($q) => $q->ordered()])
            ->get();

        $pertanyaanIds = $aspeks->flatMap(fn ($a) => $a->activePertanyaans->pluck('id'))->values()->all();

        if (empty($pertanyaanIds)) {
            return redirect()->route('reviewer.final-draft.assess', $finalDraft)
                ->with('error', 'Belum ada kriteria penilaian aktif. Hubungi admin untuk menambahkan aspek dan pertanyaan.');
        }

        $request->validate([
            'jawaban' => 'required|array',
            'jawaban.*.skor' => 'required|integer|min:1|max:5',
            'jawaban.*.catatan' => 'nullable|string|max:1000',
            'catatan_revisi' => 'nullable|string|max:2000',
        ], [
            'jawaban.*.skor.required' => 'Setiap pertanyaan wajib diberi skor Likert 1–5.',
        ]);

        foreach ($pertanyaanIds as $pertanyaanId) {
            if (!isset($request->jawaban[$pertanyaanId]['skor'])) {
                return redirect()->route('reviewer.final-draft.assess', $finalDraft)
                    ->withInput()
                    ->with('error', 'Semua pertanyaan aktif wajib dinilai.');
            }
        }

        // Hitung total skor & persentase secara otomatis
        $totalSkor = 0;
        foreach ($pertanyaanIds as $pertanyaanId) {
            $totalSkor += (int) $request->jawaban[$pertanyaanId]['skor'];
        }
        $maksimalSkor = count($pertanyaanIds) * 5;
        $persentase = $maksimalSkor > 0 ? round(($totalSkor / $maksimalSkor) * 100, 1) : 0;

        // Aturan keputusan: total skor >= 80% = Sangat Layak, < 80% = Perlu Perbaikan
        $isSangatLayak = $persentase >= 80.0;
        $hasil = $isSangatLayak ? FinalDraftReview::HASIL_SANGAT_LAYAK : FinalDraftReview::HASIL_PERLU_PERBAIKAN;

        // Jika perlu perbaikan, catatan revisi wajib diisi
        if (!$isSangatLayak && empty(trim((string) $request->catatan_revisi))) {
            return redirect()->route('reviewer.final-draft.assess', $finalDraft)
                ->withInput()
                ->withErrors(['catatan_revisi' => 'Catatan revisi wajib diisi jika hasil penilaian adalah Perlu Perbaikan (skor di bawah 80%).']);
        }

        try {
            DB::transaction(function () use ($request, $finalDraft, $aspeks, $hasil, $isSangatLayak) {
                $catatanRevisi = $request->catatan_revisi;

                $review = FinalDraftReview::create([
                    'final_draft_id' => $finalDraft->id,
                    'reviewer_id' => Auth::id(),
                    'hasil_penilaian' => $hasil,
                    'catatan_revisi' => $catatanRevisi,
                    'submitted_at' => now(),
                ]);

                foreach ($aspeks as $aspek) {
                    foreach ($aspek->activePertanyaans as $pertanyaan) {
                        $jawaban = $request->jawaban[$pertanyaan->id] ?? [];
                        $review->answers()->create([
                            'review_pertanyaan_id' => $pertanyaan->id,
                            'aspek_nama' => $aspek->nama,
                            'teks_pertanyaan' => $pertanyaan->teks_pertanyaan,
                            'skor' => (int) ($jawaban['skor'] ?? 0),
                            'catatan' => $jawaban['catatan'] ?? null,
                        ]);
                    }
                }

                // Evaluasi status agregat multi-reviewer
                $statusNew = $finalDraft->evaluateMultiReviewerStatus();

                $finalDraft->update([
                    'status' => $statusNew,
                    'hasil_penilaian' => $statusNew === 'approved_by_reviewer'
                        ? FinalDraftReview::HASIL_SANGAT_LAYAK
                        : ($statusNew === 'rejected_by_reviewer' ? FinalDraftReview::HASIL_PERLU_PERBAIKAN : $hasil),
                    'catatan_reviewer' => $catatanRevisi ?: $finalDraft->catatan_reviewer,
                    'reviewer_validated_at' => now(),
                    'reviewer_validated_by' => Auth::id(),
                ]);

                FinalDraftActivityLog::create([
                    'final_draft_id' => $finalDraft->id,
                    'actor_id' => Auth::id(),
                    'actor_role' => 'reviewer',
                    'action' => $hasil,
                    'status_after' => $statusNew,
                    'notes' => $catatanRevisi,
                    'created_at' => now(),
                ]);
            });

            Log::info('Final draft validated by Reviewer (Likert Otomatis)', [
                'final_draft_id' => $finalDraft->id,
                'reviewer_id' => Auth::id(),
                'total_skor' => $totalSkor,
                'persentase' => $persentase,
                'hasil' => $hasil,
            ]);

            if ($isSangatLayak) {
                $message = "Penilaian tersimpan (Skor: {$persentase}% - Sangat Layak). Silakan lengkapi tanda tangan dan laporan validasi.";
                return redirect()->route('reviewer.final-draft.validation-report.form', $finalDraft)
                    ->with('success', $message);
            }

            $message = "Penilaian tersimpan (Skor: {$persentase}% - Perlu Perbaikan). Modul dikembalikan ke Penyusun untuk direvisi.";
            return redirect()->route('reviewer.final-draft.show', $finalDraft)
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error validating final draft by Reviewer: ' . $e->getMessage());

            return redirect()->route('reviewer.final-draft.assess', $finalDraft)
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan penilaian: ' . $e->getMessage());
        }
    }

    private function isAuthorizedReviewer(FinalDraft $finalDraft): bool
    {
        $userId = Auth::id();

        // 1. Cek tabel pivot multi-reviewer
        if ($finalDraft->penyusunApplication && $finalDraft->penyusunApplication->reviewers()->where('users.id', $userId)->exists()) {
            return true;
        }

        // 2. Cek backward-compatibility mata kuliah
        if ($finalDraft->mataKuliah && $finalDraft->mataKuliah->reviewer_id === $userId) {
            return true;
        }

        return false;
    }

    private function downloadValidationReportPdf(FinalDraft $finalDraft, ?FinalDraftReview $review, string $redirectRoute)
    {
        $finalDraft->load([
            'mataKuliah.jurusan',
            'penyusunApplication',
        ]);

        if (!$review || !$review->requiresValidationReport()) {
            return redirect()->route($redirectRoute, $finalDraft)
                ->with('error', 'Laporan validasi PDF hanya tersedia untuk penilaian Sangat Layak.');
        }

        if (!$review->hasCompletedValidationReport()) {
            return redirect()->route($redirectRoute, $finalDraft)
                ->with('error', 'Laporan validasi belum dilengkapi oleh reviewer.');
        }

        $pdf = Pdf::loadView('pdf.final-draft-validation-report', [
            'finalDraft' => $finalDraft,
            'review' => $review,
            'likertLabels' => FinalDraftReview::LIKERT_LABELS,
            'hasilOptions' => FinalDraftReview::HASIL_OPTIONS,
        ])->setPaper('a4', 'portrait')->setOptions([
            'defaultFont' => 'DejaVu Sans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
        ]);

        $fileName = 'Form_Validasi_FD-' . str_pad((string) $finalDraft->id, 5, '0', STR_PAD_LEFT) . '_' . ($review->reviewer->id ?? 'r') . '.pdf';

        return $pdf->download($fileName);
    }
}
