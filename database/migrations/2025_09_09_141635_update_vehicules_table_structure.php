<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vehicules', function (Blueprint $table) {
            // Supprimer l'ancien champ photo
            $table->dropColumn('photo');

            // Ajouter les nouveaux champs
            $table->foreignId('marque_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('modele_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['location', 'propriete'])->default('propriete');
            $table->year('annee')->nullable();
            $table->string('couleur')->nullable();
            $table->integer('kilometrage')->nullable();
            $table->enum('statut', ['disponible', 'en_mission', 'en_maintenance', 'hors_service'])->default('disponible');
            $table->text('description')->nullable();
            $table->decimal('prix_achat', 10, 2)->nullable();
            $table->date('date_achat')->nullable();
            $table->string('numero_chassis')->nullable();
            $table->string('numero_moteur')->nullable();
            $table->date('derniere_revision')->nullable();
            $table->date('prochaine_revision')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicules', function (Blueprint $table) {
            // Supprimer les nouveaux champs
            $table->dropForeign(['marque_id']);
            $table->dropForeign(['modele_id']);
            $table->dropColumn([
                'marque_id', 'modele_id', 'type', 'annee', 'couleur',
                'kilometrage', 'statut', 'description', 'prix_achat',
                'date_achat', 'numero_chassis', 'numero_moteur',
                'derniere_revision', 'prochaine_revision'
            ]);

            // Remettre l'ancien champ photo
            $table->string('photo')->nullable();
        });
    }
};
