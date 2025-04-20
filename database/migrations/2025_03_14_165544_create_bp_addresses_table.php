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
        Schema::create('bp_addresses', function (Blueprint $table) {
            $table->id();
            // Polymorphic fields
            $table->unsignedBigInteger('addressable_id');
            $table->string('addressable_type');
            // Contact fields
            $table->string('type');
            $table->string('address_street');
            $table->string('postcode')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('notes')->nullable();
            $table->string('responsible')->nullable();
            $table->string('nearest_fire_station')->nullable();
            $table->string('work_team')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bp_addresses');
    }
};
