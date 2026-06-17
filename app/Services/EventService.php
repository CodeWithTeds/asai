<?php

namespace App\Services;

use App\Concerns\HasVersionedCache;
use App\Models\Event;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class EventService
{
    use HasVersionedCache;

    /**
     * Define the cache key prefix for versioning
     */
    protected function getCacheKeyPrefix(): string
    {
        return 'events';
    }

    /**
     * Return all active events for public display
     */
    public function getActive(?string $search = null, ?string $type = null): Collection
    {
        $version = $this->getCacheVersion();
        $cacheKey = "events:v{$version}:active:" . md5(($search ?? '') . ':' . ($type ?? ''));

        $result = Cache::get($cacheKey);

        if (! ($result instanceof Collection)) {
            Cache::forget($cacheKey);

            $query = Event::active()
                ->select('id', 'title', 'body', 'type', 'image', 'starts_at', 'expires_at', 'created_at');

            if (! empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('body', 'like', "%{$search}%");
                });
            }

            if (! empty($type) && $type !== 'all') {
                $query->where('type', $type);
            }

            $result = $query->latest()->get();
            Cache::put($cacheKey, $result, now()->addMinutes(60));
        }

        return $result;
    }

    /**
     * Return paginated list for admin management, with optional search and filters.
     */
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Event::with('creator:id,name')->latest();

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Create a new event, storing any uploaded image
     */
    public function create(array $data, int $createdBy, ?UploadedFile $image = null): Event
    {
        if ($image) {
            $data['image'] = $image->store('events/images', 'public');
        }

        $event = Event::create([
            ...$data,
            'created_by' => $createdBy
        ]);

        $this->clearCache();

        return $event;
    }

    /**
     * Update an existing event, replacing or removing the image as requested
     */
    public function update(Event $event, array $data, ?UploadedFile $image = null): Event
    {
        if ($image) {
            // New file uploaded — swap out the old one
            $this->deleteImage($event);
            $data['image'] = $image->store('events/images', 'public');
        } elseif (! empty($data['remove_image'])) {
            // User explicitly cleared the image with no replacement
            $this->deleteImage($event);
            $data['image'] = null;
        }

        // Remove the flag before saving — not a real DB column
        unset($data['remove_image']);

        $event->update($data);
        $this->clearCache();

        return $event->fresh();
    }

    /**
     * Delete an event and its associated image from disk
     */
    public function delete(Event $event): void
    {
        $this->deleteImage($event);
        $event->delete();
        $this->clearCache();
    }

    /**
     * Remove the image from disk if one exists
     */
    private function deleteImage(Event $event): void
    {
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }
    }
}
