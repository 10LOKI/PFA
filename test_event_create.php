<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->boot();

use App\Models\Event;
use App\Models\User;
use App\Notifications\EventCreatedNotification;
use Illuminate\Support\Facades\Auth;

// Create a test user if none exists
$user = User::first();
if (! $user) {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
        'role' => 'partner',
    ]);

    // Create a partner profile for the user
    $user->partner()->create([
        'establishment_id' => 1, // Assuming establishment ID 1 exists
        'is_approved' => true,
    ]);
}

// Login as the user
Auth::login($user);

// Create a test event
$event = Event::create([
    'title' => 'Test Event for Mail Notification',
    'description' => 'This is a test event to verify email notifications work correctly.',
    'category' => 'Test',
    'city' => 'Test City',
    'address' => 'Test Address',
    'starts_at' => now()->addDay(),
    'ends_at' => now()->addDay()->addHours(2),
    'volunteer_quota' => 10,
    'duration_hours' => 2,
    'points_reward' => 50,
    'partner_id' => $user->id,
    'status' => $user->partner->is_approved ? 'approved' : 'pending',
]);

echo "Test event created with ID: {$event->id}<br>";

// Send notification to partner (event creator) and all admins
echo 'Sending notification to partner...<br>';
$event->partner->notify(new EventCreatedNotification($event));
echo 'Partner notification queued.<br>';

echo 'Sending notification to admins...<br>';
$admins = User::where('role', 'admin')->get();
foreach ($admins as $admin) {
    $admin->notify(new EventCreatedNotification($event));
}
echo "Admin notifications queued. Total admins: {$admins->count()}<br>";

echo '<br>Check your Mailtrap inbox for the emails.<br>';
echo 'Check the queue with: php artisan queue:work --stop-when-empty';
