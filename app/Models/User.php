<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_penyusun',
        'is_lpm',
        'is_reviewer',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_penyusun' => 'boolean',
            'is_lpm' => 'boolean',
            'is_reviewer' => 'boolean',
        ];
    }

    /**
     * Get the appropriate dashboard route based on user role
     */
    public function getDashboardRoute(): string
    {
        if ($this->is_admin) {
            return 'admin.dashboard';
        }
        
        if ($this->is_penyusun) {
            return 'penyusun.dashboard';
        }

        if ($this->is_lpm) {
            return 'lpm.dashboard';
        }

        if ($this->is_reviewer) {
            return 'reviewer.dashboard';
        }
        
        return 'dashboard';
    }

    public function reviewerMataKuliahs(): HasMany
    {
        return $this->hasMany(MataKuliah::class, 'reviewer_id');
    }

    public function validatedFinalDrafts(): HasMany
    {
        return $this->hasMany(FinalDraft::class, 'validated_by');
    }

    public function lpmValidatedFinalDrafts(): HasMany
    {
        return $this->hasMany(FinalDraft::class, 'lpm_validated_by');
    }

    public function reviewerValidatedFinalDrafts(): HasMany
    {
        return $this->hasMany(FinalDraft::class, 'reviewer_validated_by');
    }

    public function assignedPenyusunApplications(): BelongsToMany
    {
        return $this->belongsToMany(PenyusunApplication::class, 'penyusun_application_reviewers', 'reviewer_id', 'penyusun_application_id')
            ->withPivot(['assigned_by', 'assigned_at'])
            ->withTimestamps();
    }

    public function finalDraftReviews(): HasMany
    {
        return $this->hasMany(FinalDraftReview::class, 'reviewer_id');
    }
}
