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
        Schema::create('modeles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marque_id')->constrained()->onDelete('cascade');
            $table->string('nom');
            $table->enum('type_vehicule', ['van', 'utilitaire', 'camion', 'fourgon', 'pickup'])->default('van');
            $table->year('annee_debut')->nullable();
            $table->year('annee_fin')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['marque_id', 'nom']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modeles');
    }
};
