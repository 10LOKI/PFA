<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partner extends Model
{
    use HasFactory;

    protected $table = 'parteners';

    protected $fillable = [
        'user_id', 'company_name', 'logo', 'bio', 'website',
        'sector', 'rc_number', 'rc_document', 'kyc_status', 'is_certified',
    ];

    protected function casts(): array
    {
        return ['is_certified' => 'boolean'];
    }

    public function isApproved(): bool { return $this->kyc_status === 'approved'; }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function events(): HasMany { return $this->hasMany(Event::class, 'partner_id', 'user_id'); }
}
