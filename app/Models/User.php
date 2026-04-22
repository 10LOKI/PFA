<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasPermissions, HasRoles, Notifiable;

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
        'interests',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'kyc_verified' => 'boolean',
            'is_certified_partner' => 'boolean',
            'interests' => 'array',
        ];
    }

    // --- Role helpers ---
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->hasRole('admin');
    }

    public function isPartner(): bool
    {
        return $this->role === 'partner';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    // Spatie compatibility
    public function hasRole($role): bool
    {
        return $this->role === $role;
    }

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

    public function events(): BelongsToMany
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

    public function conversations(): BelongsToMany
    {
        return $this->belongsToMany(Conversation::class, 'conversation_user')
            ->withTimestamps();
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }

    public function likedEvents(): MorphToMany
    {
        return $this->morphedByMany(Event::class, 'likeable', 'likes', 'user_id', 'likeable_id');
    }

    // Override Notifiable trait - use our custom notifications table
    public function notifications($class = null)
    {
        return $this->morphToMany(Notification::class, 'notifiable', 'notification_usages')
            ->withPivot(['read_at', 'created_at', 'updated_at']);
    }

    // Use custom notifications table directly
    public function customNotifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id');
    }
}
