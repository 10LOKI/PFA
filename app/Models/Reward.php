<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reward extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id', 'title', 'description', 'image', 'points_cost',
        'stock', 'min_grade', 'is_premium', 'is_active', 'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'is_premium' => 'boolean',
            'is_active' => 'boolean',
            'expires_at' => 'datetime',
        ];
    }

    public function isAvailable(): bool
    {
        return $this->is_active
            && ($this->expires_at === null || $this->expires_at->isFuture())
            && ($this->stock === null || $this->stock > 0);
    }

    public function isAccessibleBy(User $user): bool
    {
        $hierarchy = ['novice' => 0, 'pilier' => 1, 'ambassadeur' => 2];

        return ($hierarchy[$user->grade] ?? 0) >= ($hierarchy[$this->min_grade] ?? 0);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'partner_id');
    }

    public function redemptions(): HasMany
    {
        return $this->hasMany(RewardRedemption::class);
    }
}
