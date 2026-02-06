<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'passenger_completed_at' => 'datetime',
        'driver_completed_at' => 'datetime',
    ];

    public function passenger()
    {
        return $this->belongsTo(User::class, 'passenger_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function proposals()
    {
        return $this->hasMany(RideProposal::class);
    }
}
