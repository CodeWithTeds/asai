<?php

namespace App\Policies;

use App\Models\JobPosting;
use App\Models\User;

class JobPostingPolicy
{
    /**
     * Any authenticated user can create a job posting.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Only the creator can update their own job posting.
     */
    public function update(User $user, JobPosting $jobPosting): bool
    {
        return $user->id === $jobPosting->created_by;
    }

    /**
     * Only the creator can delete their own job posting.
     */
    public function delete(User $user, JobPosting $jobPosting): bool
    {
        return $user->id === $jobPosting->created_by;
    }
}
