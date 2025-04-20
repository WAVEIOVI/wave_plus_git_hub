<?php

namespace App\Http\Controllers\Api\ProductAssetSuite\Categories;

use App\Models\Category;
use Orion\Http\Controllers\Controller;
use Orion\Concerns\DisableAuthorization;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    use DisableAuthorization;

    /**
     * Fully-qualified model class name
     */
    protected $model = Category::class;

    public function searchableBy(): array
    {
        return ['category_id', 'category_name', 'category_status', 'category_description'];
    }

    public function filterableBy(): array
    {
        return ['category_status'];
    }

    public function sortableBy(): array
    {
        return ['created_at', 'category_id', 'category_name', 'category_status'];
    }   
}
