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
        Schema::create('chain_custody', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('application_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('os')->nullable();
            $table->json('content')->nullable();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('application_id')->references('id')->on('companies');
            $table->foreign('order_id')->references('id')->on('order_service');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chain_custody');
    }
};
