<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TravelRequest extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'destination',
        'departure_date',
        'return_date',
    ];

    protected $casts = [
        'departure_date' => 'datetime',
        'return_date'    => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
