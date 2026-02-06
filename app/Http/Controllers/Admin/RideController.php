<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Ride;

class RideController extends Controller
{
    // List all rides
    public function index()
    {
        $rides = Ride::with(['passenger', 'driver'])->orderBy('created_at', 'desc')->get();
        return view('admin.rides.index', compact('rides'));
    }

    // Show ride details
    public function show($id)
    {
        $ride = Ride::with(['passenger', 'driver', 'proposals.driver'])->findOrFail($id);
        return view('admin.rides.show', compact('ride'));
    }
}
