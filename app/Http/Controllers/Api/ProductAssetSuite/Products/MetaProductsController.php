<?php

namespace App\Http\Controllers\Api\ProductAssetSuite\Products;

use App\Models\Product;
use Orion\Http\Controllers\Controller;
use Orion\Concerns\DisableAuthorization;
use Orion\Concerns\DisablePagination;
use App\Http\Resources\ProductsCollection;

class MetaProductsController extends Controller
{
    use DisableAuthorization;
    use DisablePagination;

    /**
     * Fully-qualified model class name
     */
    protected $model = Product::class;

    protected $collectionResource = ProductsCollection::class;

    public function filterableBy(): array
    {
        return ['product_type','category_id', 'category_name', 'subcategory_id', 'subcategory_name'];
    }
}
