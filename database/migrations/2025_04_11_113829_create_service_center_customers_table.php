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
        Schema::create('customer_service_center', function (Blueprint $table) {
            $table->foreignId('service_center_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();

            $table->unique(['service_center_id', 'customer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_service_center');
    }
};
