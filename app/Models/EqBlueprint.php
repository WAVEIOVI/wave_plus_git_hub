<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class EqBlueprint extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $fillable = [
        //1. Core Identification
        'eq_blueprint_id',
        'category_id',
        'subcategory_id',
        'category_name',
        'subcategory_name',
        'name',
        'description',
        'eq_blueprint_status',
        //2. Technical Specifications
        'inspection_frequency',
        'hydro_test_frequency',
        'fire_class_rating',
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
        static::creating(function (EqBlueprint $eqBlueprint) {
            $eqBlueprint->eq_blueprint_id = self::generateEqBlueprintId();
        });

        static::saving(function ($eqBlueprint) {
            if ($eqBlueprint->category_id) {
                $category = Category::find($eqBlueprint->category_id);
                if ($category) {
                    // Get all translations for category_name
                    $translations = $category->getTranslations('category_name');
                    // Set all translations to the eqBlueprint
                    $eqBlueprint->setTranslations('category_name', $translations);
                }
            }

            if ($eqBlueprint->subcategory_id) {
                $subcategory = Subcategory::find($eqBlueprint->subcategory_id);
                if ($subcategory) {
                    // Get all translations for subcategory_name
                    $translations = $subcategory->getTranslations('subcategory_name');
                    // Set all translations to the eqBlueprint
                    $eqBlueprint->setTranslations('subcategory_name', $translations);
                }
            }
        });
    }

    /**
     * Generate a unique eqBlueprint ID with prefix based on eqBlueprint type.
     *
     * @param string $eqBlueprintType
     * @return string
     */

     protected static function generateEqBlueprintId(): string
    {
        $prefix = 'EB25'; // Adjust the prefix as needed.
        $lastEqBlueprint = self::withTrashed()
            ->where('eq_blueprint_id', 'like', $prefix . '%')
            ->orderByDesc('eq_blueprint_id')
            ->first();

        $nextNumber = 1;
        if ($lastEqBlueprint) {
            // Extract numeric part after the prefix and increment it
            $lastNumber = (int) substr($lastEqBlueprint->eq_blueprint_id, strlen($prefix));
            $nextNumber = $lastNumber + 1;
        }

        // Return blueprint id formatted with leading zeros (4 digits)
        return $prefix . sprintf('%04d', $nextNumber);
    }

    public function weights(): BelongsToMany
    {
        return $this->belongsToMany(Weight::class)->withTimestamps();
    }

    public function pressures(): BelongsToMany
    {
        return $this->belongsToMany(Pressure::class)->withTimestamps();
    }

    public function eq_servicings(): BelongsToMany
    {
        return $this->belongsToMany(EqServicing::class)->withTimestamps()
        ->withPivot('purpose', 'pression_type');
    }

    public function consumables(): BelongsToMany
    {
        return $this->belongsToMany(Consumable::class)->withTimestamps()
            ->withPivot('purpose');
    }

}
