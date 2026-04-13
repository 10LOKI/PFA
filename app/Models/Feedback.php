<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'feedbackable_id',
        'feedbackable_type',
        'rating',
        'message',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
        ];
    }

    // --- Helper ---
    public function isValidRating(): bool
    {
        return $this->rating >= 1 && $this->rating <= 5;
    }

    // --- Relationships ---
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function feedbackable(): MorphTo
    {
        return $this->morphTo();
    }
}
