<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinalDraftReviewAnswer extends Model
{
    protected $fillable = [
        'final_draft_review_id',
        'review_pertanyaan_id',
        'aspek_nama',
        'teks_pertanyaan',
        'skor',
        'catatan',
    ];

    protected $casts = [
        'skor' => 'integer',
    ];

    public function review(): BelongsTo
    {
        return $this->belongsTo(FinalDraftReview::class, 'final_draft_review_id');
    }

    public function pertanyaan(): BelongsTo
    {
        return $this->belongsTo(ReviewPertanyaan::class, 'review_pertanyaan_id');
    }

    public function getSkorLabelAttribute(): string
    {
        return FinalDraftReview::LIKERT_LABELS[$this->skor] ?? (string) $this->skor;
    }
}
