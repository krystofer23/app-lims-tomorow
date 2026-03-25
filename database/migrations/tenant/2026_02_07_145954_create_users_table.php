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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('last_name_first')->nullable();
            $table->string('last_name_second')->nullable();
            $table->string('full_name')->nullable();
            $table->string('type_document')->nullable();
            $table->string('document_number')->nullable();
            $table->string('sex')->nullable();
            $table->string('age')->nullable();
            $table->string('birthdate')->nullable();

            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();

            $table->boolean('is_professional')->default(false);
            $table->string('signature')->nullable();

            $table->boolean('active')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
