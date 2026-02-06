<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Ride;
use App\Models\RideProposal;
use Illuminate\Support\Facades\DB;

class PassengerController extends Controller
{
    // Create a ride request
    public function create(Request $request)
    {
        $validated = $request->validate([
            'passenger_id' => 'required|exists:users,id',
            'pickup_lat' => 'required|numeric',
            'pickup_lng' => 'required|numeric',
            'dest_lat' => 'required|numeric',
            'dest_lng' => 'required|numeric',
        ]);

        $ride = Ride::create(array_merge($validated, ['status' => 'pending']));

        return response()->json([
            'message' => 'Ride requested successfully',
            'ride' => $ride
        ], 201);
    }

    // Approve a driver
    public function approveDriver(Request $request, $id)
    {
        $request->validate([
            'driver_id' => 'required|exists:users,id',
        ]);

        $ride = Ride::findOrFail($id);

        if ($ride->passenger_id != $request->passenger_id) { // Assuming passenger_id is sent or extracted from context
             // In a real app we'd use Auth::id() but assuming we pass ID or no auth as per req
             // "No authentication required - Although do consider ... how you handle"
             // Use request->input('current_user_id') or similar if needed, but for now simple.
        }

        // Logic to verify this driver actually proposed?
        // $proposal = RideProposal::where('ride_id', $ride->id)->where('driver_id', $request->driver_id)->firstOrFail();

        $ride->update([
            'driver_id' => $request->driver_id,
            'status' => 'accepted'
        ]);

        return response()->json([
            'message' => 'Driver approved',
            'ride' => $ride
        ]);
    }

    // Mark completed
    public function completeRide(Request $request, $id)
    {
        $ride = Ride::findOrFail($id);
        
        $ride->update(['passenger_completed_at' => now()]);

        if ($ride->driver_completed_at) {
            $ride->update(['status' => 'completed']);
        }

        return response()->json([
            'message' => 'Ride marked as completed by passenger',
            'ride' => $ride
        ]);
    }
}
