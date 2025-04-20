<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $fillable = [
        // 1. Core Client Information
        'category_id',
        'category_name',
        'category_status',
        'category_description',
    ];

    protected $casts = [
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d',
    ];

    public $translatable = ['category_name', 'category_description'];

    /**
     * Booted method to attach model event listeners.
     */
    protected static function booted()
    {
        static::updated(function ($category) {
            // Get all translations for the updated category
            $translations = $category->getTranslations('category_name');
            
            // Update all products that belong to this category
            Product::where('category_id', $category->id)
                   ->get()
                   ->each(function ($product) use ($translations) {
                       $product->setTranslations('category_name', $translations);
                       $product->save();
                   });
        });
    }
        /**
     * Get the subcategories for the categories.
     */
    public function subcategories(): HasMany
    {
        return $this->hasMany(subcategory::class);
    }
}
