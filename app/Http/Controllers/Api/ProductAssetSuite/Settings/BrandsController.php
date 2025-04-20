<?php

namespace App\Http\Controllers\Api\ProductAssetSuite\Settings;

use App\Models\Brand;
use Orion\Http\Controllers\Controller;
use Orion\Concerns\DisableAuthorization;
use Illuminate\Http\Request;
use Orion\Concerns\DisablePagination;

class BrandsController extends Controller
{
    use DisableAuthorization;
    use DisablePagination;

    /**
     * Fully-qualified model class name
     */
    protected $model = Brand::class;

    public function searchableBy(): array
    {
        return ['brand_id', 'name', 'description', 'website'];
    }
}
