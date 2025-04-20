<?php

namespace App\Http\Controllers\Api\BusinessPartner\Suppliers;

use App\Models\Supplier;
use Orion\Http\Controllers\Controller;
use Orion\Concerns\DisableAuthorization;
use Orion\Concerns\DisablePagination;
use App\Http\Resources\SuppliersCollection;

class MetaSuppliersController extends Controller
{
    use DisableAuthorization;
    use DisablePagination;

    /**
     * Fully-qualified model class name
     */
    protected $model = Supplier::class;

    protected $collectionResource = SuppliersCollection::class;

    public function filterableBy(): array
    {
        return ['supplier_tier', 'industry', 'supplier_status'];
    }
}
