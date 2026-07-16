<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MataKuliah extends Model
{
    protected $table = 'mata_kuliahs';
    
    protected $fillable = [
        'jurusan_id',
        'nama_mata_kuliah',
        'kode_mata_kuliah',
        'semester',
        'sks',
        'deskripsi',
        'reviewer_id',
    ];

    protected $casts = [
        'semester' => 'integer',
        'sks' => 'integer',
    ];

    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function moduls(): HasMany
    {
        return $this->hasMany(Modul::class);
    }

    public function finalDrafts(): HasMany
    {
        return $this->hasMany(FinalDraft::class);
    }

    public function penyusunApplications(): HasMany
    {
        return $this->hasMany(PenyusunApplication::class, 'mata_kuliah_id');
    }

    /**
     * Jumlah bab modul sesuai ketentuan: 2 SKS = 6 bab, 3 SKS = 9 bab.
     */
    public function jumlahBab(): ?int
    {
        if (!$this->sks) {
            return null;
        }

        return (int) $this->sks * 3;
    }
}
