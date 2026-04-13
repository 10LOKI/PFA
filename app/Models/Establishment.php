<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Establishment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'address',
        'city',
        'phone',
        'email',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }
}
