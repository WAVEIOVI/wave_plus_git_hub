<?php

namespace App\Http\Controllers\Api\BusinessPartner\Clients;

use App\Models\Client;
use Orion\Http\Controllers\Controller;
use Orion\Concerns\DisableAuthorization;
use Orion\Concerns\DisablePagination;
use App\Http\Resources\ClientsCollection;

class MetaClientsController extends Controller
{
    use DisableAuthorization;
    use DisablePagination;

    /**
     * Fully-qualified model class name
     */
    protected $model = Client::class;

    protected $collectionResource = ClientsCollection::class;

    public function filterableBy(): array
    {
        return ['client_tier', 'industry', 'client_status'];
    }
}
