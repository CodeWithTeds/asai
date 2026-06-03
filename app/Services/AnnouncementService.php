<?php

namespace App\Services;

use App\Models\Announcement;
use App\Traits\HasVersionedCache;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

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
    public function getActive(?string $search = null): Collection
    {
        $version = $this->getCacheVersion();
        $cacheKey = "announcements:v{$version}:active:" . md5($search ?? '');

        $result = Cache::get($cacheKey);

        if (! ($result instanceof Collection)) {
            Cache::forget($cacheKey);

            $query = Announcement::active()
                ->select('id', 'title', 'body', 'starts_at', 'expires_at', 'created_at');

            if (! empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('body', 'like', "%{$search}%");
                });
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
     * Create a new announcement
     */
    public function create(array $data, int $createdBy): Announcement
    {
        $announcement = Announcement::create([
            ...$data,
            'created_by' => $createdBy
        ]);

        $this->clearCache();

        return $announcement;
    }

    /**
     * Update an existing announcement
     */
    public function update(Announcement $announcement, array $data): Announcement
    {
        $announcement->update($data);
        $this->clearCache();

        return $announcement->fresh();
    }

    /**
     * Delete an announcement
     */
    public function delete(Announcement $announcement): void
    {
        $announcement->delete();
        $this->clearCache();
    }
}
