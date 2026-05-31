<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobPosting\StoreJobPostingRequest;
use App\Http\Requests\JobPosting\UpdateJobPostingRequest;
use App\Models\JobPosting;
use App\Services\JobPostingService;
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
     * Show admin list of all job postings.
     */
    public function index(): Response
    {
        return Inertia::render('JobPostings/Index', [
            'jobPostings' => $this->jobPostingService->getPaginated(),
        ]);
    }

    /**
     * Store a new job posting
     */
    public function store(StoreJobPostingRequest $request): RedirectResponse
    {
        $this->jobPostingService->create($request->validated(), $request->user()->id);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Job posting created.']);

        return to_route('job-postings.index');
    }

    /**
     * Update an existing job posting
     */
    public function update(UpdateJobPostingRequest $request, JobPosting $jobPosting): RedirectResponse
    {
        $this->jobPostingService->update($jobPosting, $request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Job posting updated.']);

        return to_route('job-postings.index');
    }

    /**
     * Delete a job posting.
     */
    public function destroy(JobPosting $jobPosting): RedirectResponse
    {
        Gate::authorize('delete', $jobPosting);

        $this->jobPostingService->delete($jobPosting);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Job posting deleted.']);

        return to_route('job-postings.index');
    }
}
