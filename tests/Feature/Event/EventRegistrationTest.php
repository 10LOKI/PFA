<?php

use App\Models\Event;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
    // Clear Spatie's permission cache after seeding
    app(PermissionRegistrar::class)->forgetCachedPermissions();

    $this->partner = User::factory()->partner()->create();

    $this->student = User::factory()->student()->create();
    $this->student->givePermissionTo([
        'event.browse', 'event.register', 'event.checkin',
        'reward.browse', 'reward.redeem',
        'comment.create', 'feedback.create', 'certificate.download',
    ]);

    $this->admin = User::factory()->admin()->create();
    $this->admin->givePermissionTo([
        'partner.kyc-approve', 'partner.kyc-reject',
        'event.approve', 'event.reject',
        'user.manage', 'analytics.view',
    ]);

    // CSRF token for tests
    $this->csrfToken = 'test-csrf-token';
});

describe('Event Registration', function () {
    test('student can register for an approved event', function () {
        $event = Event::create([
            'partner_id' => $this->partner->id,
            'title' => 'Test Event',
            'description' => 'Test description',
            'category' => 'Sport',
            'city' => 'Paris',
            'address' => '123 Test St',
            'starts_at' => now()->addDays(7),
            'ends_at' => now()->addDays(7)->addHours(3),
            'volunteer_quota' => 10,
            'duration_hours' => 3,
            'points_reward' => 100,
            'status' => 'approved',
        ]);

        Mail::fake();

        $this->withSession(['_token' => $this->csrfToken]);
        $response = $this->actingAs($this->student)
            ->withHeaders(['referer' => route('events.show', $event)])
            ->post(route('events.register', $event), ['_token' => $this->csrfToken]);

        $response->assertRedirect(route('events.show', $event));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('event_user', [
            'event_id' => $event->id,
            'user_id' => $this->student->id,
            'status' => 'registered',
        ]);
    });

    test('student cannot register twice for the same event', function () {
        $event = Event::create([
            'partner_id' => $this->partner->id,
            'title' => 'Test Event',
            'description' => 'Test description',
            'category' => 'Sport',
            'city' => 'Paris',
            'address' => '123 Test St',
            'starts_at' => now()->addDays(7),
            'ends_at' => now()->addDays(7)->addHours(3),
            'volunteer_quota' => 10,
            'duration_hours' => 3,
            'points_reward' => 100,
            'status' => 'approved',
        ]);

        // First registration
        $event->participants()->attach($this->student->id, [
            'status' => 'registered',
            'qr_token' => Str::uuid()->toString(),
        ]);

        $this->withSession(['_token' => $this->csrfToken]);
        $response = $this->actingAs($this->student)
            ->withHeaders(['referer' => route('events.show', $event)])
            ->post(route('events.register', $event), ['_token' => $this->csrfToken]);

        $response->assertRedirect(route('events.show', $event));
        $response->assertSessionHas('error', 'You are already registered for this event.');
        $this->assertEquals(1, $event->participants()->count());
    });

    test('student cannot register for a full event', function () {
        $event = Event::create([
            'partner_id' => $this->partner->id,
            'title' => 'Full Event',
            'description' => 'Event with no slots',
            'category' => 'Sport',
            'city' => 'Paris',
            'address' => '123 Test St',
            'starts_at' => now()->addDays(7),
            'ends_at' => now()->addDays(7)->addHours(3),
            'volunteer_quota' => 2,
            'duration_hours' => 3,
            'points_reward' => 100,
            'status' => 'approved',
        ]);

        // Fill the event with participants
        $users = User::factory(2)->create();
        foreach ($users as $user) {
            $event->participants()->attach($user->id, [
                'status' => 'registered',
                'qr_token' => Str::uuid()->toString(),
            ]);
        }
        $this->assertTrue($event->isFull());

        $this->withSession(['_token' => $this->csrfToken]);
        $response = $this->actingAs($this->student)
            ->withHeaders(['referer' => route('events.show', $event)])
            ->post(route('events.register', $event), ['_token' => $this->csrfToken]);

        $response->assertRedirect(route('events.show', $event));
        $response->assertSessionHas('error', 'Event is full. Registration failed.');
        $this->assertDatabaseMissing('event_user', [
            'event_id' => $event->id,
            'user_id' => $this->student->id,
        ]);
    });

    test('student cannot register for a non-approved event', function () {
        $event = Event::create([
            'partner_id' => $this->partner->id,
            'title' => 'Pending Event',
            'description' => 'Not approved yet',
            'category' => 'Sport',
            'city' => 'Paris',
            'address' => '123 Test St',
            'starts_at' => now()->addDays(7),
            'ends_at' => now()->addDays(7)->addHours(3),
            'volunteer_quota' => 10,
            'duration_hours' => 3,
            'points_reward' => 100,
            'status' => 'pending',
        ]);

        $this->withSession(['_token' => $this->csrfToken]);
        $response = $this->actingAs($this->student)
            ->withHeaders(['referer' => route('events.show', $event)])
            ->post(route('events.register', $event), ['_token' => $this->csrfToken]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('event_user', [
            'event_id' => $event->id,
            'user_id' => $this->student->id,
        ]);
    });

    test('unauthenticated user cannot register for event', function () {
        $event = Event::create([
            'partner_id' => $this->partner->id,
            'title' => 'Test Event',
            'description' => 'Test description',
            'category' => 'Sport',
            'city' => 'Paris',
            'address' => '123 Test St',
            'starts_at' => now()->addDays(7),
            'ends_at' => now()->addDays(7)->addHours(3),
            'volunteer_quota' => 10,
            'duration_hours' => 3,
            'points_reward' => 100,
            'status' => 'approved',
        ]);

        $this->withSession(['_token' => $this->csrfToken]);
        $response = $this->post(route('events.register', $event), ['_token' => $this->csrfToken]);

        $response->assertRedirect(route('login'));
    });
});
