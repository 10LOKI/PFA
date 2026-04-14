<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id', 'title', 'description', 'category', 'city', 'address',
        'latitude', 'longitude', 'starts_at', 'ends_at', 'volunteer_quota',
        'duration_hours', 'points_reward', 'urgency_multiplier', 'qr_code_token',
        'status', 'image',
    ];

    protected function casts(): array
    {
        return [
            'starts_at'          => 'datetime',
            'ends_at'            => 'datetime',
            'urgency_multiplier' => 'decimal:2',
            'latitude'           => 'decimal:7',
            'longitude'          => 'decimal:7',
        ];
    }

    public function isApproved(): bool { return $this->status === 'approved'; }
    public function isFull(): bool     { return $this->participants()->count() >= $this->volunteer_quota; }

    public function effectivePoints(): int
    {
        return (int) round($this->points_reward * $this->urgency_multiplier);
    }

    public function partner(): BelongsTo { return $this->belongsTo(User::class, 'partner_id'); }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
                    ->using(EventUser::class)
                    ->withPivot(['status', 'checked_in_at', 'points_earned', 'partner_rating', 'partner_feedback'])
                    ->withTimestamps();
    }

    public function comments(): MorphMany { return $this->morphMany(Comment::class, 'commentable'); }
    public function feedbacks(): MorphMany { return $this->morphMany(Feedback::class, 'feedbackable'); }
}
