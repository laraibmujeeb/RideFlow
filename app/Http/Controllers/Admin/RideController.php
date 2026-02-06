<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ride;
use Illuminate\Contracts\View\View;

class RideController extends Controller
{
    public function index(): View
    {
        $rides = Ride::with(['passenger', 'driver'])->orderBy('created_at', 'desc')->get();

        return view('admin.rides.index', compact('rides'));
    }

    public function show(Ride $ride): View
    {
        $ride->load(['passenger', 'driver', 'proposals.driver']);

        return view('admin.rides.show', compact('ride'));
    }
}
