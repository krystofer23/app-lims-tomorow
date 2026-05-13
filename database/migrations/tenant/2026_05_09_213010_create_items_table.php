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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();

            $table->unsignedBigInteger('condition_id')->nullable();
            $table->unsignedBigInteger('matrix_id')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('parameter_id')->nullable();

            $table->unsignedBigInteger('unit_measurement_id')->nullable();
            $table->string('lcm')->nullable();

            $table->boolean('is_operation')->default(false);
            $table->json('operations')->nullable();

            $table->boolean('is_other_company')->default(false);
            $table->unsignedBigInteger('company_id')->nullable();

            $table->decimal('unit_price', 10, 2)->nullable();

            $table->foreign('condition_id')->references('id')->on('conditions');
            $table->foreign('matrix_id')->references('id')->on('matrix');
            $table->foreign('reference_id')->references('id')->on('references_standard');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('unit_measurement_id')->references('id')->on('units_measurement');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('parameter_id')->references('id')->on('parameters');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
