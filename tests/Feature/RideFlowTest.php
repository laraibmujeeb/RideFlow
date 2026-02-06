<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Ride;
use App\Models\RideProposal;

class RideFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_ride_flow_lifecycle()
    {
        // 1. Create Users
        $passenger = User::factory()->create(['role' => 'passenger', 'email' => 'p@test.com']);
        $driver = User::factory()->create(['role' => 'driver', 'email' => 'd@test.com', 'latitude' => 40.71, 'longitude' => -74.00]);

        // 2. Passenger Requests Ride
        $response = $this->postJson('/api/passenger/rides', [
            'passenger_id' => $passenger->id,
            'pickup_lat' => 40.7128,
            'pickup_lng' => -74.0060,
            'dest_lat' => 40.7306,
            'dest_lng' => -73.9352,
        ]);

        $response->assertStatus(201);
        $rideId = $response->json('ride.id');

        // 3. Driver Updates Location (Near Pickup)
        $this->postJson('/api/driver/location', [
            'driver_id' => $driver->id,
            'latitude' => 40.7120, // Very close
            'longitude' => -74.0050,
        ])->assertStatus(200);

        // 4. Driver Checks Nearby Rides
        $response = $this->getJson('/api/driver/rides/nearby?latitude=40.7120&longitude=-74.0050&radius=5');
        $response->assertStatus(200);
        $this->assertCount(1, $response->json());
        $this->assertEquals($rideId, $response->json()[0]['id']);

        // 5. Driver Requests Ride
        $this->postJson("/api/driver/rides/{$rideId}/request", [
            'driver_id' => $driver->id,
        ])->assertStatus(200);

        // 6. Passenger Approves Driver
        $this->postJson("/api/passenger/rides/{$rideId}/approve-driver", [
            'passenger_id' => $passenger->id,
            'driver_id' => $driver->id,
        ])->assertStatus(200);

        $this->assertDatabaseHas('rides', ['id' => $rideId, 'driver_id' => $driver->id, 'status' => 'accepted']);

        // 7. Driver Completes
        $this->postJson("/api/driver/rides/{$rideId}/complete")->assertStatus(200);
        
        // Status should still be accepted or maybe 'in_progress' logic wasn't explicitly added but completion logic checks both.
        // Wait, requirements didn't specify intermediate 'in_progress' transition trigger, but logic says "A ride is fully completed only when both... mark it completed".
        // So checking if status is NOT completed yet.
        $this->assertDatabaseHas('rides', ['id' => $rideId, 'status' => 'accepted']); // or whatever it was before

        // 8. Passenger Completes
        $this->postJson("/api/passenger/rides/{$rideId}/complete")->assertStatus(200);

        // NOW status should be completed
        $this->assertDatabaseHas('rides', ['id' => $rideId, 'status' => 'completed']);
    }
}
