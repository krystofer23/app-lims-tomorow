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
        Schema::create('matriz', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('category')->nullable();
            $table->string('description')->nullable();
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->boolean('other_company')->default(false);

            $table->unsignedBigInteger('condition_id')->nullable();
            $table->foreign('condition_id')->references('id')->on('conditions');

            $table->unsignedBigInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matriz');
    }
};
