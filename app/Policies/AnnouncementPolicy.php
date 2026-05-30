<?php

namespace App\Policies;

use App\Models\Announcement;
use App\Models\User;

class AnnouncementPolicy
{
    /**
     * Any authenticated user can create an announcement.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Only the creator can update their own announcement.
     */
    public function update(User $user, Announcement $announcement): bool
    {
        return $user->id === $announcement->created_by;
    }

    /**
     * Only the creator can delete their own announcement.
     */
    public function delete(User $user, Announcement $announcement): bool
    {
        return $user->id === $announcement->created_by;
    }
}
