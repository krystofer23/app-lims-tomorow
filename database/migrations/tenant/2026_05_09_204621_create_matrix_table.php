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
        Schema::create('matrix', function (Blueprint $table) {
            $table->id();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('type_of_sample_id')->nullable();
            $table->foreign('type_of_sample_id')->references('id')->on('type_of_samples');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matrix');
    }
};
