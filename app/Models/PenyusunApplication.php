<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PenyusunApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_penyusun',
        'email',
        'no_wa',
        'nip',
        'nidn',
        'judul_bahan_ajar',
        'jurusan',
        'semester',
        'mata_kuliah',
        'mata_kuliah_id',
        'draft_path',
        'setuju_informasi',
        'setuju_pelaksanaan',
        'setuju_jml_modul',
        'setuju_pembiayaan',
        'status',
        'approved_at',
        'validated_by',
        'rejection_reason',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'setuju_informasi' => 'boolean',
        'setuju_pelaksanaan' => 'boolean',
        'setuju_jml_modul' => 'boolean',
        'setuju_pembiayaan' => 'boolean',
    ];

    public function moduls(): HasMany
    {
        return $this->hasMany(Modul::class);
    }

    public function publicationModuls(): HasMany
    {
        return $this->hasMany(PublicationModul::class);
    }

    // public function tahapPenyusunans(): HasMany
    // {
    //     return $this->hasMany(TahapPenyusunan::class);
    // }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    public function finalDrafts(): HasMany
    {
        return $this->hasMany(FinalDraft::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    // Accessor untuk mendapatkan nama user
    public function getUserNameAttribute()
    {
        return $this->user ? $this->user->name : $this->nama_penyusun;
    }

    // Accessor untuk mendapatkan email user
    public function getUserEmailAttribute()
    {
        return $this->user ? $this->user->email : $this->email;
    }

    /**
     * Cek apakah email sudah digunakan oleh penyusun yang sudah divalidasi admin
     */
    public static function isEmailValidatedByAdmin(string $email): bool
    {
        return self::where('email', $email)
            ->where('status', 'approved')
            ->exists();
    }

    /**
     * Cek apakah email sudah pernah digunakan untuk pendaftaran (baik pending maupun approved)
     */
    public static function isEmailUsed(string $email): bool
    {
        return self::where('email', $email)->exists();
    }
}
