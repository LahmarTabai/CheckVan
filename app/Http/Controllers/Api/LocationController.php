<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'recorded_at' => 'nullable|date',
        ]);

        $user = $request->user();
        abort_unless($user && $user->role === 'chauffeur', 403);

        $location = Location::create([
            'chauffeur_id' => $user->id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'recorded_at' => $request->input('recorded_at', now()),
        ]);

        return response()->json(['status' => 'ok', 'id' => $location->id]);
    }
}



