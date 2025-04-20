<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Subcategory extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $fillable = [
        // 1. Core Client Information
        'subcategory_id',
        'subcategory_name',
        'subcategory_status',
        'subcategory_description',
        'category_id',
    ];

    protected $casts = [
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d',
    ];

    public $translatable = ['subcategory_name', 'subcategory_description'];

    protected static function booted()
    {
        static::updated(function ($subcategory) {
            // Get all translations for the updated subcategory
            $translations = $subcategory->getTranslations('subcategory_name');
            
            // Update all products that belong to this subcategory
            Product::where('subcategory_id', $subcategory->id)
                   ->get()
                   ->each(function ($product) use ($translations) {
                       $product->setTranslations('subcategory_name', $translations);
                       $product->save();
                   });

            // // Update in equipment table
            // Equipment::where('category_id', $category->id)
            //          ->update(['category_name' => $category->name]);

            // // Update in consumables table
            // Consumable::where('category_id', $category->id)
            //           ->update(['category_name' => $category->name]);
        });
    }


    /**
     * Get the category that owns the subcategory.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
