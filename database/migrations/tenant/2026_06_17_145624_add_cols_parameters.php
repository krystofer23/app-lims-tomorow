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
        Schema::table('parameters', function (Blueprint $table) {
            if (!Schema::hasColumn('parameters', 'is_metal')) {
                $table->boolean('is_metal')->default(false);
            }
            if (!Schema::hasColumn('parameters', 'ids_connections_parameters')) {
                $table->json('ids_connections_parameters')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parameters', function (Blueprint $table) {
            if (Schema::hasColumn('parameters', 'is_metal')) {
                $table->dropColumn('is_metal');
            }
            if (Schema::hasColumn('parameters', 'ids_connections_parameters')) {
                $table->dropColumn('ids_connections_parameters');
            }
        });
    }
};
