<?php

namespace App\Http\Controllers\Api\ProductAssetSuite\Settings;

use App\Models\Weight;
use Orion\Http\Controllers\Controller;
use Orion\Concerns\DisableAuthorization;
use Illuminate\Http\Request;
use Orion\Concerns\DisablePagination;

class WeightsController extends Controller
{
    use DisableAuthorization;
    use DisablePagination;

    /**
     * Fully-qualified model class name
     */
    protected $model = Weight::class;

    public function searchableBy(): array
    {
        return ['weight_id', 'name', 'description', 'website'];
    }
}
