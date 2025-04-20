<?php

namespace App\Http\Controllers\Api\ProductAssetSuite\EqServicings;

use App\Models\EqServicing;
use Orion\Http\Controllers\Controller;
use Orion\Concerns\DisableAuthorization;
use Orion\Concerns\DisablePagination;
use App\Http\Resources\EqServicingsCollection;

class MetaEqServicingsController extends Controller
{
    use DisableAuthorization;
    use DisablePagination;

    /**
     * Fully-qualified model class name
     */
    protected $model = EqServicing::class;

    protected $collectionResource = EqServicingsCollection::class;

    public function filterableBy(): array
    {
        return ['eq_servicing_type','category_id', 'category_name', 'subcategory_id', 'subcategory_name'];
    }
}
