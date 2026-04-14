<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'establishment_id', 'level', 'field', 'academic_year'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function establishment(): BelongsTo { return $this->belongsTo(Establishment::class); }
}
