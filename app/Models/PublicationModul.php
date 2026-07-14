<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublicationModul extends Model
{
    protected $fillable = [
        'penyusun_application_id', 'final_draft_id', 'judul_modul', 'deskripsi_modul',
        'final_modul_file_path', 'final_modul_file_name',
        'sertifikat_hki_file_path', 'sertifikat_hki_file_name',
        'nama_bank', 'nomor_rekening', 'nama_pemilik_rekening',
        'nik', 'npwp',
        'status', 'catatan_admin', 'uploaded_at', 'validated_at', 'validated_by',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'validated_at' => 'datetime',
    ];

    public function penyusunApplication(): BelongsTo
    {
        return $this->belongsTo(PenyusunApplication::class);
    }

    public function finalDraft(): BelongsTo
    {
        return $this->belongsTo(FinalDraft::class);
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    // Scope methods
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

    // Helper methods
    public function isAdminValidated(): bool
    {
        return !is_null($this->validated_at) && !is_null($this->validated_by);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}
