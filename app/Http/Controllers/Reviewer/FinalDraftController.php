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
use Illuminate\Validation\Rule;

class FinalDraftController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $mataKuliahIds = MataKuliah::where('reviewer_id', $user->id)->pluck('id')->toArray();

        $query = FinalDraft::whereIn('mata_kuliah_id', $mataKuliahIds)
            ->with(['mataKuliah.jurusan']);

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
        if ($finalDraft->mataKuliah->reviewer_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk mereview final draft ini.');
        }

        $finalDraft->load([
            'mataKuliah.jurusan',
            'reviewerValidator',
            'lpmValidator',
            'activityLogs.actor',
            'latestReview.answers',
            'latestReview.reviewer',
        ]);

        $hasActiveKriteria = ReviewAspek::active()
            ->whereHas('activePertanyaans')
            ->exists();

        return view('reviewer.final-draft.show', compact('finalDraft', 'hasActiveKriteria'));
    }

    public function assess(FinalDraft $finalDraft)
    {
        if ($finalDraft->mataKuliah->reviewer_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk mereview final draft ini.');
        }

        if ($finalDraft->status !== 'pending_review') {
            return redirect()->route('reviewer.final-draft.show', $finalDraft)
                ->with('error', 'Final draft ini sudah dinilai dan tidak dapat dinilai ulang.');
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

        return view('reviewer.final-draft.assess', compact(
            'finalDraft',
            'aspeks',
            'likertLabels',
            'hasilOptions'
        ));
    }

    public function download(FinalDraft $finalDraft)
    {
        if ($finalDraft->mataKuliah->reviewer_id !== Auth::id()) {
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
        if ($finalDraft->mataKuliah->reviewer_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk melengkapi laporan validasi ini.');
        }

        $finalDraft->load(['mataKuliah.jurusan', 'penyusunApplication', 'latestReview.answers', 'latestReview.reviewer']);
        $review = $finalDraft->latestReview;

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
        if ($finalDraft->mataKuliah->reviewer_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk menyimpan laporan validasi ini.');
        }

        $finalDraft->load('latestReview');
        $review = $finalDraft->latestReview;

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
            'signature' => [$hasExistingSignature ? 'nullable' : 'required', 'string'],
        ], [
            'validator_institusi.required' => 'Institusi / unit kerja wajib diisi.',
            'validator_bidang_keahlian.required' => 'Bidang keahlian wajib diisi.',
            'validator_kontak.required' => 'Email / kontak wajib diisi.',
            'rekomendasi_validator.required' => 'Rekomendasi validator wajib diisi.',
            'signature.required' => 'Tanda tangan wajib diisi.',
        ]);

        $signaturePath = $review->validator_signature;

        if ($request->filled('signature')) {
            if (!preg_match('/^data:image\/(png|jpeg|jpg);base64,/', $request->signature)) {
                return redirect()->route('reviewer.final-draft.validation-report.form', $finalDraft)
                    ->withInput()
                    ->with('error', 'Format tanda tangan tidak valid. Silakan gambar ulang tanda tangan.');
            }

            $newSignaturePath = $this->storeSignatureImage($request->signature, $review);

            if ($review->validator_signature && $review->validator_signature !== $newSignaturePath) {
                Storage::disk('public')->delete($review->validator_signature);
            }

            $signaturePath = $newSignaturePath;
        } elseif (!$hasExistingSignature) {
            return redirect()->route('reviewer.final-draft.validation-report.form', $finalDraft)
                ->withInput()
                ->with('error', 'Tanda tangan wajib diisi.');
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
        if ($finalDraft->mataKuliah->reviewer_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengunduh laporan validasi ini.');
        }

        return $this->downloadValidationReportPdf($finalDraft, 'reviewer.final-draft.show');
    }

    public function validateDraft(Request $request, FinalDraft $finalDraft)
    {
        if ($finalDraft->mataKuliah->reviewer_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk memvalidasi draft ini.');
        }

        if ($finalDraft->status !== 'pending_review') {
            return redirect()->route('reviewer.final-draft.show', $finalDraft)
                ->with('error', 'Final draft ini sudah dinilai dan tidak dapat dinilai ulang.');
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

        $hasilKeys = array_keys(FinalDraftReview::HASIL_OPTIONS);

        $request->validate([
            'hasil_penilaian' => ['required', Rule::in($hasilKeys)],
            'catatan_revisi' => [
                Rule::requiredIf(fn () => $request->hasil_penilaian !== FinalDraftReview::HASIL_SANGAT_LAYAK),
                'nullable',
                'string',
                'max:2000',
            ],
            'jawaban' => 'required|array',
            'jawaban.*.skor' => 'required|integer|min:1|max:5',
            'jawaban.*.catatan' => 'nullable|string|max:1000',
        ], [
            'hasil_penilaian.required' => 'Hasil penilaian wajib dipilih.',
            'catatan_revisi.required' => 'Catatan revisi wajib diisi jika hasil bukan Sangat Layak.',
            'jawaban.*.skor.required' => 'Setiap pertanyaan wajib diberi skor Likert 1–5.',
        ]);

        foreach ($pertanyaanIds as $pertanyaanId) {
            if (!isset($request->jawaban[$pertanyaanId]['skor'])) {
                return redirect()->route('reviewer.final-draft.assess', $finalDraft)
                    ->withInput()
                    ->with('error', 'Semua pertanyaan aktif wajib dinilai.');
            }
        }

        try {
            DB::transaction(function () use ($request, $finalDraft, $aspeks) {
                $hasil = $request->hasil_penilaian;
                $isSangatLayak = $hasil === FinalDraftReview::HASIL_SANGAT_LAYAK;
                $statusNew = $isSangatLayak ? 'approved_by_reviewer' : 'rejected_by_reviewer';
                $catatanRevisi = $isSangatLayak ? null : $request->catatan_revisi;

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

                $finalDraft->update([
                    'status' => $statusNew,
                    'hasil_penilaian' => $hasil,
                    'catatan_reviewer' => $catatanRevisi,
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

            Log::info('Final draft validated by Reviewer (Likert)', [
                'final_draft_id' => $finalDraft->id,
                'hasil_penilaian' => $request->hasil_penilaian,
                'reviewer_id' => Auth::id(),
            ]);

            $message = $request->hasil_penilaian === FinalDraftReview::HASIL_SANGAT_LAYAK
                ? 'Penilaian tersimpan. Lengkapi form laporan validasi untuk menghasilkan PDF pelaporan.'
                : 'Penilaian tersimpan. Final draft dikembalikan ke penyusun untuk perbaikan.';

            if ($request->hasil_penilaian === FinalDraftReview::HASIL_SANGAT_LAYAK) {
                return redirect()->route('reviewer.final-draft.validation-report.form', $finalDraft)
                    ->with('success', $message);
            }

            return redirect()->route('reviewer.final-draft.show', $finalDraft)
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error validating final draft by Reviewer: ' . $e->getMessage());

            return redirect()->route('reviewer.final-draft.assess', $finalDraft)
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan penilaian: ' . $e->getMessage());
        }
    }

    private function storeSignatureImage(string $signatureData, FinalDraftReview $review): string
    {
        $imageData = substr($signatureData, strpos($signatureData, ',') + 1);
        $decoded = base64_decode($imageData, true);

        if ($decoded === false) {
            throw new \RuntimeException('Gagal memproses tanda tangan.');
        }

        $fileName = 'validation-signatures/review_' . $review->id . '_' . time() . '.png';
        Storage::disk('public')->put($fileName, $decoded);

        return $fileName;
    }

    private function downloadValidationReportPdf(FinalDraft $finalDraft, string $redirectRoute)
    {
        $finalDraft->load([
            'mataKuliah.jurusan',
            'penyusunApplication',
            'latestReview.answers',
            'latestReview.reviewer',
        ]);

        $review = $finalDraft->latestReview;

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

        $fileName = 'Form_Validasi_FD-' . str_pad((string) $finalDraft->id, 5, '0', STR_PAD_LEFT) . '.pdf';

        return $pdf->download($fileName);
    }
}
