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
        Schema::create('relations_essay_team', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('essay_id')->nullable();
            $table->unsignedBigInteger('team_id')->nullable();
            $table->foreign('essay_id')->references('id')->on('essays');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relations_essay_team');
    }
};
