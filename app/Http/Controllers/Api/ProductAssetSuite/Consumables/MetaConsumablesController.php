<?php

namespace App\Http\Controllers\Api\ProductAssetSuite\Consumables;

use App\Models\Consumable;
use Orion\Http\Controllers\Controller;
use Orion\Concerns\DisableAuthorization;
use Orion\Concerns\DisablePagination;
use App\Http\Resources\ConsumablesCollection;

class MetaConsumablesController extends Controller
{
    use DisableAuthorization;
    use DisablePagination;

    /**
     * Fully-qualified model class name
     */
    protected $model = Consumable::class;

    protected $collectionResource = ConsumablesCollection::class;

    public function filterableBy(): array
    {
        return ['consumable_type','category_id', 'category_name', 'subcategory_id', 'subcategory_name'];

    }
}
