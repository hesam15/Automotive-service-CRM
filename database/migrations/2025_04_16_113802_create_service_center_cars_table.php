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
            $table->foreignId('service_center_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('car_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();

            $table->unique(['service_center_id', 'car_id']);
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
