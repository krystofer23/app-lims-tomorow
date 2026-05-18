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
            if (! Schema::hasColumn('parameters', 'type_id')) {
                $table->unsignedBigInteger('type_id')->nullable();
                $table->foreign('type_id')->references('id')->on('type_of_samples');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parameters', function (Blueprint $table) {
            if (! Schema::hasColumn('parameters', 'type_id')) {
                $table->dropColumn('type_id');
            }
        });
    }
};
