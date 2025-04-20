<?php

namespace App\Http\Controllers\Api\ProductAssetSuite\EqServicings;

use App\Models\EqServicing;
use Orion\Http\Controllers\Controller;
use Orion\Concerns\DisableAuthorization;
use Illuminate\Http\Request;
use App\Services\PdfService;

class EqServicingsController extends Controller
{
    use DisableAuthorization;

    /**
     * Fully-qualified model class name
     */
    protected $model = EqServicing::class;

    public function searchableBy(): array
    {
        return ['eq_servicing_id', 'name', 'description', 'purchase_price', 'base_price', 'discount_rate', 'sale_price','tax_rate','tax_sale',
        'certifications','warranty_info', 'category_name', 'subcategory_id', 'subcategory_name', 'brand_name','eq_servicing_type' ];
    }

    public function filterableBy(): array   
    {
        return ['eq_servicing_type','category_id', 'category_name', 'subcategory_id', 'subcategory_name'];
    }

    public function sortableBy(): array
    {
        return ['created_at', 'eq_servicing_id', 'name', 'category_name', 'subcategory_name', 'brand_name','eq_servicing_type'];
    }

    /**
     * Generate a PDF for equipment servicing 
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function generateEqServicingPdf(Request $request)
    {
        // Set locale from header or request
        if ($request->hasHeader('X-Localization')) {
            app()->setLocale($request->header('X-Localization'));
        } elseif ($request->has('lang')) {
            app()->setLocale($request->input('lang'));
        }

        if ($request->has('id')) {
            // Single eq_servicing PDF generation
            $eqServicing = EqServicing::query()
            ->findOrFail($request->input('id'));
            $data = [
                'eq_servicing' => $eqServicing,
                'orientation' => $request->input('orientation', 'portrait'),
                'preparedBy' => auth()->user()->name,
                'title' => __('Equipment Servicing Details') . ' #' . $eqServicing->eq_servicing_id,
                'isRtl' => (bool)$request->header('X-Is-Rtl', false),
            ];
            // Use a dedicated view for single equipment servicing pdf, e.g., pdf.eq_servicing
            $pdf = (new PdfService())->generatePdf('pdf.eqServicing', $data, $data['orientation']);
        } else {
            // List of equipment servicings pdf generation (existing logic)
            $query = EqServicing::query();
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

            $eqServicings = $query->get();

            $statistics = [
                'total_eq_servicings' => $eqServicings->count(),
                'total_active_eq_servicings' => $eqServicings->where('eq_servicing_status', 'active')->count(),
                'total_draft_eq_servicings' => $eqServicings->where('eq_servicing_status', 'draft')->count(),
                'total_inactive_eq_servicings' => $eqServicings->where('eq_servicing_status', 'inactive')->count(),
            ];

            $data = [
                'isRtl' => (bool)$request->header('X-Is-Rtl', false),
                'title' => __('Equipment Servicings Report'),
                'preparedBy' => auth()->user()->name,
                'eqServicings' => $eqServicings,
                'orientation' => $request->input('orientation', 'landscape'),
                'statistics' => $statistics,
                // Group data for charts/tables
                'types' => $eqServicings->groupBy('eq_servicing_type')->map->count(),
                'categories' => $eqServicings->groupBy('category_name')->map->count(),
                'subcategories' => $eqServicings->groupBy('subcategory_name')->map->count(),
            ];

            $pdf = (new PdfService())->generatePdf('pdf.eqServicings', $data, $data['orientation']);
        }

        return response()->streamDownload(
            fn() => print($pdf),
            'equipment-servicing-' . now()->format('Ymd-His') . '.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }
    
    // Keeping the original method for backward compatibility
    public function generateEqServicingsListPdf(Request $request)
    {
        return $this->generateEqServicingPdf($request);
    }
}
