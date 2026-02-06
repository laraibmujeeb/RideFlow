<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetNearbyRidesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'latitude.between' => 'Latitude must be between -90 and 90.',
            'longitude.between' => 'Longitude must be between -180 and 180.',
            'radius.min' => 'Radius must be a positive number.',
        ];
    }
}
