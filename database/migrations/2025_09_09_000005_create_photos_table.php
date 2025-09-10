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
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tache_id')->nullable();
            $table->unsignedBigInteger('affectation_id')->nullable();
            $table->string('type'); // 'avant', 'après', 'dommage'
            $table->string('path');
            $table->timestamps();

            $table->foreign('tache_id')->references('id')->on('taches')->onDelete('cascade');
            $table->foreign('affectation_id')->references('id')->on('affectations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};

