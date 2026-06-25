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
        Schema::table('companies', function (Blueprint $table) {
            if (!Schema::hasColumn('companies', 'name')) {
                $table->string('name')->nullable();
            }
            if (!Schema::hasColumn('companies', 'email')) {
                $table->string('email')->nullable();
            }
            if (!Schema::hasColumn('companies', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('companies', 'contact')) {
                $table->string('contact')->nullable();
            }
            if (!Schema::hasColumn('companies', 'observations')) {
                $table->text('observations')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            if (Schema::hasColumn('companies', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('companies', 'name')) {
                $table->dropColumn('email');
            }
            if (Schema::hasColumn('companies', 'name')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('companies', 'name')) {
                $table->dropColumn('contact');
            }
            if (Schema::hasColumn('companies', 'name')) {
                $table->dropColumn('observations');
            }
        });
    }
};
