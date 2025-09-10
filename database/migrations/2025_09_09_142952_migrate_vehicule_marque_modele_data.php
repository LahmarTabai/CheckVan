<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Vehicule;
use App\Models\Marque;
use App\Models\Modele;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrer les données existantes de marque/modele vers marque_id/modele_id
        $vehicules = Vehicule::whereNotNull('marque')->whereNotNull('modele')->get();

        foreach ($vehicules as $vehicule) {
            // Chercher ou créer la marque
            $marque = Marque::firstOrCreate(
                ['nom' => $vehicule->marque],
                ['pays' => 'France', 'logo' => null]
            );

            // Chercher ou créer le modèle
            $modele = Modele::firstOrCreate(
                [
                    'marque_id' => $marque->id,
                    'nom' => $vehicule->modele
                ],
                [
                    'type_vehicule' => 'utilitaire',
                    'annee_debut' => 2000,
                    'annee_fin' => null
                ]
            );

            // Mettre à jour le véhicule
            $vehicule->update([
                'marque_id' => $marque->id,
                'modele_id' => $modele->id
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remettre les anciennes valeurs
        $vehicules = Vehicule::whereNotNull('marque_id')->whereNotNull('modele_id')->get();

        foreach ($vehicules as $vehicule) {
            $marque = Marque::find($vehicule->marque_id);
            $modele = Modele::find($vehicule->modele_id);

            if ($marque && $modele) {
                $vehicule->update([
                    'marque' => $marque->nom,
                    'modele' => $modele->nom
                ]);
            }
        }
    }
};
