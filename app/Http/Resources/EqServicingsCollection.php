<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Orion\Http\Resources\CollectionResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EqServicingsCollection extends CollectionResource
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
                'total_eq_servicings' => $collection->count(),
                'total_inspection_and_testing' => $collection->where('eq_servicing_type', 'inspection and testing')->count(),
                'total_maintenance_and_repair' => $collection->where('eq_servicing_type', 'maintenance and repair')->count(),
                'total_refill_recharge' => $collection->where('eq_servicing_type', 'refill recharge')->count(),
                'total_calibration_and_certification' => $collection->where('eq_servicing_type', 'calibration and certification')->count(),
                'total_installation_decommissioning' => $collection->where('eq_servicing_type', 'installation decommissioning')->count(),
            ],
            'types' => $collection->groupBy('eq_servicing_type')->map->count(),
            'categories' => $collection->groupBy('category_name')->map->count(),
            'subcategories' => $collection->groupBy('subcategory_name')->map->count(),
            'dates' => [
                'oldest' => $collection->min('created_at'),
                'newest' => $collection->max('created_at'),
            ]
        ];
    }
}
