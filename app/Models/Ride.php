<?php

namespace App\Models;

use App\Enums\RideStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ride extends Model
{
    protected $fillable = [
        'passenger_id',
        'driver_id',
        'pickup_lat',
        'pickup_lng',
        'dest_lat',
        'dest_lng',
        'status',
        'passenger_completed_at',
        'driver_completed_at',
    ];

    protected $casts = [
        'pickup_lat' => 'float',
        'pickup_lng' => 'float',
        'dest_lat' => 'float',
        'dest_lng' => 'float',
        'status' => RideStatus::class,
        'passenger_completed_at' => 'datetime',
        'driver_completed_at' => 'datetime',
    ];

    public function passenger(): BelongsTo
    {
        return $this->belongsTo(User::class, 'passenger_id');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function proposals(): HasMany
    {
        return $this->hasMany(RideProposal::class);
    }
}
