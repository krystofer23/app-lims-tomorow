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
        Schema::table('order_service', function (Blueprint $table) {
            if (!Schema::hasColumn('order_service', 'date_exit')) {
                $table->date('date_exit')->nullable();
            }
            if (!Schema::hasColumn('order_service', 'date_induction')) {
                $table->date('date_induction')->nullable();
            }
            if (!Schema::hasColumn('order_service', 'date_init_monitoring')) {
                $table->date('date_init_monitoring')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_service', function (Blueprint $table) {
            if (Schema::hasColumn('order_service', 'date_exit')) {
                $table->dropColumn('date_exit');
            }
            if (Schema::hasColumn('order_service', 'date_induction')) {
                $table->dropColumn('date_induction');
            }
            if (Schema::hasColumn('order_service', 'date_init_monitoring')) {
                $table->dropColumn('date_init_monitoring');
            }
        });
    }
};
