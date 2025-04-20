<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Orion\Http\Resources\CollectionResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SuppliersCollection extends CollectionResource
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
                'total_suppliers' => $collection->count(),
                'total_active_suppliers' => $collection->where('supplier_status', 'active')->count(),
                'total_pending_suppliers' => $collection->where('supplier_status', 'pending')->count(),
                'total_inactive_suppliers' => $collection->where('supplier_status', 'inactive')->count(),
                'total_blocked_suppliers' => $collection->where('supplier_status', 'blocked')->count(),
            ],
            'tiers' => $collection->groupBy('supplier_tier')->map->count(),
            'industries' => $collection->groupBy('industry')->map->count(),
            'dates' => [
                'oldest' => $collection->min('created_at'),
                'newest' => $collection->max('created_at'),
            ]
        ];
    }
}
