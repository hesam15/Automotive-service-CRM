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
            $table->unsignedBiginteger('service_center_id');
            $table->unsignedBiginteger('customer_id');

            $table->unique(['service_center_id', 'customer_id']);

            $table->foreign('service_center_id')->references('id')->on('service_centers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade')->onUpdate('cascade');
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
