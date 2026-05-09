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
        Schema::create('items_matriz', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('matriz_id')->nullable();
            $table->unsignedBigInteger('parameter_id')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->unsignedBigInteger('units_measurement_id')->nullable();
            $table->string('lcm')->nullable();
            $table->json('conditions');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items_matriz');
    }
};
