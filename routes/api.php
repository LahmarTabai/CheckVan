<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LocationController;
use Illuminate\Support\Facades\Auth;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Géolocalisation chauffeur (mobile)
    Route::post('/location', [LocationController::class, 'store']);

    // Enregistrement du token FCM (depuis le mobile)
    Route::post('/fcm-token', function (Request $request) {
        $request->validate(['token' => 'required|string']);
        $user = $request->user();
        $user->update(['fcm_token' => $request->token]);
        return ['status' => 'ok'];
    });
});

// Routes publiques pour les marques et modèles
Route::get('/marques', function () {
    $apiService = new \App\Services\VehiculeApiService();
    return response()->json($apiService->getMarques());
});

Route::get('/modeles/{marqueId}', function ($marqueId) {
    $apiService = new \App\Services\VehiculeApiService();
    return response()->json($apiService->getModeles($marqueId));
});
