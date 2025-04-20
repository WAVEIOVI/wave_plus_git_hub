<?php

namespace App\Http\Controllers\Api\ProductAssetSuite\EqBlueprints;

use App\Models\EqBlueprint;
use Orion\Http\Controllers\Controller;
use Orion\Concerns\DisableAuthorization;
use Illuminate\Http\Request;
use App\Services\PdfService;

class EqBlueprintsController extends Controller
{
    use DisableAuthorization;

    /**
     * Fully-qualified model class name
     */
    protected $model = EqBlueprint::class;

    public function searchableBy(): array
    {
        return ['eq_blueprint_id', 'name', 'description','category_id', 'category_name', 'subcategory_id', 'subcategory_name','eq_blueprint_status' ];
    }

    public function filterableBy(): array   
    {
        return ['name','category_id', 'category_name', 'subcategory_id', 'subcategory_name'];
    }

    public function sortableBy(): array
    {
        return ['created_at', 'eq_blueprint_id', 'name', 'category_id', 'category_name',  'subcategory_id', 'subcategory_name', 'eq_blueprint_status'];
    }

    /**
     * Generate a PDF for equipment blueprint 
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function generateEqBlueprintPdf(Request $request)
    {
        // Set locale from header or request
        if ($request->hasHeader('X-Localization')) {
            app()->setLocale($request->header('X-Localization'));
        } elseif ($request->has('lang')) {
            app()->setLocale($request->input('lang'));
        }

        if ($request->has('id')) {
            // Single eq_blueprint PDF generation
            $eqBlueprint = EqBlueprint::query()
            ->findOrFail($request->input('id'));
            $data = [
                'eq_blueprint' => $eqBlueprint,
                'orientation' => $request->input('orientation', 'portrait'),
                'preparedBy' => auth()->user()->name,
                'title' => __('Equipment Blueprint Details') . ' #' . $eqBlueprint->eq_blueprint_id,
                'isRtl' => (bool)$request->header('X-Is-Rtl', false),
            ];
            // Use a dedicated view for single equipment blueprint pdf, e.g., pdf.eq_blueprint
            $pdf = (new PdfService())->generatePdf('pdf.eqBlueprint', $data, $data['orientation']);
        } else {
            // List of equipment blueprints pdf generation (existing logic)
            $query = EqBlueprint::query();
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

            $eqBlueprints = $query->get();

            $statistics = [
                'total_eq_blueprints' => $eqBlueprints->count(),
                'total_active_eq_blueprints' => $eqBlueprints->where('eq_blueprint_status', 'active')->count(),
                'total_draft_eq_blueprints' => $eqBlueprints->where('eq_blueprint_status', 'draft')->count(),
                'total_inactive_eq_blueprints' => $eqBlueprints->where('eq_blueprint_status', 'inactive')->count(),
            ];

            $data = [
                'isRtl' => (bool)$request->header('X-Is-Rtl', false),
                'title' => __('Equipment Blueprints Report'),
                'preparedBy' => auth()->user()->name,
                'eqBlueprints' => $eqBlueprints,
                'orientation' => $request->input('orientation', 'landscape'),
                'statistics' => $statistics,
                // Group data for charts/tables
                'categories' => $eqBlueprints->groupBy('category_name')->map->count(),
                'subcategories' => $eqBlueprints->groupBy('subcategory_name')->map->count(),
            ];

            $pdf = (new PdfService())->generatePdf('pdf.eqBlueprints', $data, $data['orientation']);
        }

        return response()->streamDownload(
            fn() => print($pdf),
            'equipment-blueprint-' . now()->format('Ymd-His') . '.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }
    
    // Keeping the original method for backward compatibility
    public function generateEqBlueprintsListPdf(Request $request)
    {
        return $this->generateEqBlueprintPdf($request);
    }
}
