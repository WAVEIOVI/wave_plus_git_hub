<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class EqServicing extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $fillable = [
        // Core Identification
        'eq_servicing_id',
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
        // Classification & Categorization
        'certifications',
        'warranty_info',
        'category_id',
        'category_name',
        'subcategory_id',
        'subcategory_name',
        'brand_name',
        'eq_servicing_type',
        'eq_servicing_status',
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
        static::creating(function (EqServicing $eqServicing) {
            $eqServicing->eq_servicing_id = self::generateEqServicingId($eqServicing->eq_servicing_type);
        });

        static::saving(function ($eqServicing) {
            if ($eqServicing->category_id) {
                $category = Category::find($eqServicing->category_id);
                if ($category) {
                    // Get all translations for category_name
                    $translations = $category->getTranslations('category_name');
                    // Set all translations to the eqServicing
                    $eqServicing->setTranslations('category_name', $translations);
                }
            }

            if ($eqServicing->subcategory_id) {
                $subcategory = Subcategory::find($eqServicing->subcategory_id);
                if ($subcategory) {
                    // Get all translations for subcategory_name
                    $translations = $subcategory->getTranslations('subcategory_name');
                    // Set all translations to the eqServicing
                    $eqServicing->setTranslations('subcategory_name', $translations);
                }
            }
        });
    }

    /**
     * Generate a unique eqServicing ID with prefix based on eqServicing type.
     *
     * @param string $eqServicingType
     * @return string
     */
    protected static function generateEqServicingId(string $eqServicingType): string
    {
        // Define the prefixes for each eqServicing type.
        $prefixes = [
            'inspection and testing' => 'IT',
            'maintenance and repair'  => 'MR',
            'refill recharge'  => 'RR',
            'calibration and certification'  => 'CC',
            'installation decommissioning'  => 'ID',
        ];

        // Get the appropriate prefix for the eqServicing type, defaulting to 'IT' if not found.
        $prefix = $prefixes[$eqServicingType] ?? 'IT';

        // Query the last created eqServicing with the same prefix (including soft-deleted ones).
        $lastEqServicing = self::withTrashed()
            ->where('eq_servicing_id', 'like', $prefix . '%')
            ->orderByDesc('eq_servicing_id')
            ->first();

        // Set the starting number to 1 if no previous eqServicing exists.
        $nextNumber = 1;
        if ($lastEqServicing) {
        // Extract the numeric part after the prefix and increment it.
        $lastNumber = (int) substr($lastEqServicing->eq_servicing_id, strlen($prefix));
        $nextNumber = $lastNumber + 1;
        }

        // Return the eq_servicing id formatted with leading zeros (3 digits, e.g., 001).
        return $prefix . sprintf('%05d', $nextNumber);
    }

    public function eq_blueprints(): BelongsToMany
    {
        return $this->belongsToMany(EqBlueprint::class)->withTimestamps()
        ->withPivot('purpose', 'pression_type');
    }
}
