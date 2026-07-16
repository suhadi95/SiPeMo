<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinalDraftReview extends Model
{
    public const HASIL_SANGAT_LAYAK = 'sangat_layak';
    public const HASIL_LAYAK_DENGAN_PERBAIKAN = 'layak_dengan_perbaikan';
    public const HASIL_PERLU_REVISI_MAYOR = 'perlu_revisi_mayor';
    public const HASIL_TIDAK_LAYAK = 'tidak_layak';

    public const HASIL_OPTIONS = [
        self::HASIL_SANGAT_LAYAK => 'Sangat Layak',
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
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
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
}
