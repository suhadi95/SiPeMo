<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinalDraft extends Model
{
    protected $fillable = [
        'penyusun_application_id',
        'mata_kuliah_id',
        'judul_modul',
        'deskripsi_modul',
        'file_path',
        'file_name',
        'status',
        'catatan_lpm',
        'uploaded_at',
        'lpm_validated_at',
        'lpm_validated_by',
        'reviewer_validated_at',
        'reviewer_validated_by',
        'catatan_reviewer',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'lpm_validated_at' => 'datetime',
        'reviewer_validated_at' => 'datetime',
    ];

    public function penyusunApplication(): BelongsTo
    {
        return $this->belongsTo(PenyusunApplication::class);
    }

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function lpmValidator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lpm_validated_by');
    }

    public function reviewerValidator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_validated_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function isLpmValidated(): bool
    {
        return !is_null($this->lpm_validated_at) && !is_null($this->lpm_validated_by);
    }

    public function publicationModuls(): HasMany
    {
        return $this->hasMany(PublicationModul::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(FinalDraftActivityLog::class)->orderByDesc('created_at');
    }
}
