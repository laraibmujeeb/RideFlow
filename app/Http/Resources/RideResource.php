<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RideResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'pickup' => [
                'latitude' => $this->pickup_lat,
                'longitude' => $this->pickup_lng,
            ],
            'destination' => [
                'latitude' => $this->dest_lat,
                'longitude' => $this->dest_lng,
            ],
            'passenger' => new UserResource($this->whenLoaded('passenger')),
            'driver' => new UserResource($this->whenLoaded('driver')),
            'proposals_count' => $this->whenCounted('proposals'),
            'distance' => $this->when(isset($this->distance), $this->distance),
            'passenger_completed_at' => $this->passenger_completed_at?->toISOString(),
            'driver_completed_at' => $this->driver_completed_at?->toISOString(),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}
