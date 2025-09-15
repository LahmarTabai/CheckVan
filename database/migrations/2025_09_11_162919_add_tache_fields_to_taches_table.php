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
        Schema::table('taches', function (Blueprint $table) {
            // Champs kilométrage
            $table->integer('debut_kilometrage')->nullable()->after('start_longitude');
            $table->integer('fin_kilometrage')->nullable()->after('end_longitude');

            // Champs carburant
            $table->decimal('debut_carburant', 5, 2)->nullable()->after('debut_kilometrage');
            $table->decimal('fin_carburant', 5, 2)->nullable()->after('fin_kilometrage');

            // Champs pour les règles métier
            $table->text('description')->nullable()->after('fin_carburant');
            $table->enum('type_tache', ['maintenance', 'livraison', 'inspection', 'autre'])->default('autre')->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('taches', function (Blueprint $table) {
            $table->dropColumn([
                'debut_kilometrage',
                'fin_kilometrage',
                'debut_carburant',
                'fin_carburant',
                'description',
                'type_tache'
            ]);
        });
    }
};
