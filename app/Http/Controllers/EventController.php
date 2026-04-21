<?php

namespace App\Http\Controllers;

use App\Actions\Event\GenerateQrAction;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class EventController extends Controller
{
    public function __construct(private GenerateQrAction $generateQr) {}

    public function index(Request $request): View
    {
        $query = Event::query()->with(['partner', 'participants', 'likedBy']);

        // Get unique categories for filter options
        $categories = Event::select('category')
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->toArray();

        // City filter: default to user's city if not provided
        $city = $request->filled('city') ? $request->city : (auth()->check() ? auth()->user()->city : null);
        if ($city) {
            $query->where('city', 'like', '%'.$city.'%');
        }

        // Interests filter: use manual selection if provided; else fallback to user profile interests
        $selectedInterests = $request->input('interests', []);
        if (! empty($selectedInterests)) {
            $query->whereIn('category', $selectedInterests);
        } elseif (auth()->check() && empty($selectedInterests) && ! $request->has('interests')) {
            $userInterests = auth()->user()->interests ?? [];
            if (! empty($userInterests)) {
                $query->whereIn('category', $userInterests);
            }
        }

        // Keyword search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        // Default sorting: upcoming events first
        $query->where('starts_at', '>', now())
            ->orderBy('starts_at', 'asc');

        $events = $query->paginate(12)->withQueryString();

        return view('events.index', compact('events', 'categories'));
    }

    public function show(Event $event): View
    {
        $this->authorize('view', $event);

        $event->load(['participants', 'likedBy']);

        return view('events.show', compact('event'));
    }

    public function create(): View
    {
        $this->authorize('create', Event::class);

        return view('events.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Event::class);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:255'],
            'starts_at' => ['required', 'date', 'after:now'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'volunteer_quota' => ['required', 'integer', 'min:1'],
            'duration_hours' => ['required', 'integer', 'min:1'],
            'points_reward' => ['required', 'integer', 'min:1'],
            'urgency_multiplier' => ['sometimes', 'numeric', 'min:1', 'max:3'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $data['partner_id'] = auth()->id();
        $data['status'] = 'approved'; // Auto-approve for now

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        $event = Event::create($data);

        return redirect()->route('events.show', $event)
            ->with('success', 'Event created successfully.');
    }

    public function edit(Event $event): View
    {
        $this->authorize('update', $event);

        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $this->authorize('update', $event);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:255'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'volunteer_quota' => ['required', 'integer', 'min:1'],
            'duration_hours' => ['required', 'integer', 'min:1'],
            'points_reward' => ['required', 'integer', 'min:1'],
            'urgency_multiplier' => ['sometimes', 'numeric', 'min:1', 'max:3'],
        ]);

        $event->update($data);

        return redirect()->route('events.show', $event)
            ->with('success', 'Event updated.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $this->authorize('delete', $event);

        $event->update(['status' => 'cancelled']);

        return redirect()->route('events.index')
            ->with('success', 'Event cancelled.');
    }

    public function qr(Event $event): Response
    {
        $this->authorize('generate-qr', $event);

        if (! $event->qr_code_token) {
            $event = $this->generateQr->execute($event);
        }

        $svg = $this->generateQr->execute($event);

        return response($svg, 200, ['Content-Type' => 'image/svg+xml']);
    }
}
