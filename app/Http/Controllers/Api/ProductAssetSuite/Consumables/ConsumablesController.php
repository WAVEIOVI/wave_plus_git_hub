<?php

namespace App\Http\Controllers\Api\ProductAssetSuite\Consumables;

use App\Models\Consumable;
use Orion\Http\Controllers\Controller;
use Orion\Concerns\DisableAuthorization;
use Illuminate\Http\Request;
use App\Services\PdfService;

class ConsumablesController extends Controller
{
    use DisableAuthorization;

    /**
     * Fully-qualified model class name
     */
    protected $model = Consumable::class;

    public function searchableBy(): array
    {
        return ['consumable_id', 'name', 'description', 'purchase_price', 'base_price', 'discount_rate', 'sale_price','tax_rate','tax_sale','stock_quantity',
        'dimensions','certifications','warranty_info', 'category_name', 'subcategory_id', 'subcategory_name', 'brand_name','consumable_type' ];
    }

    public function filterableBy(): array   
    {
        return ['consumable_type','category_id', 'category_name', 'subcategory_id', 'subcategory_name'];
    }

    public function sortableBy(): array
    {
        return ['created_at', 'consumable_id', 'name', 'category_name', 'subcategory_name', 'brand_name','consumable_type'];
    }

    public function generateConsumablesListPdf(Request $request)
{
    // Set locale from header or request
    if ($request->hasHeader('X-Localization')) {
        app()->setLocale($request->header('X-Localization'));
    } elseif ($request->has('lang')) {
        app()->setLocale($request->input('lang'));
    }

    if ($request->has('id')) {
        // Single consumable PDF generation
        $consumable = Consumable::query()
        ->findOrFail($request->input('id'));
        $data = [
            'consumable' => $consumable,
            'orientation' => $request->input('orientation', 'portrait'),
            'preparedBy' => auth()->user()->name,
            'title' => __('Consumable Details') . ' #' . $consumable->consumable_id,
            'isRtl' => (bool)$request->header('X-Is-Rtl', false),
        ];
        // Use a dedicated view for single consumable pdf, e.g., pdf.consumable
        $pdf = (new PdfService())->generatePdf('pdf.consumable', $data, $data['orientation']);
    } else {
        // List of consumables pdf generation (existing logic)
        $query = Consumable::query();
        // Handle search parameter
        $searchTerm = $request->input('search', '');
        if (!empty($searchTerm)) {
            $query->where(function ($q) use ($searchTerm) {
                foreach ($this->searchableBy() as $column) {
                    $q->orWhere($column, 'like', "%{$searchTerm}%");
                }
            });
        }

        // Safely handle filters
        foreach ($request->input('filters', []) as $filter) {
            if (isset($filter['field'], $filter['operator'], $filter['value'])) {
                $query->where($filter['field'], $filter['operator'], $filter['value']);
            }
        }

        // Safely handle sorting
        foreach ($request->input('sort', []) as $sort) {
            if (isset($sort['field'], $sort['direction'])) {
                $query->orderBy($sort['field'], $sort['direction']);
            }
        }

        $consumables = $query->get();

        $statistics = [
            'total_consumables' => $consumables->count(),
            'total_active_consumables' => $consumables->where('consumable_status', 'active')->count(),
            'total_draft_consumables' => $consumables->where('consumable_status', 'draft')->count(),
            'total_inactive_consumables' => $consumables->where('consumable_status', 'inactive')->count(),
        ];

        $data = [
            'isRtl' => (bool)$request->header('X-Is-Rtl', false),
            'title' => __('Consumables Report'),
            'preparedBy' => auth()->user()->name,
            'consumables' => $consumables,
            'orientation' => $request->input('orientation', 'landscape'),
            'statistics' => $statistics,
            // Group data for charts/tables
            'types' => $consumables->groupBy('consumable_type')->map->count(),
            'categories' => $consumables->groupBy('category_name')->map->count(),
            'subcategories' => $consumables->groupBy('subcategory_name')->map->count(),
        ];

        $pdf = (new PdfService())->generatePdf('pdf.consumables', $data, $data['orientation']);
    }

    return response()->streamDownload(
        fn() => print($pdf),
        'consumables-' . now()->format('Ymd-His') . '.pdf',
        ['Content-Type' => 'application/pdf']
    );
}
    
}
