<?php

namespace App\Http\Controllers\Api\BusinessPartner\Suppliers;

use App\Models\Supplier;
use Orion\Http\Controllers\Controller;
use Orion\Concerns\DisableAuthorization;
use Illuminate\Http\Request;
use App\Services\PdfService;

class SuppliersController extends Controller
{
    use DisableAuthorization;

    /**
     * Fully-qualified model class name
     */
    protected $model = Supplier::class;

    public function searchableBy(): array
    {
        return ['supplier_id', 'commercial_name', 'legal_name', 'primary_email', 'primary_phone', 'tax_id', 'rne_id'];
    }

    public function filterableBy(): array
    {
        return ['supplier_tier', 'industry', 'supplier_status'];
    }

    public function sortableBy(): array
    {
        return ['created_at', 'legal_name', 'supplier_tier', 'industry', 'supplier_status'];
    }



    public function generateSuppliersListPdf(Request $request)
{
    // Set locale from header or request
    if ($request->hasHeader('X-Localization')) {
        app()->setLocale($request->header('X-Localization'));
    } elseif ($request->has('lang')) {
        app()->setLocale($request->input('lang'));
    }

    if ($request->has('id')) {
        // Single supplier PDF generation
        $supplier = Supplier::with(['addresses', 'contacts'])
        ->findOrFail($request->input('id'));
        $data = [
            'supplier' => $supplier,
            'addresses' => $supplier->addresses,
            'contacts' => $supplier->contacts,
            'orientation' => $request->input('orientation', 'portrait'),
            'preparedBy' => auth()->user()->name,
            'title' => __('Supplier Report') . ' #' . $supplier->supplier_id,
            'isRtl' => (bool)$request->header('X-Is-Rtl', false),
        ];
        // Use a dedicated view for single supplier pdf, e.g., pdf.supplier
        $pdf = (new PdfService())->generatePdf('pdf.supplier', $data, $data['orientation']);
    } else {
        // List of suppliers pdf generation (existing logic)
        $query = Supplier::query();
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

        $suppliers = $query->get();

        $statistics = [
            'total_suppliers' => $suppliers->count(),
            'total_active_suppliers' => $suppliers->where('supplier_status', 'active')->count(),
            'total_pending_suppliers' => $suppliers->where('supplier_status', 'pending')->count(),
            'total_inactive_suppliers' => $suppliers->where('supplier_status', 'inactive')->count(),
            'total_blocked_suppliers' => $suppliers->where('supplier_status', 'blocked')->count(),
        ];

        $data = [
            'isRtl' => (bool)$request->header('X-Is-Rtl', false),
            'title' => __('Suppliers Report'),
            'preparedBy' => auth()->user()->name,
            'suppliers' => $suppliers,
            'orientation' => $request->input('orientation', 'landscape'),
            'statistics' => $statistics,
            // Group data for charts/tables
            'tiers' => $suppliers->groupBy('supplier_tier')->map->count(),
            'industries' => $suppliers->groupBy('industry')->map->count(),
            'status' => $suppliers->groupBy('supplier_status')->map->count(),
        ];

        $pdf = (new PdfService())->generatePdf('pdf.suppliers', $data, $data['orientation']);
    }

    return response()->streamDownload(
        fn() => print($pdf),
        'suppliers-' . now()->format('Ymd-His') . '.pdf',
        ['Content-Type' => 'application/pdf']
    );
}
    
}
