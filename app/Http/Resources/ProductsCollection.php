<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Orion\Http\Resources\CollectionResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductsCollection extends CollectionResource
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
                'total_products' => $collection->count(),
                'total_physical_products' => $collection->where('product_type', 'physical')->count(),
                'total_service_products' => $collection->where('product_type', 'service')->count(),
                'total_digital_products' => $collection->where('product_type', 'digital')->count(),
            ],
            'types' => $collection->groupBy('product_type')->map->count(),
            'categories' => $collection->groupBy('category_name')->map->count(),
            'subcategories' => $collection->groupBy('subcategory_name')->map->count(),
            'dates' => [
                'oldest' => $collection->min('created_at'),
                'newest' => $collection->max('created_at'),
            ]
        ];
    }
}
