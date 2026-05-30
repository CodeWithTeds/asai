<?php

namespace App\Services;

use App\Models\Announcement;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class AnnouncementService
{
    /**
     * Return all active announcements for public display
     */
    public function getActive(): Collection
    {
        return Announcement::active()
            ->select('id', 'title', 'body', 'starts_at', 'expires_at')
            ->latest()
            ->get();
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
        return Announcement::create([
            ...$data,
            'created_by' => $createdBy
        ]);
    }

    /**
     * Update an existing announcement
     */
    public function update(Announcement $announcement, array $data): Announcement
    {
        $announcement->update($data);

        return $announcement->fresh();
    }

    /**
     * Delete an announcement
     */
    public function delete(Announcement $announcement): void
    {
        $announcement->delete();
    }
}
