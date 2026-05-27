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
        Schema::table('chain_custody', function (Blueprint $table) {
            if (!Schema::hasColumn('chain_custody', 'code_sample')) {
                $table->string('code_sample')->nullable();
            }
            if (!Schema::hasColumn('chain_custody', 'coordinate')) {
                $table->string('coordinate')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chain_custody', function (Blueprint $table) {
            if (Schema::hasColumn('chain_custody', 'code_sample')) {
                $table->dropColumn('code_sample');
            }
            if (Schema::hasColumn('chain_custody', 'coordinate')) {
                $table->dropColumn('coordinate');
            }
        });
    }
};
