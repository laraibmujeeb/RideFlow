<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $passenger = \App\Models\User::create([
            'name' => 'John Doe',
            'email' => 'passenger@rideflow.com',
            'password' => bcrypt('password'),
            'role' => 'passenger'
        ]);

        $driver = \App\Models\User::create([
            'name' => 'Alex Smith',
            'email' => 'driver@rideflow.com',
            'password' => bcrypt('password'),
            'role' => 'driver',
            'latitude' => 40.7128,
            'longitude' => -74.0060
        ]);

        $anotherDriver = \App\Models\User::create([
            'name' => 'Sarah Connor',
            'email' => 'sarah@rideflow.com',
            'password' => bcrypt('password'),
            'role' => 'driver',
            'latitude' => 40.7306,
            'longitude' => -73.9352
        ]);

        $ride = \App\Models\Ride::create([
            'passenger_id' => $passenger->id,
            'driver_id' => $driver->id,
            'pickup_lat' => 40.7128,
            'pickup_lng' => -74.0060,
            'dest_lat' => 40.7306,
            'dest_lng' => -73.9352,
            'status' => 'accepted'
        ]);

        \App\Models\RideProposal::create([
            'ride_id' => $ride->id,
            'driver_id' => $driver->id
        ]);
        
        \App\Models\RideProposal::create([
            'ride_id' => $ride->id,
            'driver_id' => $anotherDriver->id
        ]);

        // Completed ride
        $ride2 = \App\Models\Ride::create([
            'passenger_id' => $passenger->id,
            'driver_id' => $anotherDriver->id,
            'pickup_lat' => 34.0522,
            'pickup_lng' => -118.2437,
            'dest_lat' => 34.0522,
            'dest_lng' => -118.4437,
            'status' => 'completed',
            'passenger_completed_at' => now(),
            'driver_completed_at' => now()
        ]);

        // Pending ride (Available for pickup)
        \App\Models\Ride::create([
            'passenger_id' => $passenger->id,
            'pickup_lat' => 51.5074,
            'pickup_lng' => -0.1278,
            'dest_lat' => 48.8566,
            'dest_lng' => 2.3522,
            'status' => 'pending'
        ]);
    }
}
