<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'passenger_id' => 'required|exists:users,id',
            'pickup_lat' => 'required|numeric|between:-90,90',
            'pickup_lng' => 'required|numeric|between:-180,180',
            'dest_lat' => 'required|numeric|between:-90,90',
            'dest_lng' => 'required|numeric|between:-180,180',
        ];
    }

    public function messages(): array
    {
        return [
            'pickup_lat.between' => 'Pickup latitude must be between -90 and 90.',
            'pickup_lng.between' => 'Pickup longitude must be between -180 and 180.',
            'dest_lat.between' => 'Destination latitude must be between -90 and 90.',
            'dest_lng.between' => 'Destination longitude must be between -180 and 180.',
        ];
    }
}
