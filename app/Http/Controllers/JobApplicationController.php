<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobApplication\StoreJobApplicationRequest;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Services\JobApplicationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class JobApplicationController extends Controller
{
    public function __construct(
        private readonly JobApplicationService $jobApplicationService
    ) {}

    /**
     * Display a paginated list of job applications for admin, with optional search and filters.
     */
    public function index(Request $request): Response
    {
        $filters = $request->only(['search', 'has_license', 'job_posting_id']);

        return Inertia::render('JobApplications/Index', [
            'jobApplications' => $this->jobApplicationService->getPaginated($filters),
            'filters' => $filters,
        ]);
    }

    /**
     * Display the specific job application details.
     */
    public function show(JobApplication $jobApplication): Response
    {
        $jobApplication->load('jobPosting:id,title');

        return Inertia::render('JobApplications/Show', [
            'jobApplication' => $jobApplication,
        ]);
    }

    /**
     * Store a newly created job application in storage.
     */
    public function store(StoreJobApplicationRequest $request, JobPosting $jobPosting): RedirectResponse
    {
        $this->jobApplicationService->apply(
            $jobPosting,
            $request->safe()->except(['resume']),
            $request->file('resume'),
        );

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Application submitted successfully.']);

        return back();
    }

    /**
     * Download the resume.
     */
    public function resume(JobApplication $jobApplication, Request $request)
    {
        $path = $jobApplication->resume_path;

        if (! Storage::disk('local')->exists($path)) {
            abort(404, 'Resume file not found.');
        }

        $disposition = $request->has('download') ? 'attachment' : 'inline';

        return response()->file(Storage::disk('local')->path($path), [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "$disposition; filename=\"resume.pdf\"",
        ]);
    }
}
