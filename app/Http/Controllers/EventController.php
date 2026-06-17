<?php

namespace App\Http\Controllers;

use App\Http\Requests\Event\StoreEventRequest;
use App\Http\Requests\Event\UpdateEventRequest;
use App\Models\Event;
use App\Services\EventService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class EventController extends Controller
{
    public function __construct(
        private readonly EventService $eventService
    ) {}

    /**
     * Show admin list of all event with optional search and filters.
     */
    public function index(Request $request): Response
    {
        $filters = $request->only(['search', 'type', 'status']);

        return Inertia::render('Events/Index', [
            'events' => $this->eventService->getPaginated($filters),
            'filters' => $filters,
        ]);
    }

    /**
     * Store a new event
     */
    public function store(StoreEventRequest $request): RedirectResponse
    {
        $this->eventService->create(
            $request->validated(),
            $request->user()->id,
            $request->file('images')
        );

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Event created.']);

        return to_route('events.manage.index');
    }

    /**
     * Update an existing event
     */
    public function update(UpdateEventRequest $request, Event $event): RedirectResponse
    {
        $this->eventService->update(
            $event,
            $request->validated(),
            $request->file('images')
        );

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Event updated.']);

        return to_route('events.manage.index');
    }

    /**
     * Delete an event.
     */
    public function destroy(Event $event): RedirectResponse
    {
        Gate::authorize('delete', $event);

        $this->eventService->delete($event);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Event deleted.']);

        return to_route('events.manage.index');
    }
}
