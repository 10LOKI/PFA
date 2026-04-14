<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasPermissions;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasPermissions;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'city',
        'phone',
        'establishment_id',
        'points_balance',
        'total_hours',
        'grade',
        'kyc_verified',
        'is_certified_partner',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'    => 'datetime',
            'password'             => 'hashed',
            'kyc_verified'         => 'boolean',
            'is_certified_partner' => 'boolean',
        ];
    }

    // --- Role helpers ---
    public function isAdmin(): bool   { return $this->role === 'admin'; }
    public function isPartner(): bool { return $this->role === 'partner'; }
    public function isStudent(): bool { return $this->role === 'student'; }

    // --- Relationships ---
    public function establishment(): BelongsTo
    {
        return $this->belongsTo(Establishment::class);
    }

    public function partner(): HasOne
    {
        return $this->hasOne(Partner::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    public function pointsTransactions(): HasMany
    {
        return $this->hasMany(PointsTransaction::class);
    }

    public function rewardRedemptions(): HasMany
    {
        return $this->hasMany(RewardRedemption::class);
    }

    public function events(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Event::class)
                    ->using(EventUser::class)
                    ->withPivot(['status', 'checked_in_at', 'points_earned', 'partner_rating', 'partner_feedback'])
                    ->withTimestamps();
    }

    public function hostedEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'partner_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }
}
