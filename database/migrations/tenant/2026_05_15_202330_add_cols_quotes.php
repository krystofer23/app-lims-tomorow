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
        Schema::table('quotes', function (Blueprint $table) {
            if (! Schema::hasColumn('quotes', 'applicant_id')) {
                $table->unsignedBigInteger('applicant_id')->nullable();
                $table->foreign('applicant_id')->references('id')->on('companies');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            if (! Schema::hasColumn('quotes', 'applicant_id')) {
                $table->dropColumn('applicant_id');
            }
        });
    }
};
