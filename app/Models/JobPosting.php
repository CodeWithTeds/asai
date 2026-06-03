<?php

namespace App\Models;

use App\Enums\JobPostingStatus;
use App\Enums\JobPostingType;
use App\Policies\JobPostingPolicy;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'title',
    'description',
    'cover_image',
    'location',
    'type',
    'status',
    'starts_at',
    'expires_at',
    'created_by'
])]
#[UsePolicy(JobPostingPolicy::class)]
class JobPosting extends Model
{
    protected function casts(): array
    {
        return [
            'status' => JobPostingStatus::class,
            'type' => JobPostingType::class,
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Filter to only job postings currently visible to the public
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('status', JobPostingStatus::Open)
            ->where(
                fn(Builder $q) => $q
                    ->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now())
            )
            ->where(
                fn(Builder $q) => $q
                    ->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now())
            );
    }

    /**
     * The user who created this job posting
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * The applications submitted for this job posting.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }
}
