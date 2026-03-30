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
        Schema::create('order_service', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quote_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('reviwed_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();

            $table->foreign('quote_id')->references('id')->on('quotes');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('reviwed_id')->references('id')->on('users');
            $table->foreign('company_id')->references('id')->on('companies');

            $table->string('reviwed')->nullable();

            $table->text('reference')->nullable();
            $table->string('origin', 255)->nullable();
            $table->string('project', 255)->nullable();

            $table->date('date_monitoring_init')->nullable();
            $table->date('date_monitoring_end')->nullable();
            $table->date('date_induction')->nullable();
            $table->date('date_output')->nullable();

            $table->text('details')->nullable();

            $table->text('stations_monitoring')->nullable();
            $table->text('project_monitoring')->nullable();

            $table->json('conditions')->nullable();
            $table->json('emision_data')->nullable();
            $table->text('observations')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_service');
    }
};
