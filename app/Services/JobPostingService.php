<?php

namespace App\Services;

use App\Models\JobPosting;
use App\Concerns\HasVersionedCache;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class JobPostingService
{
    use HasVersionedCache;

    /**
     * Define the cache key prefix for versioning
     */
    protected function getCacheKeyPrefix(): string
    {
        return 'job_postings';
    }

    /**
     * Return all active job postings for public display
     */
    public function getActive(): Collection
    {
        $version = $this->getCacheVersion();
        $cacheKey = "job_postings:v{$version}:active";

        $result = Cache::get($cacheKey);

        if (! ($result instanceof Collection)) {
            Cache::forget($cacheKey);

            $result = JobPosting::active()
                ->select(
                    'id',
                    'title',
                    'description',
                    'cover_image',
                    'location',
                    'type',
                    'starts_at',
                    'expires_at',
                    'created_by',
                    'created_at'
                )
                ->latest()
                ->get();

            Cache::put($cacheKey, $result, now()->addMinutes(60));
        }

        return $result;
    }

    /**
     * Return paginated list for admin management, with optional search and filters.
     */
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = JobPosting::with('creator:id,name')->latest();

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
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
     * Create a new job posting, storing any uploaded cover image
     */
    public function create(array $data, int $createdBy, ?UploadedFile $image = null): JobPosting
    {
        if ($image) {
            $data['cover_image'] = $image->store('job-postings/covers', 'public');
        }

        $jobPosting = JobPosting::create([
            ...$data,
            'created_by' => $createdBy,
        ]);

        $this->clearCache();

        return $jobPosting;
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

        $this->clearCache();

        return $jobPosting->fresh();
    }

    /**
     * Delete a job posting and its associated cover image from disk
     */
    public function delete(JobPosting $jobPosting): void
    {
        $this->deleteCoverImage($jobPosting);
        $jobPosting->delete();
        $this->clearCache();
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
