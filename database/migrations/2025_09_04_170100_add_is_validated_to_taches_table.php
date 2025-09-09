<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('taches', function (Blueprint $table) {
            if (!Schema::hasColumn('taches', 'is_validated')) {
                $table->boolean('is_validated')->default(false)->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('taches', function (Blueprint $table) {
            if (Schema::hasColumn('taches', 'is_validated')) {
                $table->dropColumn('is_validated');
            }
        });
    }
};



