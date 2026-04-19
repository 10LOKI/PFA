<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EventUser extends Pivot
{
    protected $table = 'event_user';

    public $incrementing = true;

    protected $fillable = [
        'event_id', 'user_id', 'status', 'checked_in_at', 'checked_out_at',
        'points_earned', 'partner_rating', 'partner_feedback',
    ];

    protected function casts(): array
    {
        return [
            'checked_in_at' => 'datetime',
            'checked_out_at' => 'datetime',
        ];
    }

    public function hasCheckedIn(): bool
    {
        return $this->checked_in_at !== null && $this->status === 'checked_in';
    }

    public function hasCheckedOut(): bool
    {
        return $this->checked_out_at !== null;
    }

    public function hasSavedToWishlist(): bool
    {
        return $this->status === 'wishlist';
    }

    public function actualDurationInMinutes(): int
    {
        if (! $this->checked_in_at || ! $this->checked_out_at) {
            return 0;
        }

        return (int) $this->checked_in_at->diffInMinutes($this->checked_out_at);
    }

    public function proRatedHours(): float
    {
        $actualMinutes = $this->actualDurationInMinutes();

        if ($actualMinutes < 30) {
            return 0;
        }

        $actualHours = $actualMinutes / 60;
        $eventHours = $this->event?->duration_hours ?? 0;

        return min($actualHours, $eventHours);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
