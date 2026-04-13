<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PointsTransaction extends Model
{
    use HasFactory;

    /**
     * Immutable ledger — no updates, no deletes.
     * All point operations must INSERT a new row via DB::transaction().
     */
    public $timestamps = true;
    const UPDATED_AT = null; // ledger rows are never updated

    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'balance_after',
        'source_type',
        'source_id',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'amount'        => 'integer',
            'balance_after' => 'integer',
        ];
    }

    // --- Immutability guard ---
    public static function boot(): void
    {
        parent::boot();

        static::updating(fn() => throw new \LogicException('PointsTransaction is immutable.'));
        static::deleting(fn() => throw new \LogicException('PointsTransaction is immutable.'));
    }

    // --- Relationships ---
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function source(): MorphTo
    {
        return $this->morphTo();
    }
}
