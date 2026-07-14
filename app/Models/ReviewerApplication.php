<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewerApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_reviewer',
        'email',
        'no_wa',
        'nip',
        'nidn',
        'sertifikasi_path',
        'sertifikasi_name',
        'status',
        'rejection_reason',
        'approved_at',
        'validated_by',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }
}
