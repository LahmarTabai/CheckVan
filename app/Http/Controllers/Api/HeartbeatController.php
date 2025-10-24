<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OnlineStatusService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class HeartbeatController extends Controller
{
    protected $onlineStatusService;

    public function __construct(OnlineStatusService $onlineStatusService)
    {
        $this->onlineStatusService = $onlineStatusService;
    }

    /**
     * Endpoint pour le heartbeat du mobile
     * POST /api/heartbeat
     */
    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Non authentifié'
            ], 401);
        }

        // Marquer l'utilisateur comme online
        $this->onlineStatusService->markAsOnline($user);

        // Si position GPS fournie, la sauvegarder aussi
        if ($request->has(['latitude', 'longitude'])) {
            $this->saveLocation($user, $request);
        }

        return response()->json([
            'success' => true,
            'message' => 'Heartbeat reçu',
            'data' => [
                'is_online' => true,
                'last_heartbeat' => $user->fresh()->last_heartbeat,
                'server_time' => now()->toIso8601String(),
            ]
        ]);
    }

    /**
     * Sauvegarder la position GPS (si fournie avec le heartbeat)
     */
    protected function saveLocation($user, Request $request): void
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy' => 'nullable|numeric',
            'altitude' => 'nullable|numeric',
            'speed' => 'nullable|numeric',
            'heading' => 'nullable|numeric',
        ]);

        \App\Models\Location::create([
            'chauffeur_id' => $user->user_id,
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'accuracy' => $validated['accuracy'] ?? null,
            'altitude' => $validated['altitude'] ?? null,
            'speed' => $validated['speed'] ?? null,
            'heading' => $validated['heading'] ?? null,
            'recorded_at' => now(),
        ]);
    }

    /**
     * Endpoint pour marquer explicitement comme offline (logout)
     * POST /api/heartbeat/offline
     */
    public function offline(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Non authentifié'
            ], 401);
        }

        $this->onlineStatusService->markAsOffline($user);

        return response()->json([
            'success' => true,
            'message' => 'Marqué comme offline',
        ]);
    }

    /**
     * Vérifier le statut online (pour debug)
     * GET /api/heartbeat/status
     */
    public function status(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Non authentifié'
            ], 401);
        }

        $isOnline = $this->onlineStatusService->isUserOnline($user);

        return response()->json([
            'success' => true,
            'data' => [
                'user_id' => $user->user_id,
                'is_online' => $isOnline,
                'is_online_db' => $user->is_online,
                'last_heartbeat' => $user->last_heartbeat,
                'last_seen' => $user->last_seen,
                'server_time' => now()->toIso8601String(),
            ]
        ]);
    }
}

