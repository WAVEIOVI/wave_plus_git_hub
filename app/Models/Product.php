<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;


class Product extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $fillable = [
        // Core Identification
        'product_id',
        'name',
        'description',
        // Pricing & Financials
        'purchase_price',
        'profit_margin',
        'base_price',
        'discount_rate',
        'sale_price',
        'tax_rate',
        'tax_sale',
        // Inventory & Logistics
        'stock_quantity',
        'dimensions',
        // Classification & Categorization
        'certifications',
        'warranty_info',
        'category_id',
        'category_name',
        'subcategory_id',
        'subcategory_name',
        'brand_name',
        'product_type',
        'product_status',
        ];

    protected $casts = [
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d',
    ];

    public $translatable = ['name', 'description','category_name','subcategory_name'];

    /**
    * Booted method to attach model event listeners.
    */
    protected static function booted()
    {
        static::creating(function (Product $product) {
            $product->product_id = self::generateProductId($product->product_type);
        });

        static::saving(function ($product) {
            if ($product->category_id) {
                $category = Category::find($product->category_id);
                if ($category) {
                    // Get all translations for category_name
                    $translations = $category->getTranslations('category_name');
                    // Set all translations to the product
                    $product->setTranslations('category_name', $translations);
                }
            }

            if ($product->subcategory_id) {
                $subcategory = Subcategory::find($product->subcategory_id);
                if ($subcategory) {
                    // Get all translations for subcategory_name
                    $translations = $subcategory->getTranslations('subcategory_name');
                    // Set all translations to the product
                    $product->setTranslations('subcategory_name', $translations);
                }
            }
        });
    }

    /**
     * Generate a unique product ID with prefix based on product type.
     *
     * @param string $productType
     * @return string
     */
    protected static function generateProductId(string $productType): string
    {
        // Define the prefixes for each product type.
        $prefixes = [
            'physical' => 'PP',
            'digital'  => 'PD',
            'service'  => 'PS',
        ];

        // Get the appropriate prefix for the product type, defaulting to 'PP' if not found.
        $prefix = $prefixes[$productType] ?? 'PP';

        // Query the last created product with the same prefix (including soft-deleted ones).
        $lastProduct = self::withTrashed()
            ->where('product_id', 'like', $prefix . '%')
            ->orderByDesc('product_id')
            ->first();

        // Set the starting number to 1 if no previous product exists.
        $nextNumber = 1;
        if ($lastProduct) {
        // Extract the numeric part after the prefix and increment it.
        $lastNumber = (int) substr($lastProduct->product_id, strlen($prefix));
        $nextNumber = $lastNumber + 1;
        }

        // Return the product id formatted with leading zeros (3 digits, e.g., 001).
        return $prefix . sprintf('%05d', $nextNumber);
    }
}
