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
        Schema::create('select_to_metals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on('order_service');

            $table->unsignedBigInteger('to_metal_id')->nullable();
            $table->foreign('to_metal_id')->references('id')->on('parameters');

            $table->unsignedBigInteger('parameter_id')->nullable();
            $table->foreign('parameter_id')->references('id')->on('parameters');

            $table->json('item')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('select_to_metals');
    }
};
