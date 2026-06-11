<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobPosting\StoreJobPostingRequest;
use App\Http\Requests\JobPosting\UpdateJobPostingRequest;
use App\Models\JobPosting;
use App\Services\JobPostingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class JobPostingController extends Controller
{
    public function __construct(
        private readonly JobPostingService $jobPostingService
    ) {}

    /**
     * Show admin list of all job postings with optional search and filters.
     */
    public function index(Request $request): Response
    {
        $filters = $request->only(['search', 'type', 'status']);

        return Inertia::render('JobPostings/Index', [
            'jobPostings' => $this->jobPostingService->getPaginated($filters),
            'filters' => $filters,
        ]);
    }

    /**
     * Store a new job posting with optional cover image.
     */
    public function store(StoreJobPostingRequest $request): RedirectResponse
    {
        $this->jobPostingService->create(
            $request->validated(),
            $request->user()->id,
            $request->file('cover_image'),
        );

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Job posting created.']);

        return to_route('job-postings.index');
    }

    /**
     * Update an existing job posting, replacing the cover image if provided.
     */
    public function update(UpdateJobPostingRequest $request, JobPosting $jobPosting): RedirectResponse
    {
        $this->jobPostingService->update(
            $jobPosting,
            $request->validated(),
            $request->file('cover_image'),
        );

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Job posting updated.']);

        return to_route('job-postings.index');
    }

    /**
     * Delete a job posting and its cover image.
     */
    public function destroy(JobPosting $jobPosting): RedirectResponse
    {
        Gate::authorize('delete', $jobPosting);

        $this->jobPostingService->delete($jobPosting);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Job posting deleted.']);

        return to_route('job-postings.index');
    }
}
