<?php

namespace App\Http\Controllers\Api;

use App\Enums\RideStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApproveDriverRequest;
use App\Http\Requests\CreateRideRequest;
use App\Http\Resources\RideResource;
use App\Models\Ride;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PassengerController extends Controller
{
    public function create(CreateRideRequest $request): JsonResponse
    {
        $ride = Ride::create(array_merge(
            $request->validated(),
            ['status' => RideStatus::PENDING]
        ));

        return response()->json([
            'message' => 'Ride requested successfully',
            'ride' => new RideResource($ride),
        ], Response::HTTP_CREATED);
    }

    public function approveDriver(ApproveDriverRequest $request, Ride $ride): JsonResponse
    {
        if ($ride->passenger_id != $request->passenger_id) {
            return response()->json([
                'message' => 'Unauthorized',
            ], Response::HTTP_FORBIDDEN);
        }

        $ride->update([
            'driver_id' => $request->driver_id,
            'status' => RideStatus::ACCEPTED,
        ]);

        return response()->json([
            'message' => 'Driver approved',
            'ride' => new RideResource($ride->fresh()),
        ], Response::HTTP_OK);
    }

    public function completeRide(Request $request, Ride $ride): JsonResponse
    {
        $ride->update(['passenger_completed_at' => now()]);

        if ($ride->driver_completed_at) {
            $ride->update(['status' => RideStatus::COMPLETED]);
        }

        return response()->json([
            'message' => 'Ride marked as completed by passenger',
            'ride' => new RideResource($ride->fresh()),
        ], Response::HTTP_OK);
    }
}
