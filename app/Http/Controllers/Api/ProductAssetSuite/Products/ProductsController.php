<?php

namespace App\Http\Controllers\Api\ProductAssetSuite\Products;

use App\Models\Product;
use Orion\Http\Controllers\Controller;
use Orion\Concerns\DisableAuthorization;
use Illuminate\Http\Request;
use App\Services\PdfService;

class ProductsController extends Controller
{
    use DisableAuthorization;

    /**
     * Fully-qualified model class name
     */
    protected $model = Product::class;

    public function searchableBy(): array
    {
        return ['product_id', 'name', 'description', 'purchase_price', 'base_price', 'discount_rate', 'sale_price','tax_rate','tax_sale','stock_quantity',
        'dimensions','certifications','warranty_info', 'category_name', 'subcategory_id', 'subcategory_name', 'brand_name','product_type' ];
    }

    public function filterableBy(): array   
    {
        return ['product_type','category_id', 'category_name', 'subcategory_id', 'subcategory_name'];
    }

    public function sortableBy(): array
    {
        return ['created_at', 'product_id', 'name', 'category_name', 'subcategory_name', 'brand_name','product_type'];
    }

    public function generateProductsListPdf(Request $request)
{
    // Set locale from header or request
    if ($request->hasHeader('X-Localization')) {
        app()->setLocale($request->header('X-Localization'));
    } elseif ($request->has('lang')) {
        app()->setLocale($request->input('lang'));
    }

    if ($request->has('id')) {
        // Single product PDF generation
        $product = Product::query()
        ->findOrFail($request->input('id'));
        $data = [
            'product' => $product,
            'orientation' => $request->input('orientation', 'portrait'),
            'preparedBy' => auth()->user()->name,
            'title' => __('Product Details') . ' #' . $product->product_id,
            'isRtl' => (bool)$request->header('X-Is-Rtl', false),
        ];
        // Use a dedicated view for single product pdf, e.g., pdf.product
        $pdf = (new PdfService())->generatePdf('pdf.product', $data, $data['orientation']);
    } else {
        // List of products pdf generation (existing logic)
        $query = Product::query();
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

        $products = $query->get();

        $statistics = [
            'total_products' => $products->count(),
            'total_active_products' => $products->where('product_status', 'active')->count(),
            'total_draft_products' => $products->where('product_status', 'draft')->count(),
            'total_inactive_products' => $products->where('product_status', 'inactive')->count(),
        ];

        $data = [
            'isRtl' => (bool)$request->header('X-Is-Rtl', false),
            'title' => __('Products Report'),
            'preparedBy' => auth()->user()->name,
            'products' => $products,
            'orientation' => $request->input('orientation', 'landscape'),
            'statistics' => $statistics,
            // Group data for charts/tables
            'types' => $products->groupBy('product_type')->map->count(),
            'categories' => $products->groupBy('category_name')->map->count(),
            'subcategories' => $products->groupBy('subcategory_name')->map->count(),
        ];

        $pdf = (new PdfService())->generatePdf('pdf.products', $data, $data['orientation']);
    }

    return response()->streamDownload(
        fn() => print($pdf),
        'products-' . now()->format('Ymd-His') . '.pdf',
        ['Content-Type' => 'application/pdf']
    );
}
    
}
