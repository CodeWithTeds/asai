<?php

namespace App\Services;

use App\Models\Announcement;
use App\Traits\HasVersionedCache;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class AnnouncementService
{
    use HasVersionedCache;

    /**
     * Define the cache key prefix for versioning
     */
    protected function getCacheKeyPrefix(): string
    {
        return 'announcements';
    }

    /**
     * Return all active announcements for public display
     */
    public function getActive(?string $search = null, ?string $type = null): Collection
    {
        $version = $this->getCacheVersion();
        $cacheKey = "announcements:v{$version}:active:" . md5(($search ?? '') . ':' . ($type ?? ''));

        $result = Cache::get($cacheKey);

        if (! ($result instanceof Collection)) {
            Cache::forget($cacheKey);

            $query = Announcement::active()
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
     * Return paginated list for admin management
     */
    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Announcement::with('creator:id,name')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Create a new announcement, storing any uploaded image
     */
    public function create(array $data, int $createdBy, ?UploadedFile $image = null): Announcement
    {
        if ($image) {
            $data['image'] = $image->store('announcements/images', 'public');
        }

        $announcement = Announcement::create([
            ...$data,
            'created_by' => $createdBy
        ]);

        $this->clearCache();

        return $announcement;
    }

    /**
     * Update an existing announcement, replacing or removing the image as requested
     */
    public function update(Announcement $announcement, array $data, ?UploadedFile $image = null): Announcement
    {
        if ($image) {
            // New file uploaded — swap out the old one
            $this->deleteImage($announcement);
            $data['image'] = $image->store('announcements/images', 'public');
        } elseif (! empty($data['remove_image'])) {
            // User explicitly cleared the image with no replacement
            $this->deleteImage($announcement);
            $data['image'] = null;
        }

        // Remove the flag before saving — not a real DB column
        unset($data['remove_image']);

        $announcement->update($data);
        $this->clearCache();

        return $announcement->fresh();
    }

    /**
     * Delete an announcement and its associated image from disk
     */
    public function delete(Announcement $announcement): void
    {
        $this->deleteImage($announcement);
        $announcement->delete();
        $this->clearCache();
    }

    /**
     * Remove the image from disk if one exists
     */
    private function deleteImage(Announcement $announcement): void
    {
        if ($announcement->image) {
            Storage::disk('public')->delete($announcement->image);
        }
    }
}
