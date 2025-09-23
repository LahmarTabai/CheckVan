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
        $request->validate([
            'token' => 'required|string|min:10',
            'device_type' => 'nullable|string|in:android,ios,web'
        ]);
        
        $user = $request->user();
        $user->update([
            'fcm_token' => $request->token,
            'device_type' => $request->device_type ?? 'unknown'
        ]);
        
        // Log pour débogage
        \Log::info('FCM Token enregistré', [
            'user_id' => $user->user_id,
            'token_preview' => substr($request->token, 0, 20) . '...',
            'device_type' => $request->device_type ?? 'unknown'
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Token FCM enregistré avec succès',
            'user' => $user->nom . ' ' . $user->prenom
        ]);
    });

    // Test d'envoi de notification (pour débogage)
    Route::post('/fcm-test', function (Request $request) {
        $request->validate(['message' => 'nullable|string']);
        
        $user = $request->user();
        if (!$user->fcm_token) {
            return response()->json(['error' => 'Aucun token FCM enregistré'], 400);
        }
        
        $fcmService = app(\App\Services\FcmService::class);
        $message = $request->message ?? 'Test de notification depuis l\'API';
        
        $success = $fcmService->sendToToken(
            $user->fcm_token,
            'Test API',
            $message,
            ['type' => 'api_test', 'timestamp' => now()->toISOString()]
        );
        
        return response()->json([
            'status' => $success ? 'success' : 'error',
            'message' => $success ? 'Notification envoyée' : 'Échec de l\'envoi',
            'sent_to' => substr($user->fcm_token, 0, 20) . '...'
        ]);
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
