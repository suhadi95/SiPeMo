<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReviewAspek extends Model
{
    protected $fillable = [
        'nama',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan' => 'integer',
    ];

    public function pertanyaans(): HasMany
    {
        return $this->hasMany(ReviewPertanyaan::class)->orderBy('urutan');
    }

    public function activePertanyaans(): HasMany
    {
        return $this->pertanyaans()->where('is_active', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan')->orderBy('id');
    }
}
