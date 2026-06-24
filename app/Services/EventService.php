<?php

namespace App\Services;

use App\Concerns\HasVersionedCache;
use App\Enums\EventStatus;
use App\Models\Event;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
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
                ->with('images')
                ->select('id', 'title', 'body', 'type', 'starts_at', 'expires_at', 'created_at');

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
        $query = Event::with(['creator:id,name', 'images'])->latest();

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
            $status = EventStatus::tryFrom($filters['status']);

            if ($status === EventStatus::Active) {
                $query->where('status', EventStatus::Active)
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                            ->orWhere('expires_at', '>=', now());
                    });
            } elseif ($status === EventStatus::Inactive) {
                $query->where(function ($q) {
                    $q->where('status', EventStatus::Inactive)
                        ->orWhere(function ($sub) {
                            $sub->whereNotNull('expires_at')
                                ->where('expires_at', '<', now());
                        });
                });
            }
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Create a new event, storing any uploaded images
     */
    public function create(array $data, int $createdBy, ?array $images = null): Event
    {
        $event = Event::create([
            ...$data,
            'created_by' => $createdBy
        ]);

        if ($images) {
            foreach ($images as $img) {
                $path = $img->store('events/images', 'public');
                $event->images()->create(['image_path' => $path]);
            }
        }

        $this->clearCache();

        return $event;
    }

    /**
     * Update an existing event, adding new images or removing selected ones
     */
    public function update(Event $event, array $data, ?array $images = null): Event
    {
        // Handle image removals
        if (! empty($data['remove_images'])) {
            $imagesToRemove = $event->images()->whereIn('id', $data['remove_images'])->get();
            foreach ($imagesToRemove as $img) {
                Storage::disk('public')->delete($img->image_path);
                $img->delete();
            }
        }

        // Remove the flag before saving
        unset($data['remove_images']);

        $event->update($data);

        // Handle new image uploads
        if ($images) {
            foreach ($images as $img) {
                $path = $img->store('events/images', 'public');
                $event->images()->create(['image_path' => $path]);
            }
        }

        $this->clearCache();

        return $event->fresh();
    }

    /**
     * Delete an event and all its associated images from disk and database
     */
    public function delete(Event $event): void
    {
        $this->deleteImages($event);
        $event->delete();
        $this->clearCache();
    }

    /**
     * Remove all images associated with the event from disk
     */
    private function deleteImages(Event $event): void
    {
        foreach ($event->images as $img) {
            Storage::disk('public')->delete($img->image_path);
        }
    }
}
