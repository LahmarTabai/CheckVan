<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\Marque;
use App\Models\Modele;

class VehiculeApiService
{
    private const CACHE_DURATION = 86400; // 24 heures
    private const API_TIMEOUT = 10; // 10 secondes

    /**
     * Récupère toutes les marques depuis l'API ou le cache
     */
    public function getMarques(): array
    {
        return Cache::remember('api_marques', self::CACHE_DURATION, function () {
            try {
                // Essayer l'API NHTSA (gratuite, stable)
                $marques = $this->fetchMarquesFromNhtsa();

                if (empty($marques)) {
                    // Fallback sur l'API CarQuery
                    $marques = $this->fetchMarquesFromCarQuery();
                }

                if (empty($marques)) {
                    // Fallback sur les seeders
                    $marques = $this->getMarquesFromSeeders();
                }

                return $marques;

            } catch (\Exception $e) {
                Log::error('Erreur API marques: ' . $e->getMessage());
                return $this->getMarquesFromSeeders();
            }
        });
    }

    /**
     * Récupère les modèles d'une marque depuis l'API ou le cache
     */
    public function getModeles(int $marqueId): array
    {
        $cacheKey = "api_modeles_{$marqueId}";

        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($marqueId) {
            try {
                $marque = Marque::find($marqueId);
                if (!$marque) {
                    return [];
                }

                // Essayer l'API NHTSA
                $modeles = $this->fetchModelesFromNhtsa($marque->nom);

                if (empty($modeles)) {
                    // Fallback sur l'API CarQuery
                    $modeles = $this->fetchModelesFromCarQuery($marque->nom);
                }

                if (empty($modeles)) {
                    // Fallback sur les seeders
                    $modeles = $this->getModelesFromSeeders($marqueId);
                }

                return $modeles;

            } catch (\Exception $e) {
                Log::error("Erreur API modèles pour marque {$marqueId}: " . $e->getMessage());
                return $this->getModelesFromSeeders($marqueId);
            }
        });
    }

    /**
     * Récupère les marques depuis l'API NHTSA
     */
    private function fetchMarquesFromNhtsa(): array
    {
        $response = Http::timeout(self::API_TIMEOUT)
            ->withOptions(['verify' => false])
            ->get('https://vpic.nhtsa.dot.gov/api/vehicles/getallmakes?format=json');

        if ($response->successful()) {
            $data = $response->json();
            $marques = [];

            foreach ($data['Results'] as $item) {
                $marques[] = [
                    'nom' => $item['Make_Name'],
                    'pays' => 'International',
                    'logo' => null,
                    'is_active' => true
                ];
            }

            return $marques;
        }

        return [];
    }

    /**
     * Récupère les modèles depuis l'API NHTSA
     */
    private function fetchModelesFromNhtsa(string $marqueNom): array
    {
        $response = Http::timeout(self::API_TIMEOUT)
            ->withOptions(['verify' => false])
            ->get("https://vpic.nhtsa.dot.gov/api/vehicles/getmodelsformake/{$marqueNom}?format=json");

        if ($response->successful()) {
            $data = $response->json();
            $modeles = [];

            foreach ($data['Results'] as $item) {
                $modeles[] = [
                    'nom' => $item['Model_Name'],
                    'type_vehicule' => 'utilitaire',
                    'annee_debut' => null,
                    'annee_fin' => null,
                    'is_active' => true
                ];
            }

            return $modeles;
        }

        return [];
    }

    /**
     * Récupère les marques depuis l'API CarQuery (fallback)
     */
    private function fetchMarquesFromCarQuery(): array
    {
        $response = Http::timeout(self::API_TIMEOUT)
            ->withOptions(['verify' => false])
            ->get('https://www.carqueryapi.com/api/0.3/?cmd=getMakes');

        if ($response->successful()) {
            $data = $response->json();
            $marques = [];

            foreach ($data['Makes'] as $item) {
                $marques[] = [
                    'nom' => $item['make_display'],
                    'pays' => $item['make_country'] ?? 'International',
                    'logo' => null,
                    'is_active' => true
                ];
            }

            return $marques;
        }

        return [];
    }

    /**
     * Récupère les modèles depuis l'API CarQuery (fallback)
     */
    private function fetchModelesFromCarQuery(string $marqueNom): array
    {
        $response = Http::timeout(self::API_TIMEOUT)
            ->withOptions(['verify' => false])
            ->get("https://www.carqueryapi.com/api/0.3/?cmd=getModels&make={$marqueNom}");

        if ($response->successful()) {
            $data = $response->json();
            $modeles = [];

            foreach ($data['Models'] as $item) {
                $modeles[] = [
                    'nom' => $item['model_name'],
                    'type_vehicule' => $item['model_body'] ?? 'utilitaire',
                    'annee_debut' => $item['model_year_start'] ?? null,
                    'annee_fin' => $item['model_year_end'] ?? null,
                    'is_active' => true
                ];
            }

            return $modeles;
        }

        return [];
    }

    /**
     * Récupère les marques depuis les seeders (fallback final)
     */
    private function getMarquesFromSeeders(): array
    {
        return Marque::where('is_active', true)
            ->orderBy('nom')
            ->get()
            ->toArray();
    }

    /**
     * Récupère les modèles depuis les seeders (fallback final)
     */
    private function getModelesFromSeeders(int $marqueId): array
    {
        return Modele::where('marque_id', $marqueId)
            ->where('is_active', true)
            ->orderBy('nom')
            ->get()
            ->toArray();
    }

    /**
     * Synchronise les marques de l'API avec la base de données
     */
    public function syncMarquesFromApi(): int
    {
        $marquesApi = $this->getMarques();
        $count = 0;

        foreach ($marquesApi as $marqueData) {
            $marque = Marque::firstOrCreate(
                ['nom' => $marqueData['nom']],
                $marqueData
            );
            $count++;
        }

        return $count;
    }

    /**
     * Synchronise les modèles d'une marque depuis l'API
     */
    public function syncModelesFromApi(int $marqueId): int
    {
        $modelesApi = $this->getModeles($marqueId);
        $count = 0;

        foreach ($modelesApi as $modeleData) {
            $modeleData['marque_id'] = $marqueId;
            $modele = Modele::firstOrCreate(
                [
                    'marque_id' => $marqueId,
                    'nom' => $modeleData['nom']
                ],
                $modeleData
            );
            $count++;
        }

        return $count;
    }

    /**
     * Vide le cache
     */
    public function clearCache(): void
    {
        Cache::forget('api_marques');
        // Vider tous les caches de modèles
        $marques = Marque::all();
        foreach ($marques as $marque) {
            Cache::forget("api_modeles_{$marque->id}");
        }
    }
}
