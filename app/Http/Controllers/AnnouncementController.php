<?php

namespace App\Http\Controllers;

use App\Http\Requests\Announcement\StoreAnnouncementRequest;
use App\Http\Requests\Announcement\UpdateAnnouncementRequest;
use App\Models\Announcement;
use App\Services\AnnouncementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class AnnouncementController extends Controller
{
    public function __construct(
        private readonly AnnouncementService $announcementService
    ) {}

    /**
     * Show admin list of all announcements.
     */
    public function index(): Response
    {
        return Inertia::render('Announcements/Index', [
            'announcements' => $this->announcementService->getPaginated(),
        ]);
    }

    /**
     * Store a new announcement
     */
    public function store(StoreAnnouncementRequest $request): RedirectResponse
    {
        $this->announcementService->create($request->validated(), $request->user()->id);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Announcement created.']);

        return to_route('announcements.index');
    }

    /**
     * Update an existing announcement
     */
    public function update(UpdateAnnouncementRequest $request, Announcement $announcement): RedirectResponse
    {
        $this->announcementService->update($announcement, $request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Announcement updated.']);

        return to_route('announcements.index');
    }

    /**
     * Delete an announcement.
     */
    public function destroy(Announcement $announcement): RedirectResponse
    {
        Gate::authorize('delete', $announcement);

        $this->announcementService->delete($announcement);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Announcement deleted.']);

        return to_route('announcements.index');
    }
}
