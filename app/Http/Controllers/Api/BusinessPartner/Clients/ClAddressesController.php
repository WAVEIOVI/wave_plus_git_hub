<?php

namespace App\Http\Controllers\Api\BusinessPartner\Clients;

use App\Models\Client;
use Orion\Http\Controllers\RelationController;
use Orion\Concerns\DisableAuthorization;
use Orion\Concerns\DisablePagination;

class ClAddressesController extends RelationController
{
    use DisableAuthorization;
    use DisablePagination;


    /**
     * Fully-qualified model class name
     */
    protected $model = Client::class;

    protected $relation = 'addresses';

    public function searchableBy(): array
    {
        return ['type', 'address_street', 'phone', 'email', 'responsible', 'work_team'];
    }

    public function filterableBy(): array
    {
        return ['type', 'work_team', 'responsible'];
    }
}
