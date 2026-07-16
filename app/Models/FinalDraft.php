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
        'hasil_penilaian',
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

    public function reviews(): HasMany
    {
        return $this->hasMany(FinalDraftReview::class)->orderByDesc('submitted_at');
    }

    public function latestReview()
    {
        return $this->hasOne(FinalDraftReview::class)->latestOfMany('submitted_at');
    }

    public function getHasilPenilaianLabelAttribute(): ?string
    {
        if (!$this->hasil_penilaian) {
            return null;
        }

        return FinalDraftReview::HASIL_OPTIONS[$this->hasil_penilaian] ?? $this->hasil_penilaian;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending_review' => 'Menunggu Reviewer',
            'rejected_by_reviewer' => 'Perlu Revisi (Reviewer)',
            'approved_by_reviewer' => 'Lolos Reviewer (Menunggu LPM)',
            'pending_lpm' => 'Menunggu Validasi LPM (revisi)',
            'approved' => 'Disetujui LPM',
            'rejected' => 'Ditolak LPM',
            'pending' => 'Menunggu',
            default => $this->status,
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'pending_review', 'pending_lpm', 'pending' => 'bg-yellow-100 text-yellow-800',
            'approved_by_reviewer' => 'bg-blue-100 text-blue-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected_by_reviewer', 'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function isAwaitingLpm(): bool
    {
        return in_array($this->status, ['approved_by_reviewer', 'pending_lpm'], true);
    }
}
