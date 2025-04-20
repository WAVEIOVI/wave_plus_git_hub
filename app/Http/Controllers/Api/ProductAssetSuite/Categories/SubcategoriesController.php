<?php

namespace App\Http\Controllers\Api\ProductAssetSuite\Categories;

use App\Models\Category;
use Orion\Http\Controllers\RelationController;
use Orion\Concerns\DisableAuthorization;
use Illuminate\Http\Request;

class SubcategoriesController extends RelationController
{
    use DisableAuthorization;

    /**
     * Fully-qualified model class name
     */
    protected $model = Category::class;

    protected $relation = 'subcategories';

    public function searchableBy(): array
    {
        return ['subcategory_id', 'subcategory_name', 'subcategory_status', 'subcategory_description'];
    }

    public function filterableBy(): array
    {
        return ['subcategory_status'];
    }
}
