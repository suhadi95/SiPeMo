<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinalDraftActivityLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'final_draft_id',
        'actor_id',
        'actor_role',
        'action',
        'status_after',
        'notes',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $log) {
            if (!$log->created_at) {
                $log->created_at = now();
            }
        });
    }

    public function finalDraft(): BelongsTo
    {
        return $this->belongsTo(FinalDraft::class);
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function roleLabel(): string
    {
        return match ($this->actor_role) {
            'reviewer' => 'Reviewer',
            'lpm' => 'LPM',
            default => ucfirst($this->actor_role),
        };
    }

    public function actionLabel(): string
    {
        return match ($this->action) {
            'approved' => 'Diterima',
            'rejected' => 'Ditolak',
            default => ucfirst($this->action),
        };
    }

    public function isApproved(): bool
    {
        return $this->action === 'approved';
    }
}
