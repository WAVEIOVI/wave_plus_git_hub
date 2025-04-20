<?php

namespace App\Http\Controllers\Api\ProductAssetSuite\EqBlueprints;

use App\Models\EqBlueprint;
use Orion\Http\Controllers\Controller;
use Orion\Concerns\DisableAuthorization;
use Orion\Concerns\DisablePagination;
use App\Http\Resources\EqBlueprintsCollection;

class MetaEqBlueprintsController extends Controller
{
    use DisableAuthorization;
    use DisablePagination;

    /**
     * Fully-qualified model class name
     */
    protected $model = EqBlueprint::class;

    protected $collectionResource = EqBlueprintsCollection::class;

    public function filterableBy(): array
    {
        return ['category_id', 'category_name', 'subcategory_id', 'subcategory_name'];
    }
}
