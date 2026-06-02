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
        Schema::create('laboratory_results', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on('order_service');

            $table->unsignedBigInteger('order_item_id')->nullable();

            $table->unsignedBigInteger('parameter_id')->nullable();
            $table->foreign('parameter_id')->references('id')->on('parameters');

            $table->unsignedBigInteger('matrix_id')->nullable();
            $table->foreign('matrix_id')->references('id')->on('matrix');

            $table->unsignedBigInteger('type_of_sample_id')->nullable();
            $table->foreign('type_of_sample_id')->references('id')->on('type_of_samples');

            $table->unsignedBigInteger('item_id')->nullable();
            $table->foreign('item_id')->references('id')->on('items');

            $table->unsignedBigInteger('chain_custody_id')->nullable();
            $table->foreign('chain_custody_id')->references('id')->on('chain_custody');

            $table->string('code_season')->nullable();
            $table->string('code_lab')->nullable();
            $table->string('code_sample')->nullable();

            $table->string('result')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(
                ['order_id', 'item_id', 'chain_custody_id'],
                'lab_results_order_item_chain_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratory_results');
    }
};
