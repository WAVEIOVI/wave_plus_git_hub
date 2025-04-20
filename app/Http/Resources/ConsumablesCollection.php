<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Orion\Http\Resources\CollectionResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ConsumablesCollection extends CollectionResource
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
                'total_consumables' => $collection->count(),
                'total_agents_and_disposables' => $collection->where('consumable_type', 'agents and disposables')->count(),
                'total_replacement_parts' => $collection->where('consumable_type', 'replacement parts')->count(),
                'total_safety_signage' => $collection->where('consumable_type', 'safety signage')->count(),
                'total_accessories' => $collection->where('consumable_type', 'accessories')->count(),
            ],
            'types' => $collection->groupBy('consumable_type')->map->count(),
            'categories' => $collection->groupBy('category_name')->map->count(),
            'subcategories' => $collection->groupBy('subcategory_name')->map->count(),
            'dates' => [
                'oldest' => $collection->min('created_at'),
                'newest' => $collection->max('created_at'),
            ]
        ];
    }
}
