<?php

namespace App\Http\Controllers;

use App\Actions\Event\GenerateQrAction;
use App\Models\Event;
use App\Models\Notification as NotificationModel;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class EventController extends Controller
{
    public function __construct(private GenerateQrAction $generateQr) {}

    public function index(Request $request): View
    {
        // Admins see all events, others only approved
        if (auth()->user()->isAdmin()) {
            $events = Event::latest('starts_at')->paginate(12);
        } else {
            $events = Event::where('status', 'approved')
                ->latest('starts_at')
                ->paginate(12);
        }

        $categories = ['Environnement', 'Éducation', 'Santé', 'Social', 'Culture', 'Sport', 'Technologie', 'Autre'];

        return view('events.index', compact('events', 'categories'));
    }

    public function show(Event $event): View
    {
        $this->authorize('view', $event);

        // Only show approved events to non-admins
        if (! $event->isApproved() && ! auth()->user()->isAdmin()) {
            abort(403);
        }

        $event->load('participants');

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
        $data['status'] = auth()->user()->partner?->isApproved() ? 'approved' : 'pending'; // Auto-approve for KYC approved partners

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        $event = Event::create($data);

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
