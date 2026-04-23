<?php

use App\Models\Event;
use App\Models\User;
use App\Notifications\EventCreatedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/test-event', function () {
    // Authenticate as a user (assuming there's at least one user)
    $user = User::first();

    if (! $user) {
        return 'No users found. Please create a user first.';
    }

    // Login as the user
    Auth::login($user);

    // Create a test event
    $event = Event::create([
        'title' => 'Test Event for Mail',
        'description' => 'This is a test event to verify email notifications.',
        'category' => 'Test',
        'city' => 'Test City',
        'address' => 'Test Address',
        'starts_at' => now()->addDay(),
        'ends_at' => now()->addDay()->addHours(2),
        'volunteer_quota' => 10,
        'duration_hours' => 2,
        'points_reward' => 50,
        'partner_id' => $user->id,
        'status' => $user->partner?->isApproved() ? 'approved' : 'pending',
    ]);

    // Send notification
    $event->partner->notify(new EventCreatedNotification($event));
    User::where('role', 'admin')->get()->each(function ($admin) use ($event) {
        $admin->notify(new EventCreatedNotification($event));
    });

    return 'Test event created and notifications sent. Check Mailtrap for emails.';
});
