<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('event.browse');
    }

    public function view(User $user, Event $event): bool
    {
        return $event->isApproved() || $user->id === $event->partner_id || $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->can('event.create') && $user->isPartner();
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
        return $user->can('event.approve') && $user->isAdmin();
    }
}
