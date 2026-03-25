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
            $table->text('category')->nullable();
            $table->text('information')->nullable();
            $table->string('description')->nullable();
            $table->string('type_matriz')->nullable();
            $table->unsignedBigInteger('methodologie_id')->nullable();
            $table->integer('number_samples')->nullable();
            $table->foreign('methodologie_id')->references('id')->on('methodologies');
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->decimal('price', 10, 2)->nullable();
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
