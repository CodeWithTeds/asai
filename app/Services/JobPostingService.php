<?php

namespace App\Services;

use App\Models\JobPosting;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class JobPostingService
{
    /**
     * Return all active job postings for public display
     */
    public function getActive(): Collection
    {
        return JobPosting::active()
            ->select('id', 'title', 'description', 'location', 'type', 'starts_at', 'expires_at')
            ->latest()
            ->get();
    }

    /**
     * Return paginated list for admin management
     */
    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return JobPosting::with('creator:id,name')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Create a new job posting
     */
    public function create(array $data, int $createdBy): JobPosting
    {
        return JobPosting::create([
            ...$data,
            'created_by' => $createdBy,
        ]);
    }

    /**
     * Update an existing job posting
     */
    public function update(JobPosting $jobPosting, array $data): JobPosting
    {
        $jobPosting->update($data);

        return $jobPosting->fresh();
    }

    /**
     * Delete a job posting
     */
    public function delete(JobPosting $jobPosting): void
    {
        $jobPosting->delete();
    }
}
