<?php

namespace App\Http\Controllers\Api\ProductAssetSuite\EqBlueprints;

use App\Models\EqBlueprint;
use Orion\Concerns\DisableAuthorization;
use Orion\Concerns\DisablePagination;
use Illuminate\Http\Request;
use Orion\Http\Controllers\RelationController;


class EqBlueprintsEqServicingsController extends RelationController
{
    use DisableAuthorization;
    use DisablePagination;

    /**
     * Fully-qualified model class name
     */
    protected $model = EqBlueprint::class;

    protected $relation = 'eq_servicings';

    public function searchableBy(): array
    {
        return ['eq_blueprint_id', 'name', 'description','category_id', 'category_name', 'subcategory_id', 'subcategory_name','eq_blueprint_status', 'pivot.pression_type' ];
    }

    public function filterableBy(): array
    {
        return ['name','category_id', 'category_name', 'subcategory_id', 'subcategory_name', 'pivot.pression_type'];
    }

    public function sortableBy(): array
    {
        return ['created_at', 'eq_blueprint_id', 'name', 'category_id', 'category_name',  'subcategory_id', 'subcategory_name', 'eq_blueprint_status'];
    }
}
