<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('photos', function (Blueprint $table) {
            // Pour modifier la nullabilité, on doit d'abord supprimer la contrainte, puis la recréer
            try {
                $table->dropForeign(['tache_id']);
            } catch (\Throwable $e) {
                // SQLite ou FK déjà absente : ignorer
            }

            // Recréer la colonne nullable si nécessaire (certaines plateformes exigent column modification)
            if (Schema::hasColumn('photos', 'tache_id')) {
                // Laravel gère le rebuild pour SQLite
                $table->unsignedBigInteger('tache_id')->nullable()->change();
            }

            // Ré-attacher la contrainte si la table taches existe
            try {
                $table->foreign('tache_id')->references('id')->on('taches')->onDelete('cascade');
            } catch (\Throwable $e) {
                // ignorer si non supporté
            }
        });
    }

    public function down(): void
    {
        Schema::table('photos', function (Blueprint $table) {
            try {
                $table->dropForeign(['tache_id']);
            } catch (\Throwable $e) {
                // ignorer
            }

            if (Schema::hasColumn('photos', 'tache_id')) {
                $table->unsignedBigInteger('tache_id')->nullable(false)->change();
            }

            try {
                $table->foreign('tache_id')->references('id')->on('taches')->onDelete('cascade');
            } catch (\Throwable $e) {
                // ignorer
            }
        });
    }
};



