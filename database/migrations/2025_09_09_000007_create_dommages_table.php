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
            $table->unsignedBigInteger('affectation_id');
            $table->unsignedBigInteger('chauffeur_id');
            $table->decimal('coord_x', 8, 2)->nullable(); // Coordonnée X sur l'image 2D/3D
            $table->decimal('coord_y', 8, 2)->nullable(); // Coordonnée Y sur l'image 2D/3D
            $table->decimal('coord_z', 8, 2)->nullable(); // Coordonnée Z pour 3D (optionnel)
            $table->enum('type', ['rayure', 'bosse', 'choc', 'autre'])->default('autre');
            $table->text('description')->nullable();
            $table->string('severite', 20)->default('mineur'); // mineur, moyen, majeur
            $table->string('photo_path')->nullable(); // Photo du dommage
            $table->boolean('reparé')->default(false);
            $table->timestamps();

            $table->foreign('affectation_id')->references('id')->on('affectations')->onDelete('cascade');
            $table->foreign('chauffeur_id')->references('user_id')->on('users')->onDelete('cascade');
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

