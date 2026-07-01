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
        Schema::create('laboratory_rni_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained('order_service');
            $table->unsignedBigInteger('order_item_id')->nullable();

            $table->unsignedBigInteger('item_id')->nullable();
            $table->unsignedBigInteger('parameter_id')->nullable();
            $table->unsignedBigInteger('matrix_id')->nullable();
            $table->unsignedBigInteger('type_of_sample_id')->nullable();

            $table->foreignId('chain_custody_id')->constrained('chain_custody');

            $table->string('measurement_period', 30)->default('PUNTA');

            $table->date('date_monitoring')->nullable();
            $table->string('hour_sampling')->nullable();
            $table->string('humidity_relative')->nullable();
            $table->string('ambient_temperature')->nullable();
            $table->string('electric_system_type')->nullable();

            $table->string('instrument')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('probe_range')->nullable();
            $table->date('calibration_date')->nullable();
            $table->string('certificate_number')->nullable();

            $table->text('station_description')->nullable();
            $table->text('soil_coverage')->nullable();
            $table->text('climate_conditions')->nullable();

            $table->json('measurements')->nullable();
            $table->json('summary')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique([
                'order_id',
                'item_id',
                'chain_custody_id',
                'measurement_period',
            ], 'rni_unique_result');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratory_rni_results');
    }
};
