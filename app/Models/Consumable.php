<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Consumable extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $fillable = [
        // Core Identification
        'consumable_id',
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
        'consumable_type',
        'consumable_status',
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
        static::creating(function (Consumable $consumable) {
            $consumable->consumable_id = self::generateConsumableId($consumable->consumable_type);
        });

        static::saving(function ($consumable) {
            if ($consumable->category_id) {
                $category = Category::find($consumable->category_id);
                if ($category) {
                    // Get all translations for category_name
                    $translations = $category->getTranslations('category_name');
                    // Set all translations to the consumable
                    $consumable->setTranslations('category_name', $translations);
                }
            }

            if ($consumable->subcategory_id) {
                $subcategory = Subcategory::find($consumable->subcategory_id);
                if ($subcategory) {
                    // Get all translations for subcategory_name
                    $translations = $subcategory->getTranslations('subcategory_name');
                    // Set all translations to the consumable
                    $consumable->setTranslations('subcategory_name', $translations);
                }
            }
        });
    }

    /**
     * Generate a unique consumable ID with prefix based on consumable type.
     *
     * @param string $consumableType
     * @return string
     */
    protected static function generateConsumableId(string $consumableType): string
    {
        // Define the prefixes for each consumable type.
        $prefixes = [
            'agents and disposables' => 'AD',
            'replacement parts'  => 'RP',
            'safety signage'  => 'SS',
            'accessories'  => 'AC',
        ];

        // Get the appropriate prefix for the consumable type, defaulting to 'PP' if not found.
        $prefix = $prefixes[$consumableType] ?? 'PP';

        // Query the last created consumable with the same prefix (including soft-deleted ones).
        $lastConsumable = self::withTrashed()
            ->where('consumable_id', 'like', $prefix . '%')
            ->orderByDesc('consumable_id')
            ->first();

        // Set the starting number to 1 if no previous consumable exists.
        $nextNumber = 1;
        if ($lastConsumable) {
        // Extract the numeric part after the prefix and increment it.
        $lastNumber = (int) substr($lastConsumable->consumable_id, strlen($prefix));
        $nextNumber = $lastNumber + 1;
        }

        // Return the consumable id formatted with leading zeros (3 digits, e.g., 001).
        return $prefix . sprintf('%05d', $nextNumber);
    }

    public function eq_blueprints(): BelongsToMany
    {
        return $this->belongsToMany(EqBlueprint::class)->withTimestamps()
        ->withPivot('purpose');
    }
}
