<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Ride;
use App\Models\RideProposal;
use App\Models\User;

class DriverController extends Controller
{
    // Update driver location
    public function updateLocation(Request $request)
    {
        $validated = $request->validate([
            'driver_id' => 'required|exists:users,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $driver = User::findOrFail($validated['driver_id']);
        
        // Ensure user is a driver
        if ($driver->role !== 'driver') {
            return response()->json(['message' => 'User is not a driver'], 403);
        }

        $driver->update([
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
        ]);

        return response()->json(['message' => 'Location updated']);
    }

    // Fetch nearby pending ride requests
    public function getNearbyRides(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'nullable|numeric', // in km
        ]);

        $lat = $request->latitude;
        $lng = $request->longitude;
        $radius = $request->radius ?? 10; // default 10km

        // Fetch all pending rides (in production, we'd use bounding box to limit results before fetching)
        $rides = Ride::where('status', 'pending')->get();
        
        $rides = $rides->map(function ($ride) use ($lat, $lng) {
            $theta = $lng - $ride->pickup_lng;
            $dist = sin(deg2rad($lat)) * sin(deg2rad($ride->pickup_lat)) +  cos(deg2rad($lat)) * cos(deg2rad($ride->pickup_lat)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $km = $miles * 1.609344;
            
            $ride->distance = round($km, 2);
            return $ride;
        })->filter(function ($ride) use ($radius) {
            return $ride->distance <= $radius;
        })->sortBy('distance')->values();

        return response()->json($rides);
    }

    // Request/claim a ride
    public function requestRide(Request $request, $id)
    {
        $request->validate([
            'driver_id' => 'required|exists:users,id',
        ]);

        $ride = Ride::findOrFail($id);

        if ($ride->status !== 'pending') {
            return response()->json(['message' => 'Ride is not available'], 400);
        }

        // Create a proposal
        // Check if already requested
        $existing = RideProposal::where('ride_id', $id)
            ->where('driver_id', $request->driver_id)
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Ride already requested'], 200);
        }

        $proposal = RideProposal::create([
            'ride_id' => $id,
            'driver_id' => $request->driver_id,
        ]);

        return response()->json([
            'message' => 'Ride requested successfully',
            'proposal' => $proposal
        ]);
    }

    // Mark ride as completed
    public function completeRide(Request $request, $id)
    {
        $ride = Ride::findOrFail($id);
        
        $ride->update(['driver_completed_at' => now()]);

        if ($ride->passenger_completed_at) {
            $ride->update(['status' => 'completed']);
        }

        return response()->json([
            'message' => 'Ride marked as completed by driver',
            'ride' => $ride
        ]);
    }
}
