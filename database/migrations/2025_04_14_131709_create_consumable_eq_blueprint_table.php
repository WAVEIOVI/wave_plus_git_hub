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
        Schema::create('consumable_eq_blueprint', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eq_blueprint_id')->constrained()->onDelete('cascade');
            $table->foreignId('consumable_id')->constrained()->onDelete('cascade');
            $table->string('purpose')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumable_eq_blueprint');
    }
};
