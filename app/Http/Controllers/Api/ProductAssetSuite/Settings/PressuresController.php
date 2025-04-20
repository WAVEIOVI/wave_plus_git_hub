<?php

namespace App\Http\Controllers\Api\ProductAssetSuite\Settings;

use App\Models\Pressure;
use Orion\Http\Controllers\Controller;
use Orion\Concerns\DisableAuthorization;
use Illuminate\Http\Request;
use Orion\Concerns\DisablePagination;

class PressuresController extends Controller
{
    use DisableAuthorization;
    use DisablePagination;

    /**
     * Fully-qualified model class name
     */
    protected $model = Pressure::class;

    public function searchableBy(): array
    {
        return ['pressure_id', 'name', 'description'];
    }
}
