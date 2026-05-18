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

            $table->foreign('quote_id')->references('id')->on('quotes');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('reviwed_id')->references('id')->on('users');

            $table->string('reviwed')->nullable();

            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('contact_company')->nullable();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('contact_company')->references('id')->on('contact_companies');

            $table->string('direction')->nullable();
            $table->date('date_attention')->nullable();

            $table->unsignedBigInteger('application_id')->nullable();
            $table->unsignedBigInteger('contact_application')->nullable();

            $table->foreign('application_id')->references('id')->on('companies');
            $table->foreign('contact_application')->references('id')->on('contact_companies');

            $table->string('department')->nullable();
            $table->string('district')->nullable();
            $table->string('province')->nullable();

            $table->text('reference')->nullable();
            $table->text('origin')->nullable();
            $table->text('project')->nullable();

            $table->date('date_init_service');
            $table->date('date_end_monitoring');

            $table->json('users')->nullable();

            $table->text('details')->nullable();
            $table->text('monitoring')->nullable();
            $table->text('projects')->nullable();

            $table->string('service_includes')->nullable();
            $table->string('accommodation')->nullable();
            $table->string('travel_expenses')->nullable();
            $table->string('days_service')->nullable();
            $table->string('personal_transport')->nullable();
            $table->string('send_sampling')->nullable();
            $table->string('surveillance')->nullable();
            $table->string('electric_generator')->nullable();

            $table->unsignedBigInteger('company_emission_id')->nullable();
            $table->foreign('company_emission_id')->references('id')->on('companies');

            $table->string('type_document_required')->nullable();
            $table->string('number_copy')->nullable();

            $table->string('code')->nullable();
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
