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
        Schema::create('eq_blueprints', function (Blueprint $table) {
            $table->id();
            // Core Identification
            $table->string('eq_blueprint_id')->unique();
            $table->json('name', 255);
            $table->json('description')->nullable();
            // Classification & Categorization
            $table->string('category_id')->nullable();
            $table->json('category_name')->nullable();
            $table->string('subcategory_id')->nullable();
            $table->json('subcategory_name')->nullable();
            $table->enum('eq_blueprint_status', ['active', 'draft', 'inactive'])->default('draft');
            //2. Technical Specifications
            $table->string('inspection_frequency')->nullable();
            $table->string('hydro_test_frequency')->nullable();
            $table->string('fire_class_rating')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eq_blueprints');
    }
};
