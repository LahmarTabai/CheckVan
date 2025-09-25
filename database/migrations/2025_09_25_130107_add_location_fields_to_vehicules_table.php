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
            $table->decimal('prix_location_jour', 10, 2)->nullable()->after('date_achat');
            $table->date('date_location')->nullable()->after('prix_location_jour');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicules', function (Blueprint $table) {
            $table->dropColumn(['prix_location_jour', 'date_location']);
        });
    }
};
