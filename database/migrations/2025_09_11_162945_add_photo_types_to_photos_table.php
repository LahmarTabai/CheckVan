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
        Schema::table('photos', function (Blueprint $table) {
            // Modifier la colonne type existante pour accepter les nouveaux types
            $table->enum('type', [
                'debut_kilometrage',
                'debut_carburant',
                'fin_kilometrage',
                'fin_carburant',
                'plaque',
                'interieur',
                'exterieur',
                'dommage',
                'avant',
                'apres',
                'autre'
            ])->default('autre')->change();

            // Description optionnelle de la photo
            $table->text('description')->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('photos', function (Blueprint $table) {
            // Revenir à la colonne type string simple
            $table->string('type')->change();
            $table->dropColumn('description');
        });
    }
};
