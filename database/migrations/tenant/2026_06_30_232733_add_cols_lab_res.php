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
        Schema::table('laboratory_results', function (Blueprint $table) {
            if (!Schema::hasColumn('laboratory_results', 'result_axis')) {
                $table->string('result_axis')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laboratory_results', function (Blueprint $table) {
            if (Schema::hasColumn('laboratory_results', 'result_axis')) {
                $table->dropColumn('result_axis');
            }
        });
    }
};
