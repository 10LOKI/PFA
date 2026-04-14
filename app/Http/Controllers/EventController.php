<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        $events = Event::where('status', 'approved')
                       ->latest('starts_at')
                       ->paginate(12);

        return view('events.index', compact('events'));
    }

    public function show(Event $event): View
    {
        $this->authorize('view', $event);
        $event->load(['partner', 'comments.user', 'feedbacks']);

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
            'title'              => ['required', 'string', 'max:255'],
            'description'        => ['required', 'string'],
            'category'           => ['nullable', 'string', 'max:100'],
            'city'               => ['required', 'string', 'max:100'],
            'address'            => ['required', 'string', 'max:255'],
            'starts_at'          => ['required', 'date', 'after:now'],
            'ends_at'            => ['required', 'date', 'after:starts_at'],
            'volunteer_quota'    => ['required', 'integer', 'min:1'],
            'duration_hours'     => ['required', 'integer', 'min:1'],
            'points_reward'      => ['required', 'integer', 'min:1'],
            'urgency_multiplier' => ['sometimes', 'numeric', 'min:1', 'max:3'],
            'image'              => ['nullable', 'image', 'max:2048'],
        ]);

        $data['partner_id'] = auth()->id();
        $data['status']     = auth()->user()->is_certified_partner ? 'approved' : 'pending';

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
            'title'              => ['required', 'string', 'max:255'],
            'description'        => ['required', 'string'],
            'category'           => ['nullable', 'string', 'max:100'],
            'city'               => ['required', 'string', 'max:100'],
            'address'            => ['required', 'string', 'max:255'],
            'starts_at'          => ['required', 'date'],
            'ends_at'            => ['required', 'date', 'after:starts_at'],
            'volunteer_quota'    => ['required', 'integer', 'min:1'],
            'duration_hours'     => ['required', 'integer', 'min:1'],
            'points_reward'      => ['required', 'integer', 'min:1'],
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
}
