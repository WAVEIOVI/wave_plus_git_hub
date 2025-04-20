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
        Schema::create('consumables', function (Blueprint $table) {
            $table->id();
            // Core Identification
            $table->string('consumable_id')->unique();
            $table->json('name', 255);
            $table->json('description')->nullable();
            // Pricing & Financials
            $table->decimal('purchase_price', 12, 3)->nullable()->comment('Purchase price from supplier');
            $table->decimal('profit_margin', 5, 2)->default(0)->comment('Profit percentage applied');
            $table->decimal('base_price', 12, 3)->nullable()->comment('Original price before discounts');
            $table->decimal('discount_rate', 5, 2)->default(0)->comment('Discount percentage applied');
            $table->decimal('sale_price', 12, 3)->nullable()->comment('Final price after discounts');
            $table->decimal('tax_rate', 5, 2)->default(0)->comment('Tax percentage applied');
            $table->decimal('tax_sale', 12, 3)->nullable()->comment('The sale price of the consumable with tax.');
            // Inventory & Logistics
            $table->integer('stock_quantity')->nullable();
            $table->string('dimensions', 50)->nullable()->comment('Format: LxWxH');
            // Classification & Categorization
            $table->string('certifications')->nullable();
            $table->string('warranty_info')->nullable();
            $table->string('category_id')->nullable();
            $table->json('category_name')->nullable();
            $table->string('subcategory_id')->nullable();
            $table->json('subcategory_name')->nullable();
            $table->string('brand_name')->nullable();
            $table->enum('consumable_type', ['agents and disposables', 'replacement parts', 'safety signage', 'accessories'])->nullable();
            $table->enum('consumable_status', ['active', 'draft', 'inactive'])->default('draft');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumables');
    }
};
