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
        Schema::create('connection_parameter', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('parameter_id')->nullable();
            $table->foreign('parameter_id')->references('id')->on('parameters');

            $table->unsignedBigInteger('matrix_id')->nullable();
            $table->foreign('matrix_id')->references('id')->on('matrix');

            $table->unsignedBigInteger('type_of_samples_id')->nullable();
            $table->foreign('type_of_samples_id')->references('id')->on('type_of_samples');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('connection_parameter');
    }
};
