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
        Schema::create('permissions_role', function (Blueprint $table) {
            $table->foreignId('permissions_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate()->cascadeOnUpdate();

            $table->primary(['permissions_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions_role');
    }
};
