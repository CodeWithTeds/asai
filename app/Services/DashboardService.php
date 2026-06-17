<?php

namespace App\Services;

use App\Models\Event;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Concerns\HasVersionedCache;
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
        $eventVersion = (int) Cache::get('events:cache_version', 1);
        $jobPostingVersion = (int) Cache::get('job_postings:cache_version', 1);
        $jobApplicationVersion = (int) Cache::get('job_applications:cache_version', 1);

        return crc32("{$eventVersion}_{$jobPostingVersion}_{$jobApplicationVersion}");
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
                    'events' => [
                        'total' => Event::count(),
                        'active' => Event::active()->count(),
                    ],
                    'job_postings' => [
                        'total' => JobPosting::count(),
                        'open' => JobPosting::active()->count(),
                    ],
                    'job_applications' => [
                        'total' => JobApplication::count(),
                    ],
                ],
                'recent_events' => Event::with('creator:id,name')
                    ->latest()
                    ->limit(5)
                    ->get()
                    ->map(fn ($event) => [
                        'id' => $event->id,
                        'title' => $event->title,
                        'type' => $event->type->value ?? $event->type,
                        'status' => $event->status->value ?? $event->status,
                        'creator_name' => $event->creator->name ?? 'System',
                        'created_at' => $event->created_at->toIso8601String(),
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
