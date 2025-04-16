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
        Schema::create('car_service_center', function (Blueprint $table) {
            $table->unsignedBiginteger('service_center_id');
            $table->unsignedBiginteger('car_id');

            $table->unique(['service_center_id', 'car_id']);

            $table->foreign('service_center_id')->references('id')->on('service_centers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade')->onUpdate('cascade');   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_service_center');
    }
};
