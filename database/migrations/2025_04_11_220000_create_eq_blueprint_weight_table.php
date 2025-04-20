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
        Schema::create('eq_blueprint_weight', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eq_blueprint_id')->constrained()->onDelete('cascade');
            $table->foreignId('weight_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eq_blueprint_weight');
    }
};
