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
     * Return paginated list of all applications for admin management.
     */
    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return JobApplication::with('jobPosting:id,title')
            ->latest()
            ->paginate($perPage);
    }
}
