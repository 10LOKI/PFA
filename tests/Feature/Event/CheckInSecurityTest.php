<?php

use App\Models\Event;
use App\Models\EventUser;
use App\Models\User;
use Database\Seeders\PermissionSeeder;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);

    $this->admin = User::factory()->admin()->create();
    $this->partner1 = User::factory()->partner()->create();
    $this->partner1->givePermissionTo(['event.browse', 'event.create', 'event.update', 'event.delete', 'checkin.validate', 'student.rate']);
    $this->partner2 = User::factory()->partner()->create();
    $this->partner2->givePermissionTo(['event.browse', 'event.create', 'event.update', 'event.delete', 'checkin.validate', 'student.rate']);
    $this->student = User::factory()->student()->create();
    $this->student->givePermissionTo(['event.browse', 'event.register', 'event.checkin']);

    // CSRF token for tests
    $this->csrfToken = 'test-csrf-token';

    // Create owned events
    $this->eventA = Event::create([
        'partner_id' => $this->partner1->id,
        'title' => 'Event A',
        'description' => 'Partner1 event',
        'category' => 'Sport',
        'city' => 'Paris',
        'address' => '1 Test',
        'starts_at' => now()->addDays(1),
        'ends_at' => now()->addDays(1)->addHours(3),
        'volunteer_quota' => 5,
        'duration_hours' => 3,
        'points_reward' => 100,
        'status' => 'approved',
    ]);

    $this->eventB = Event::create([
        'partner_id' => $this->partner2->id,
        'title' => 'Event B',
        'description' => 'Partner2 event',
        'category' => 'Culture',
        'city' => 'Lyon',
        'address' => '2 Test',
        'starts_at' => now()->addDays(2),
        'ends_at' => now()->addDays(2)->addHours(2),
        'volunteer_quota' => 3,
        'duration_hours' => 2,
        'points_reward' => 80,
        'status' => 'approved',
    ]);

    // Student registration with QR token
    $this->registration = EventUser::create([
        'event_id' => $this->eventA->id,
        'user_id' => $this->student->id,
        'status' => 'registered',
        'qr_token' => 'test-token-123',
    ]);
});

describe('Check-in/Check-out Security — Event Ownership', function () {
    test('event owner can check in their own student', function () {
        $response = $this->actingAs($this->partner1)
            ->withSession(['_token' => $this->csrfToken])
            ->post(route('checkin.scan', ['token' => 'test-token-123']), ['_token' => $this->csrfToken]);

        $response->assertRedirect();
        $this->registration->refresh();
        $this->assertEquals('checked_in', $this->registration->status);
        $this->assertNotNull($this->registration->checked_in_at);
    });

    test('other partner cannot check in student for foreign event', function () {
        $response = $this->actingAs($this->partner2)
            ->withSession(['_token' => $this->csrfToken])
            ->post(route('checkin.scan', ['token' => 'test-token-123']), ['_token' => $this->csrfToken]);

        $response->assertForbidden();
        $this->registration->refresh();
        $this->assertNull($this->registration->checked_in_at);
    });

    test('admin can check in student for any event', function () {
        $response = $this->actingAs($this->admin)
            ->withSession(['_token' => $this->csrfToken])
            ->post(route('checkin.scan', ['token' => 'test-token-123']), ['_token' => $this->csrfToken]);

        $response->assertRedirect();
        $this->registration->refresh();
        $this->assertEquals('checked_in', $this->registration->status);
        $this->assertNotNull($this->registration->checked_in_at);
    });

    test('student cannot access check-in endpoint', function () {
        $response = $this->actingAs($this->student)
            ->withSession(['_token' => $this->csrfToken])
            ->post(route('checkin.scan', ['token' => 'test-token-123']), ['_token' => $this->csrfToken]);

        $response->assertForbidden();
    });

    test('event owner can check out their own student', function () {
        // Check-in via controller
        $this->actingAs($this->partner1)
            ->withSession(['_token' => $this->csrfToken])
            ->post(route('checkin.scan', ['token' => 'test-token-123']), ['_token' => $this->csrfToken])
            ->assertRedirect();

        $this->registration->refresh();
        $this->assertEquals('checked_in', $this->registration->status);

        // Simulate time passed: set checked_in_at to 2 hours ago
        $this->registration->update(['checked_in_at' => now()->subHours(2)]);

        // Check out
        $response = $this->actingAs($this->partner1)
            ->withSession(['_token' => $this->csrfToken])
            ->post(route('events.checkout', $this->eventA), [
                '_token' => $this->csrfToken,
                'student_id' => $this->student->id,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->registration->refresh();
        $this->assertNotNull($this->registration->checked_out_at);
        $this->assertGreaterThan(0, $this->registration->points_earned);
    });

    test('other partner cannot check out student for foreign event', function () {
        // Owner checks in the student first
        $this->actingAs($this->partner1)
            ->withSession(['_token' => $this->csrfToken])
            ->post(route('checkin.scan', ['token' => 'test-token-123']), ['_token' => $this->csrfToken])
            ->assertRedirect();

        $this->registration->refresh();
        // Simulate time passed
        $this->registration->update(['checked_in_at' => now()->subHours(2)]);

        // Attempt check-out by other partner
        $response = $this->actingAs($this->partner2)
            ->withSession(['_token' => $this->csrfToken])
            ->post(route('events.checkout', $this->eventA), [
                '_token' => $this->csrfToken,
                'student_id' => $this->student->id,
            ]);

        $response->assertForbidden();
        $this->registration->refresh();
        $this->assertNull($this->registration->checked_out_at);
    });
});

test('student can check themselves out after being checked in', function () {
    // Partner checks in the student first
    $this->actingAs($this->partner1)
        ->withSession(['_token' => $this->csrfToken])
        ->post(route('checkin.scan', ['token' => 'test-token-123']), ['_token' => $this->csrfToken])
        ->assertRedirect();

    $this->registration->refresh();
    $this->assertEquals('checked_in', $this->registration->status);

    // Simulate time passed for hours calculation
    $this->registration->update(['checked_in_at' => now()->subHours(2)]);

    $initialPoints = $this->student->points_balance;

    // Student self check-out
    $response = $this->actingAs($this->student)
        ->withSession(['_token' => $this->csrfToken])
        ->post(route('events.checkout', $this->eventA), [
            '_token' => $this->csrfToken,
            'student_id' => $this->student->id,
        ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->registration->refresh();
    $this->assertNotNull($this->registration->checked_out_at);
    $this->assertGreaterThan(0, $this->registration->points_earned);

    // Verify points balance increased by the earned amount
    $this->student->refresh();
    $this->assertEquals($initialPoints + $this->registration->points_earned, $this->student->points_balance);
});
