<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReviewPertanyaan extends Model
{
    protected $fillable = [
        'review_aspek_id',
        'teks_pertanyaan',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan' => 'integer',
    ];

    public function aspek(): BelongsTo
    {
        return $this->belongsTo(ReviewAspek::class, 'review_aspek_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(FinalDraftReviewAnswer::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan')->orderBy('id');
    }

    public function hasBeenUsed(): bool
    {
        return $this->answers()->exists();
    }
}
