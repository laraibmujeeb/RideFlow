<?php

namespace App\Http\Controllers\Api;

use App\Enums\RideStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetNearbyRidesRequest;
use App\Http\Requests\RequestRideRequest;
use App\Http\Requests\UpdateLocationRequest;
use App\Http\Resources\RideProposalResource;
use App\Http\Resources\RideResource;
use App\Models\Ride;
use App\Models\RideProposal;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DriverController extends Controller
{
    public function updateLocation(UpdateLocationRequest $request): JsonResponse
    {
        $driver = User::findOrFail($request->driver_id);

        if ($driver->role !== 'driver') {
            return response()->json([
                'message' => 'User is not a driver',
            ], Response::HTTP_FORBIDDEN);
        }

        $driver->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json([
            'message' => 'Location updated',
        ], Response::HTTP_OK);
    }

    public function getNearbyRides(GetNearbyRidesRequest $request): JsonResponse
    {
        $lat = $request->latitude;
        $lng = $request->longitude;
        $radius = $request->radius ?? 10;

        $rides = Ride::where('status', RideStatus::PENDING)->get();

        $rides = $rides->map(function ($ride) use ($lat, $lng) {
            $theta = $lng - $ride->pickup_lng;
            $dist = sin(deg2rad($lat)) * sin(deg2rad($ride->pickup_lat)) + cos(deg2rad($lat)) * cos(deg2rad($ride->pickup_lat)) * cos(deg2rad($theta));
            $dist = acos(min(1, max(-1, $dist)));
            $dist = rad2deg($dist);
            $km = $dist * 60 * 1.1515 * 1.609344;

            $ride->distance = round($km, 2);
            return $ride;
        })->filter(function ($ride) use ($radius) {
            return $ride->distance <= $radius;
        })->sortBy('distance')->values();

        return response()->json(RideResource::collection($rides), Response::HTTP_OK);
    }

    public function requestRide(RequestRideRequest $request, Ride $ride): JsonResponse
    {
        if ($ride->status !== RideStatus::PENDING) {
            return response()->json([
                'message' => 'Ride is not available',
            ], Response::HTTP_BAD_REQUEST);
        }

        $existing = RideProposal::where('ride_id', $ride->id)
            ->where('driver_id', $request->driver_id)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Ride already requested',
            ], Response::HTTP_OK);
        }

        $proposal = RideProposal::create([
            'ride_id' => $ride->id,
            'driver_id' => $request->driver_id,
        ]);

        return response()->json([
            'message' => 'Ride requested successfully',
            'proposal' => new RideProposalResource($proposal),
        ], Response::HTTP_CREATED);
    }

    public function completeRide(Request $request, Ride $ride): JsonResponse
    {
        $ride->update(['driver_completed_at' => now()]);

        if ($ride->passenger_completed_at) {
            $ride->update(['status' => RideStatus::COMPLETED]);
        }

        return response()->json([
            'message' => 'Ride marked as completed by driver',
            'ride' => new RideResource($ride->fresh()),
        ], Response::HTTP_OK);
    }
}
