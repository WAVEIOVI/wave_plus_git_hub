<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Orion\Http\Resources\CollectionResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ClientsCollection extends CollectionResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
        $collection = $this->collection;

        return [
            'count' => [
                'total_clients' => $collection->count(),
                'total_active_clients' => $collection->where('client_status', 'active')->count(),
                'total_pending_clients' => $collection->where('client_status', 'pending')->count(),
                'total_inactive_clients' => $collection->where('client_status', 'inactive')->count(),
                'total_blocked_clients' => $collection->where('client_status', 'blocked')->count(),
            ],
            'tiers' => $collection->groupBy('client_tier')->map->count(),
            'industries' => $collection->groupBy('industry')->map->count(),
            'dates' => [
                'oldest' => $collection->min('created_at'),
                'newest' => $collection->max('created_at'),
            ]
        ];
    }
}
