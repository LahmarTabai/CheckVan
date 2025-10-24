<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OnlineStatusService
{
    /**
     * Durée en secondes avant qu'un utilisateur soit considéré offline
     * 30 secondes = 2 heartbeats manqués (heartbeat toutes les 15s)
     */
    const ONLINE_THRESHOLD = 30;

    /**
     * Marquer un utilisateur comme online (appelé lors du heartbeat)
     */
    public function markAsOnline(User $user): void
    {
        $now = Carbon::now();

        $user->update([
            'is_online' => true,
            'last_seen' => $now,
            'last_heartbeat' => $now,
        ]);

        // Cache pour éviter trop de requêtes DB
        Cache::put("user_online_{$user->user_id}", true, now()->addMinutes(2));

        Log::info("User {$user->user_id} marked as online", [
            'user_id' => $user->user_id,
            'role' => $user->role,
            'last_heartbeat' => $now->toDateTimeString()
        ]);
    }

    /**
     * Marquer un utilisateur comme offline
     */
    public function markAsOffline(User $user): void
    {
        $user->update([
            'is_online' => false,
        ]);

        Cache::forget("user_online_{$user->user_id}");

        Log::info("User {$user->user_id} marked as offline", [
            'user_id' => $user->user_id,
            'role' => $user->role,
        ]);
    }

    /**
     * Vérifier si un utilisateur est online (basé sur last_heartbeat)
     */
    public function isUserOnline(User $user): bool
    {
        // ✅ CORRECTION : Vérifier d'abord is_online en base (prioritaire)
        if (!$user->is_online) {
            Cache::forget("user_online_{$user->user_id}");
            return false;
        }

        // Check cache (réduit à 30s pour être réactif)
        if (Cache::has("user_online_{$user->user_id}")) {
            return Cache::get("user_online_{$user->user_id}");
        }

        if (!$user->last_heartbeat) {
            return false;
        }

        $secondsSinceLastHeartbeat = Carbon::now()->diffInSeconds($user->last_heartbeat);
        $isOnline = $secondsSinceLastHeartbeat <= self::ONLINE_THRESHOLD;

        // Update cache (30 secondes max au lieu de 2 minutes)
        Cache::put("user_online_{$user->user_id}", $isOnline, now()->addSeconds(30));

        return $isOnline;
    }

    /**
     * Nettoyer les statuts online périmés (appelé par le scheduler)
     */
    public function cleanupStaleStatuses(): int
    {
        $threshold = Carbon::now()->subSeconds(self::ONLINE_THRESHOLD);

        $updated = User::where('is_online', true)
            ->where(function($query) use ($threshold) {
                $query->whereNull('last_heartbeat')
                      ->orWhere('last_heartbeat', '<', $threshold);
            })
            ->update([
                'is_online' => false
            ]);

        if ($updated > 0) {
            Log::info("Cleaned up {$updated} stale online statuses");
        }

        return $updated;
    }

    /**
     * Obtenir tous les utilisateurs online (pour admin)
     */
    public function getOnlineUsers(int $adminId, string $role = null): \Illuminate\Support\Collection
    {
        $query = User::where('is_online', true)
            ->where('last_heartbeat', '>=', Carbon::now()->subSeconds(self::ONLINE_THRESHOLD));

        if ($role) {
            $query->where('role', $role);
        }

        // Filtrer par admin
        if ($role === 'chauffeur') {
            $query->where('admin_id', $adminId);
        } elseif ($role === 'admin') {
            $query->where('user_id', $adminId);
        } else {
            // Tous les users de cet admin
            $query->where(function($q) use ($adminId) {
                $q->where('admin_id', $adminId)
                  ->orWhere('user_id', $adminId);
            });
        }

        return $query->get();
    }

    /**
     * Obtenir les statistiques online/offline pour un admin
     */
    public function getOnlineStats(int $adminId): array
    {
        $chauffeurs = User::where('role', 'chauffeur')
            ->where('admin_id', $adminId)
            ->get();

        $online = $chauffeurs->filter(fn($user) => $this->isUserOnline($user))->count();
        $offline = $chauffeurs->count() - $online;

        return [
            'total' => $chauffeurs->count(),
            'online' => $online,
            'offline' => $offline,
            'online_percentage' => $chauffeurs->count() > 0
                ? round(($online / $chauffeurs->count()) * 100, 1)
                : 0,
        ];
    }

    /**
     * Mettre à jour last_seen automatiquement (appelé par middleware)
     */
    public function updateLastSeen(User $user): void
    {
        // Mettre à jour seulement si dernière MAJ > 1 minute (éviter trop d'écritures)
        $shouldUpdate = !$user->last_seen
            || Carbon::now()->diffInMinutes($user->last_seen) >= 1;

        if ($shouldUpdate) {
            $user->update([
                'last_seen' => Carbon::now(),
            ]);
        }
    }
}

