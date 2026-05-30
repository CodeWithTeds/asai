<?php

namespace App\Models;

use App\Enums\AnnouncementStatus;
use App\Policies\AnnouncementPolicy;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['title', 'body', 'status', 'starts_at', 'expires_at', 'created_by'])]
#[UsePolicy(AnnouncementPolicy::class)]
class Announcement extends Model
{
    protected function casts(): array
    {
        return [
            'status' => AnnouncementStatus::class,
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Filter to only announcements currently visible to the public
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('status', AnnouncementStatus::Active)
            ->where(fn (Builder $q) => $q
                ->whereNull('starts_at')
                ->orWhere('starts_at', '<=', now())
            )
            ->where(fn (Builder $q) => $q
                ->whereNull('expires_at')
                ->orWhere('expires_at', '>=', now())
            );
    }

    /**
     * The admin who created this announcement
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
