<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Notification as NotificationModel;
use App\Models\User;
use App\Notifications\EventCreatedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $query = Event::query();

        // Filter by role:
        // - Admin: sees all events
        // - Partner: sees their own events (any status) + all approved events (unless filtering to own only)
        // - Student: sees only approved events
        if (auth()->user()->isAdmin()) {
            // no filter
        } elseif (auth()->user()->isPartner()) {
            // If partner wants to see only their own events
            if ($request->has('mine') && $request->boolean('mine')) {
                $query->where('partner_id', auth()->id());
            } else {
                $query->where(function ($q) {
                    $q->where('partner_id', auth()->id())
                        ->orWhere('status', 'approved');
                });
            }
        } else {
            $query->where('status', 'approved');
        }

        // Search filter (title or description)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        // City filter
        if ($request->filled('city')) {
            $query->where('city', 'like', '%'.$request->input('city').'%');
        }

        // Category filter (interests checkboxes)
        if ($request->filled('interests') && is_array($request->input('interests'))) {
            $categories = $request->input('interests');
            $query->whereIn('category', $categories);
        }

        $events = $query->latest('starts_at')->paginate(12)->withQueryString();

        $categories = ['Environnement', 'Éducation', 'Santé', 'Social', 'Culture', 'Sport', 'Technologie', 'Autre'];

        return view('events.index', compact('events', 'categories'));
    }

    public function show(Event $event): View
    {
        $this->authorize('view', $event);

        // Block access only if event is not approved AND user is not admin AND not the partner who created it
        if (! $event->isApproved() && ! auth()->user()->isAdmin() && auth()->id() !== $event->partner_id) {
            abort(403);
        }

        $event->load('participants');

        $myRegistration = null;
        if (auth()->check()) {
            $myRegistration = $event->participants()
                ->where('user_id', auth()->id())
                ->first();
        }

        return view('events.show', compact('event', 'myRegistration'));
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
        $data['status'] = auth()->user()->partner?->isApproved() ? 'approved' : 'pending';

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        $event = Event::create($data);

        // Send notification to partner (event creator) and all admins
        $event->partner->notify(new EventCreatedNotification($event));
        User::where('role', 'admin')->get()->each(function ($admin) use ($event) {
            $admin->notify(new EventCreatedNotification($event));
        });

        $message = $data['status'] === 'approved'
            ? 'Event created and approved successfully.'
            : 'Event created successfully. En attente d\'approbation par l\'admin.';

        return redirect()->route('events.show', $event)->with('success', $message);
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

    public function approve(Event $event): RedirectResponse
    {
        $this->authorize('approve', $event);

        $event->update(['status' => 'approved']);

        // Notify students about the new approved event
        $students = User::where('role', 'student')->get();
        foreach ($students as $student) {
            NotificationModel::create([
                'user_id' => $student->id,
                'type' => 'event_approved',
                'title' => 'Nouvel événement approuvé',
                'message' => $event->title,
                'link' => route('events.show', $event),
                'event_id' => $event->id,
                'read' => false,
            ]);
        }

        return redirect()->back()
            ->with('success', 'Event approved.');
    }

    public function reject(Event $event): RedirectResponse
    {
        $this->authorize('approve', $event);

        $event->update(['status' => 'rejected']);

        return redirect()->back()
            ->with('success', 'Event rejected.');
    }
}
