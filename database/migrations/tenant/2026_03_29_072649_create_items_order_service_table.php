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
        Schema::create('items_order_service', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_service_id')->nullable();
            $table->foreign('order_service_id')->references('id')->on('order_service');

            $table->string('filable_type')->nullable();
            $table->unsignedBigInteger('filable_id')->nullable();

            $table->json('item')->nullable();
            $table->string('type')->nullable();
            $table->integer('amount')->nullable();
            $table->decimal('price_unit', 10, 2)->nullable();
            $table->decimal('total', 10, 2)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items_order_service');
    }
};
