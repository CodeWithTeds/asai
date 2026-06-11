<?php

namespace App\Services;

use App\Models\JobApplication;
use App\Models\JobPosting;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;

class JobApplicationService
{
    /**
     * Create a new job application, storing the resume file securely.
     */
    public function apply(JobPosting $jobPosting, array $data, UploadedFile $resume): JobApplication
    {
        $path = $resume->store('job-applications/resumes', 'local');

        return JobApplication::create([
            ...$data,
            'job_posting_id' => $jobPosting->id,
            'resume_path' => $path,
        ]);
    }

    /**
     * Return paginated list of all applications for admin management, with optional search and filters.
     */
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = JobApplication::with('jobPosting:id,title')->latest();

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('applicant_name', 'like', "%{$search}%")
                    ->orWhere('applicant_email', 'like', "%{$search}%");
            });
        }

        if (isset($filters['has_license']) && $filters['has_license'] !== '') {
            $query->where('has_license', $filters['has_license'] === '1');
        }

        if (! empty($filters['job_posting_id'])) {
            $query->where('job_posting_id', $filters['job_posting_id']);
        }

        return $query->paginate($perPage)->withQueryString();
    }
}
