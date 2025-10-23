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
            // Modifier l'enum pour inclure 'rejetee'
            $table->enum('status', ['en_attente', 'en_cours', 'terminée', 'rejetee'])->default('en_attente')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('taches', function (Blueprint $table) {
            // Revenir à l'enum original
            $table->enum('status', ['en_attente', 'en_cours', 'terminée'])->default('en_attente')->change();
        });
    }
};
