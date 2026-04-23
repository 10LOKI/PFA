<?php

use App\Models\Event;
use App\Models\User;
use Database\Seeders\PermissionSeeder;

beforeEach(function () {
    // Seed required permissions
    $this->seed(PermissionSeeder::class);

    // Create users with different roles and assign permissions
    $this->admin = User::factory()->admin()->create();
    $this->admin->givePermissionTo([
        'partner.kyc-approve', 'partner.kyc-reject',
        'event.approve', 'event.reject',
        'user.manage', 'analytics.view',
    ]);

    $this->partner = User::factory()->partner()->create();
    $this->partner->givePermissionTo([
        'event.browse', 'event.create', 'event.update', 'event.delete',
        'checkin.validate', 'student.rate',
    ]);

    $this->student = User::factory()->student()->create();
    $this->student->givePermissionTo([
        'event.browse', 'event.register', 'event.checkin',
        'reward.browse', 'reward.redeem',
        'comment.create', 'feedback.create', 'certificate.download',
    ]);

    // Create events with different statuses
    $this->partnerPendingEvent = Event::create([
        'partner_id' => $this->partner->id,
        'title' => 'Partner Pending Event',
        'description' => 'This is a pending event created by partner',
        'category' => 'Environnement',
        'city' => 'Paris',
        'address' => '123 Test St',
        'starts_at' => now()->addDays(7),
        'ends_at' => now()->addDays(7)->addHours(3),
        'volunteer_quota' => 10,
        'duration_hours' => 3,
        'points_reward' => 100,
        'status' => 'pending',
    ]);

    $this->partnerApprovedEvent = Event::create([
        'partner_id' => $this->partner->id,
        'title' => 'Partner Approved Event',
        'description' => 'This is an approved event created by partner',
        'category' => 'Sport',
        'city' => 'Lyon',
        'address' => '456 Test Ave',
        'starts_at' => now()->addDays(14),
        'ends_at' => now()->addDays(14)->addHours(4),
        'volunteer_quota' => 5,
        'duration_hours' => 4,
        'points_reward' => 150,
        'status' => 'approved',
    ]);

    $this->otherPartner = User::factory()->partner()->create();
    $this->otherPartner->givePermissionTo([
        'event.browse', 'event.create', 'event.update', 'event.delete',
        'checkin.validate', 'student.rate',
    ]);
    $this->otherPartnerPendingEvent = Event::create([
        'partner_id' => $this->otherPartner->id,
        'title' => 'Other Partner Pending Event',
        'description' => 'This is a pending event created by another partner',
        'category' => 'Culture',
        'city' => 'Marseille',
        'address' => '789 Test Blvd',
        'starts_at' => now()->addDays(21),
        'ends_at' => now()->addDays(21)->addHours(2),
        'volunteer_quota' => 8,
        'duration_hours' => 2,
        'points_reward' => 80,
        'status' => 'pending',
    ]);

    $this->approvedEvent = Event::create([
        'partner_id' => $this->otherPartner->id,
        'title' => 'Public Approved Event',
        'description' => 'This is an approved event visible to all',
        'category' => 'Éducation',
        'city' => 'Bordeaux',
        'address' => '101 Test Rd',
        'starts_at' => now()->addDays(30),
        'ends_at' => now()->addDays(30)->addHours(5),
        'volunteer_quota' => 15,
        'duration_hours' => 5,
        'points_reward' => 200,
        'status' => 'approved',
    ]);
});

describe('Event visibility', function () {
    // ... existing tests ...

    test('partner can access edit page for their own event', function () {
        $response = $this->actingAs($this->partner)->get(route('events.edit', $this->partnerApprovedEvent));
        $response->assertSuccessful();
        $response->assertSee('EDIT MISSION');
    });

    test('partner cannot access edit page for other partners event', function () {
        $response = $this->actingAs($this->partner)->get(route('events.edit', $this->otherPartnerPendingEvent));
        $response->assertForbidden();
    });

    test('partner can update their own approved event', function () {
        $newTitle = 'Updated Title';
        $response = $this->actingAs($this->partner)->put(route('events.update', $this->partnerApprovedEvent), [
            'title' => $newTitle,
            'description' => 'Updated description',
            'category' => 'Sport',
            'city' => 'Lyon',
            'address' => '456 Test Ave',
            'starts_at' => $this->partnerApprovedEvent->starts_at->format('Y-m-d\TH:i'),
            'ends_at' => $this->partnerApprovedEvent->ends_at->format('Y-m-d\TH:i'),
            'volunteer_quota' => 5,
            'duration_hours' => 4,
            'points_reward' => 150,
        ]);
        $response->assertRedirect(route('events.show', $this->partnerApprovedEvent));
        $this->assertDatabaseHas('events', ['id' => $this->partnerApprovedEvent->id, 'title' => $newTitle]);
    });

    test('partner cannot update other partners event', function () {
        $response = $this->actingAs($this->partner)->put(route('events.update', $this->otherPartnerPendingEvent), [
            'title' => 'Hacked Title',
            'description' => 'Hacked description',
            'category' => 'Sport',
            'city' => 'Marseille',
            'address' => '789 Test Blvd',
            'starts_at' => $this->otherPartnerPendingEvent->starts_at->format('Y-m-d\TH:i'),
            'ends_at' => $this->otherPartnerPendingEvent->ends_at->format('Y-m-d\TH:i'),
            'volunteer_quota' => 8,
            'duration_hours' => 2,
            'points_reward' => 80,
        ]);
        $response->assertForbidden();
        $this->assertDatabaseMissing('events', ['id' => $this->otherPartnerPendingEvent->id, 'title' => 'Hacked Title']);
    });

    test('partner can cancel their own pending event', function () {
        $event = Event::create([
            'partner_id' => $this->partner->id,
            'title' => 'To Cancel',
            'description' => 'Will be cancelled',
            'category' => 'Autre',
            'city' => 'Paris',
            'address' => '999 Test',
            'starts_at' => now()->addDays(10),
            'ends_at' => now()->addDays(10)->addHours(2),
            'volunteer_quota' => 3,
            'duration_hours' => 2,
            'points_reward' => 50,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->partner)->delete(route('events.destroy', $event));
        $response->assertRedirect(route('events.index'));
        $this->assertDatabaseHas('events', ['id' => $event->id, 'status' => 'cancelled']);
    });

    test('partner cannot delete other partners event', function () {
        $initialTitle = $this->otherPartnerPendingEvent->title;
        $response = $this->actingAs($this->partner)->delete(route('events.destroy', $this->otherPartnerPendingEvent));
        $response->assertForbidden();
        $this->assertDatabaseHas('events', ['id' => $this->otherPartnerPendingEvent->id, 'title' => $initialTitle]);
    });
});

describe('Event index filtering', function () {
    test('admin sees all events in index', function () {
        $response = $this->actingAs($this->admin)->get(route('events.index'));
        $response->assertSuccessful();
        $response->assertSee($this->partnerPendingEvent->title);
        $response->assertSee($this->partnerApprovedEvent->title);
        $response->assertSee($this->otherPartnerPendingEvent->title);
        $response->assertSee($this->approvedEvent->title);
    });

    test('partner sees their own events plus all approved events in index', function () {
        $response = $this->actingAs($this->partner)->get(route('events.index'));
        $response->assertSuccessful();
        // Should see own pending and approved events
        $response->assertSee('Partner Pending Event');
        $response->assertSee('Partner Approved Event');
        // Should see other approved events
        $response->assertSee('Public Approved Event');
        // Should NOT see other partner's pending event
        $response->assertDontSee('Other Partner Pending Event');
    });

    test('student only sees approved events in index', function () {
        $response = $this->actingAs($this->student)->get(route('events.index'));
        $response->assertSuccessful();
        // Should not see any pending events
        $response->assertDontSee($this->partnerPendingEvent->title);
        $response->assertDontSee($this->otherPartnerPendingEvent->title);
        // Should see approved events
        $response->assertSee($this->partnerApprovedEvent->title);
        $response->assertSee($this->approvedEvent->title);
    });

    test('pending events are marked with PENDING status badge in index', function () {
        $response = $this->actingAs($this->partner)->get(route('events.index'));
        $response->assertSuccessful();
        $response->assertSee('PENDING');
    });
});
