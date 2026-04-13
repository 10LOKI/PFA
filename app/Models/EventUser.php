<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EventUser extends Pivot
{
    protected $table = 'event_user';

    public $incrementing = true;

    protected $fillable = [
        'event_id',
        'user_id',
        'status',
        'checked_in_at',
        'points_earned',
        'partner_rating',
        'partner_feedback',
    ];

    protected function casts(): array
    {
        return [
            'checked_in_at' => 'datetime',
        ];
    }

    // --- Guard: points can only be set after QR check-in ---
    public function hasCheckedIn(): bool
    {
        return $this->checked_in_at !== null && $this->status === 'checked_in';
    }

    // --- Relationships ---
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
