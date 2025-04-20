<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Orion\Http\Resources\CollectionResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EqBlueprintsCollection extends CollectionResource
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
                'total_eq_blueprints' => $collection->count(),
                'total_active' => $collection->where('eq_blueprint_status', 'active')->count(),
                'total_inactive' => $collection->where('eq_blueprint_status', 'inactive')->count(),
                'total_draft' => $collection->where('eq_blueprint_status', 'draft')->count(),
            ],
            'categories' => $collection->groupBy('category_name')->map->count(),
            'subcategories' => $collection->groupBy('subcategory_name')->map->count(),
            'dates' => [
                'oldest' => $collection->min('created_at'),
                'newest' => $collection->max('created_at'),
            ]
        ];
    }
}
