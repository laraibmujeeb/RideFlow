<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RideProposalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ride_id' => $this->ride_id,
            'driver' => new UserResource($this->whenLoaded('driver')),
            'created_at' => $this->created_at->toISOString(),
        ];
    }
}
