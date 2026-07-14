<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Jurusan extends Model
{
    protected $table = 'jurusans';
    
    protected $fillable = [
        'nama_jurusan',
        'kode_jurusan',
        'deskripsi',
    ];

    public function mataKuliah(): HasMany
    {
        return $this->hasMany(MataKuliah::class);
    }

    public function finalDrafts(): HasMany
    {
        return $this->hasManyThrough(FinalDraft::class, MataKuliah::class);
    }
}
