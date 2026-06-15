<?php

namespace App\Services;

use App\Models\Announcement;
use App\Models\JobApplication;
use App\Models\JobPosting;

use App\Traits\HasVersionedCache;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    use HasVersionedCache;

    /**
     * Get the cache key prefix for versioning
     */
    protected function getCacheKeyPrefix(): string
    {
        return 'dashboard';
    }

    /**
     * Override getCacheVersion to return a combined version hash of dependencies
     */
    protected function getCacheVersion(): int
    {
        $announcementVersion = (int) Cache::get('announcements:cache_version', 1);
        $jobPostingVersion = (int) Cache::get('job_postings:cache_version', 1);
        $jobApplicationVersion = (int) Cache::get('job_applications:cache_version', 1);

        return crc32("{$announcementVersion}_{$jobPostingVersion}_{$jobApplicationVersion}");
    }

    /**
     * Get aggregated stats and recent activity for the dashboard.
     *
     * @return array
     */
    public function getSummaryData(): array
    {
        $version = $this->getCacheVersion();
        $cacheKey = "dashboard:v{$version}:summary";

        return Cache::remember($cacheKey, now()->addMinutes(60), function () {
            return [
                'stats' => [
                    'announcements' => [
                        'total' => Announcement::count(),
                        'active' => Announcement::active()->count(),
                    ],
                    'job_postings' => [
                        'total' => JobPosting::count(),
                        'open' => JobPosting::active()->count(),
                    ],
                    'job_applications' => [
                        'total' => JobApplication::count(),
                    ],
                ],
                'recent_announcements' => Announcement::with('creator:id,name')
                    ->latest()
                    ->limit(5)
                    ->get()
                    ->map(fn ($announcement) => [
                        'id' => $announcement->id,
                        'title' => $announcement->title,
                        'type' => $announcement->type->value ?? $announcement->type,
                        'status' => $announcement->status->value ?? $announcement->status,
                        'creator_name' => $announcement->creator->name ?? 'System',
                        'created_at' => $announcement->created_at->toIso8601String(),
                    ])->toArray(),
                'recent_job_postings' => JobPosting::with('creator:id,name')
                    ->latest()
                    ->limit(5)
                    ->get()
                    ->map(fn ($job) => [
                        'id' => $job->id,
                        'title' => $job->title,
                        'type' => $job->type->value ?? $job->type,
                        'status' => $job->status->value ?? $job->status,
                        'creator_name' => $job->creator->name ?? 'System',
                        'created_at' => $job->created_at->toIso8601String(),
                    ])->toArray(),
                'recent_job_applications' => JobApplication::with('jobPosting:id,title')
                    ->latest()
                    ->limit(5)
                    ->get()
                    ->map(fn ($application) => [
                        'id' => $application->id,
                        'applicant_name' => $application->applicant_name,
                        'applicant_email' => $application->applicant_email,
                        'job_title' => $application->jobPosting->title ?? 'Deleted Position',
                        'created_at' => $application->created_at->toIso8601String(),
                    ])->toArray(),
            ];
        });
    }
}
