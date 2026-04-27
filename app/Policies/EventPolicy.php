<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Event $event): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isPartner();
    }

    public function update(User $user, Event $event): bool
    {
        return $user->can('event.update')
            && $user->id === $event->partner_id
            && ! in_array($event->status, ['cancelled', 'rejected']);
    }

    public function delete(User $user, Event $event): bool
    {
        return $user->can('event.delete') && $user->id === $event->partner_id;
    }

    public function approve(User $user, Event $event): bool
    {
        return $user->isAdmin();
    }

    public function checkIn(User $user, Event $event): bool
    {
        // Only event owner (partner) or admin can check in students
        return $user->isAdmin() || $user->id === $event->partner_id;
    }

    public function checkOut(User $user, Event $event): bool
    {
        // Admin can always check out
        if ($user->isAdmin()) {
            return true;
        }

        // Event owner (partner) can check out students for their own events
        if ($user->id === $event->partner_id) {
            return true;
        }

        // Student can check out if they are registered for the event
        if ($user->isStudent()) {
            return $event->participants()->where('user_id', $user->id)->exists();
        }

        return false;
    }

    // generateQr permission removed - QR now student-specific
}
