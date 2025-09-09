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
        Schema::create('dommages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affectation_id')->constrained()->onDelete('cascade');
            $table->foreignId('chauffeur_id')->constrained('users')->onDelete('cascade');
            $table->decimal('coord_x', 8, 2)->nullable(); // Coordonnée X sur l'image 2D/3D
            $table->decimal('coord_y', 8, 2)->nullable(); // Coordonnée Y sur l'image 2D/3D
            $table->decimal('coord_z', 8, 2)->nullable(); // Coordonnée Z pour 3D (optionnel)
            $table->enum('type', ['rayure', 'bosse', 'choc', 'autre'])->default('autre');
            $table->text('description')->nullable();
            $table->string('severite', 20)->default('mineur'); // mineur, moyen, majeur
            $table->string('photo_path')->nullable(); // Photo du dommage
            $table->boolean('reparé')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dommages');
    }
};
