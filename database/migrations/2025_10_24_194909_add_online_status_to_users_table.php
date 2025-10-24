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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_online')->default(false)->after('fcm_token');
            $table->timestamp('last_seen')->nullable()->after('is_online');
            $table->timestamp('last_heartbeat')->nullable()->after('last_seen');

            // Index pour optimiser les requêtes
            $table->index(['is_online', 'last_seen']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['is_online', 'last_seen']);
            $table->dropColumn(['is_online', 'last_seen', 'last_heartbeat']);
        });
    }
};
