<?php

namespace App\Http\Controllers\Api\BusinessPartner\Clients;

use App\Models\Client;
use Orion\Http\Controllers\RelationController;
use Orion\Concerns\DisableAuthorization;
use Orion\Concerns\DisablePagination;

class ClContactsController extends RelationController
{
    use DisableAuthorization;
    use DisablePagination;


    /**
     * Fully-qualified model class name
     */
    protected $model = Client::class;

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
