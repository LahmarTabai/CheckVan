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
        Schema::create('vehicule_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicule_id')->constrained()->onDelete('cascade');
            $table->string('chemin');
            $table->string('nom_fichier');
            $table->string('extension');
            $table->integer('taille')->nullable(); // en bytes
            $table->integer('ordre')->default(1);
            $table->string('type')->default('exterieur'); // exterieur, interieur, moteur, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicule_photos');
    }
};
