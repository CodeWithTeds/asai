<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    /**
     * Any authenticated user can create an Event.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Only the creator can update their own Event.
     */
    public function update(User $user, Event $event): bool
    {
        return $user->id === $event->created_by;
    }

    /**
     * Only the creator can delete their own Event.
     */
    public function delete(User $user, Event $event): bool
    {
        return $user->id === $event->created_by;
    }
}
