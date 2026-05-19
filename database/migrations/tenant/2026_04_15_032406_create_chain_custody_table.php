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
            $table->foreign('company_id')->references('id')->on('companies');

            $table->unsignedBigInteger('application_id')->nullable();
            $table->foreign('application_id')->references('id')->on('companies');

            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on('order_service');

            $table->string('os')->nullable();

            $table->string('number_chain')->nullable();
            $table->string('number_report')->nullable();

            $table->unsignedBigInteger('type_of_sample_id')->nullable();
            $table->foreign('type_of_sample_id')->references('id')->on('type_of_samples');

            $table->unsignedBigInteger('matrix_id')->nullable();
            $table->foreign('matrix_id')->references('id')->on('matrix');

            $table->string('number_sample')->nullable();
            $table->string('number_essays')->nullable();

            $table->dateTime('date_reception')->nullable();

            $table->date('date_sampling_init_date')->nullable();
            $table->time('date_sampling_init_time')->nullable();

            $table->date('date_sampling_end_date')->nullable();
            $table->time('date_sampling_end_time')->nullable();

            $table->date('date_agreed')->nullable();

            $table->string('company_sampling_id')->nullable();
            $table->string('code_lab')->nullable();
            $table->string('code_season')->nullable();
            $table->string('condition_report')->nullable();

            $table->unsignedBigInteger('other_company_id')->nullable();
            $table->foreign('other_company_id')->references('id')->on('companies');

            $table->text('observations')->nullable();

            $table->json('parameters')->nullable();

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
