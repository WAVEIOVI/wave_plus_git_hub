<?php

namespace App\Http\Controllers\Api\BusinessPartner\Suppliers;

use App\Models\Supplier;
use Orion\Http\Controllers\RelationController;
use Orion\Concerns\DisableAuthorization;
use Orion\Concerns\DisablePagination;

class SuContactsController extends RelationController
{
    use DisableAuthorization;
    use DisablePagination;


    /**
     * Fully-qualified model class name
     */
    protected $model = Supplier::class;

    protected $relation = 'contacts';

    public function searchableBy(): array
    {
        return ['first_name', 'last_name', 'job_title', 'department', 'status'];
    }

    public function filterableBy(): array
    {
        return ['job_title', 'department', 'status'];
    }
}
