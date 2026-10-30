<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class FinalDraftReview extends Model
{
    public const HASIL_SANGAT_LAYAK = 'sangat_layak';
    public const HASIL_PERLU_PERBAIKAN = 'perlu_perbaikan';
    public const HASIL_LAYAK_DENGAN_PERBAIKAN = 'layak_dengan_perbaikan';
    public const HASIL_PERLU_REVISI_MAYOR = 'perlu_revisi_mayor';
    public const HASIL_TIDAK_LAYAK = 'tidak_layak';

    public const HASIL_OPTIONS = [
        self::HASIL_SANGAT_LAYAK => 'Sangat Layak',
        self::HASIL_PERLU_PERBAIKAN => 'Perlu Perbaikan',
        self::HASIL_LAYAK_DENGAN_PERBAIKAN => 'Layak dengan Perbaikan',
        self::HASIL_PERLU_REVISI_MAYOR => 'Perlu Revisi Mayor',
        self::HASIL_TIDAK_LAYAK => 'Tidak Layak',
    ];

    public const LIKERT_LABELS = [
        1 => 'Sangat Kurang',
        2 => 'Kurang',
        3 => 'Cukup',
        4 => 'Baik',
        5 => 'Sangat Baik',
    ];

    protected $fillable = [
        'final_draft_id',
        'reviewer_id',
        'hasil_penilaian',
        'catatan_revisi',
        'submitted_at',
        'validator_institusi',
        'validator_bidang_keahlian',
        'validator_email_kontak',
        'rekomendasi_validator',
        'validator_signature',
        'validator_report_completed_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'validator_report_completed_at' => 'datetime',
    ];

    public function finalDraft(): BelongsTo
    {
        return $this->belongsTo(FinalDraft::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(FinalDraftReviewAnswer::class);
    }

    public function getHasilPenilaianLabelAttribute(): string
    {
        return self::HASIL_OPTIONS[$this->hasil_penilaian] ?? $this->hasil_penilaian;
    }

    public function isSangatLayak(): bool
    {
        return $this->hasil_penilaian === self::HASIL_SANGAT_LAYAK;
    }

    public function answersGroupedByAspek()
    {
        return $this->answers->groupBy('aspek_nama');
    }

    public function hasCompletedValidationReport(): bool
    {
        return $this->validator_report_completed_at !== null;
    }

    public function requiresValidationReport(): bool
    {
        return $this->hasil_penilaian === self::HASIL_SANGAT_LAYAK;
    }

    public function getTotalSkorAttribute(): int
    {
        return (int) $this->answers->sum('skor');
    }

    public function getMaksimalSkorAttribute(): int
    {
        $count = $this->answers->count();
        return $count > 0 ? $count * 5 : 0;
    }

    public function getPersentaseSkorAttribute(): float
    {
        $maks = $this->maksimal_skor;
        if ($maks <= 0) {
            return 0.0;
        }

        return round(($this->total_skor / $maks) * 100, 1);
    }

    public function signatureDataUri(): ?string
    {
        if (!$this->validator_signature || !Storage::disk('public')->exists($this->validator_signature)) {
            return null;
        }

        $path = $this->validator_signature;
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mime = match ($ext) {
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            default => 'image/png',
        };

        return 'data:' . $mime . ';base64,' . base64_encode(Storage::disk('public')->get($path));
    }
}
