<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Carbon\Carbon;

class TahapPenyusunan extends Model
{
    protected $fillable = [
        'tahap',
        'nama_tahap',
        'nama_periode',
        'tanggal_mulai',
        'tanggal_selesai',
        'deskripsi',
        'deskripsi_tahap',
    ];

    protected $casts = [
        'tahap' => 'integer',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function moduls(): HasMany
    {
        return $this->hasMany(Modul::class);
    }

    public function finalDrafts(): HasMany
    {
        return $this->hasManyThrough(FinalDraft::class, Modul::class, 'tahap_id', 'penyusun_application_id', 'id', 'penyusun_application_id');
    }

    public function isCurrentlyActive(): bool
    {
        $now = Carbon::now()->toDateString();
        return $this->tanggal_mulai <= $now && $this->tanggal_selesai >= $now;
    }

    public function isPast(): bool
    {
        return Carbon::now()->toDateString() > $this->tanggal_selesai;
    }

    public function isFuture(): bool
    {
        return Carbon::now()->toDateString() < $this->tanggal_mulai;
    }

    public function isAccessible(): bool
    {
        $now = Carbon::now()->toDateString();
        return $this->tanggal_mulai <= $now;
    }

    public function canStillUpload(): bool
    {
        // Tahap dapat diakses jika sudah mulai dan belum lewat batas waktu
        $now = Carbon::now()->toDateString();
        return $this->tanggal_mulai <= $now && $this->tanggal_selesai >= $now;
    }

    public function hasUnfinishedModuls($penyusunApplicationId): bool
    {
        // Cek apakah ada modul dalam tahap ini yang belum diupload atau belum divalidasi
        $modul = Modul::where('penyusun_application_id', $penyusunApplicationId)
            ->where('tahap_id', $this->id)
            ->first();

        if (!$modul) {
            return true; // Belum ada modul yang diupload
        }

        // Cek apakah modul masih pending atau ditolak
        return in_array($modul->status, ['pending', 'rejected']);
    }

    public function isAccessibleForUser($penyusunApplicationId): bool
    {
        // Tahap dapat diakses jika:
        // 1. Sudah mulai (tanggal_mulai <= sekarang)
        // 2. Dan (masih dalam periode atau ada modul yang belum selesai)
        $now = Carbon::now()->toDateString();
        
        if ($this->tanggal_mulai > $now) {
            return false; // Belum mulai
        }

        // Jika masih dalam periode, pasti bisa diakses
        if ($this->tanggal_selesai >= $now) {
            return true;
        }

        // Jika sudah lewat periode, cek apakah ada modul yang belum selesai
        return $this->hasUnfinishedModuls($penyusunApplicationId);
    }

    public function getModulStart(): int
    {
        if ($this->tahap == 1) {
            return 1;
        } elseif ($this->tahap == 2) {
            return 3;
        } elseif ($this->tahap == 3) {
            return 5;
        } else {
            return ($this->tahap - 3) + 5;
        }
    }

    public function getModulEnd(): int
    {
        if ($this->tahap == 1) {
            return 2;
        } elseif ($this->tahap == 2) {
            return 4;
        } elseif ($this->tahap == 3) {
            return 6;
        } else {
            return ($this->tahap - 3) + 6;
        }
    }

    public function scopeCurrent($query)
    {
        $now = Carbon::now()->toDateString();
        return $query->where('tanggal_mulai', '<=', $now)
                    ->where('tanggal_selesai', '>=', $now);
    }

    public function scopeAccessible($query)
    {
        $now = Carbon::now()->toDateString();
        return $query->where('tanggal_mulai', '<=', $now);
    }

    public function scopeGlobal($query)
    {
        // Untuk tahap global (semua tahap sekarang adalah global)
        return $query;
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->isCurrentlyActive();
    }
}
