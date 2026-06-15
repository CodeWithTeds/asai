<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService
    ) {}

    /**
     * Display the dashboard page with summary statistics and recent lists.
     */
    public function __invoke(): Response
    {
        return Inertia::render('Dashboard', $this->dashboardService->getSummaryData());
    }
}
