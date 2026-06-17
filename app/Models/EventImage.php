<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['event_id', 'image_path'])]
class EventImage extends Model
{
    /**
     * Get the event that owns this image.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
