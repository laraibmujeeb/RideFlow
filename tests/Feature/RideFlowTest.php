<?php

namespace Tests\Feature;

use App\Models\Ride;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RideFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_ride_flow_lifecycle(): void
    {
        $passenger = User::factory()->create(['role' => 'passenger', 'email' => 'p@test.com']);
        $driver = User::factory()->create(['role' => 'driver', 'email' => 'd@test.com', 'latitude' => 40.71, 'longitude' => -74.00]);

        $response = $this->postJson('/api/passenger/rides', [
            'passenger_id' => $passenger->id,
            'pickup_lat' => 40.7128,
            'pickup_lng' => -74.0060,
            'dest_lat' => 40.7306,
            'dest_lng' => -73.9352,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $rideId = $response->json('ride.id');

        $this->postJson('/api/driver/location', [
            'driver_id' => $driver->id,
            'latitude' => 40.7120,
            'longitude' => -74.0050,
        ])->assertStatus(Response::HTTP_OK);

        $response = $this->getJson('/api/driver/rides/nearby?latitude=40.7120&longitude=-74.0050&radius=5');
        $response->assertStatus(Response::HTTP_OK);
        $this->assertCount(1, $response->json());
        $this->assertEquals($rideId, $response->json()[0]['id']);

        $this->postJson("/api/driver/rides/{$rideId}/request", [
            'driver_id' => $driver->id,
        ])->assertStatus(Response::HTTP_CREATED);

        $this->postJson("/api/passenger/rides/{$rideId}/approve-driver", [
            'passenger_id' => $passenger->id,
            'driver_id' => $driver->id,
        ])->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('rides', ['id' => $rideId, 'driver_id' => $driver->id, 'status' => 'accepted']);

        $this->postJson("/api/driver/rides/{$rideId}/complete")->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('rides', ['id' => $rideId, 'status' => 'accepted']);

        $this->postJson("/api/passenger/rides/{$rideId}/complete")->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('rides', ['id' => $rideId, 'status' => 'completed']);
    }
}
