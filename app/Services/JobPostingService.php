<?php

namespace App\Services;

use App\Models\JobPosting;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class JobPostingService
{
    /**
     * Return all active job postings for public display
     */
    public function getActive(): Collection
    {
        return JobPosting::active()
            ->select('id', 'title', 'description', 'cover_image', 'location', 'type', 'starts_at', 'expires_at')
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
     * Create a new job posting, storing any uploaded cover image
     */
    public function create(array $data, int $createdBy, ?UploadedFile $image = null): JobPosting
    {
        if ($image) {
            $data['cover_image'] = $image->store('job-postings/covers', 'public');
        }

        return JobPosting::create([
            ...$data,
            'created_by' => $createdBy,
        ]);
    }

    /**
     * Update a job posting, replacing or removing the cover image as requested
     */
    public function update(JobPosting $jobPosting, array $data, ?UploadedFile $image = null): JobPosting
    {
        if ($image) {
            // New file uploaded — swap out the old one
            $this->deleteCoverImage($jobPosting);
            $data['cover_image'] = $image->store('job-postings/covers', 'public');
        } elseif (! empty($data['remove_cover_image'])) {
            // User explicitly cleared the image with no replacement
            $this->deleteCoverImage($jobPosting);
            $data['cover_image'] = null;
        }

        // Remove the flag before saving — not a real DB column
        unset($data['remove_cover_image']);

        $jobPosting->update($data);

        return $jobPosting->fresh();
    }

    /**
     * Delete a job posting and its associated cover image from disk
     */
    public function delete(JobPosting $jobPosting): void
    {
        $this->deleteCoverImage($jobPosting);
        $jobPosting->delete();
    }

    /**
     * Remove the cover image from disk if one exists
     */
    private function deleteCoverImage(JobPosting $jobPosting): void
    {
        if ($jobPosting->cover_image) {
            Storage::disk('public')->delete($jobPosting->cover_image);
        }
    }
}
