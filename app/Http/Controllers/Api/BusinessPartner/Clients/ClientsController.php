<?php

namespace App\Http\Controllers\Api\BusinessPartner\Clients;

use App\Models\Client;
use Orion\Http\Controllers\Controller;
use Orion\Concerns\DisableAuthorization;
use Illuminate\Http\Request;
use App\Services\PdfService;

class ClientsController extends Controller
{
    use DisableAuthorization;

    /**
     * Fully-qualified model class name
     */
    protected $model = Client::class;

    public function searchableBy(): array
    {
        return ['client_id', 'commercial_name', 'legal_name', 'primary_email', 'primary_phone', 'tax_id', 'rne_id'];
    }

    public function filterableBy(): array
    {
        return ['client_tier', 'industry', 'client_status'];
    }

    public function sortableBy(): array
    {
        return ['created_at', 'legal_name', 'client_tier', 'industry', 'client_status'];
    }



    public function generateClientsListPdf(Request $request)
{
    // Set locale from header or request
    if ($request->hasHeader('X-Localization')) {
        app()->setLocale($request->header('X-Localization'));
    } elseif ($request->has('lang')) {
        app()->setLocale($request->input('lang'));
    }

    if ($request->has('id')) {
        // Single client PDF generation
        $client = Client::with(['addresses', 'contacts'])
        ->findOrFail($request->input('id'));
        $data = [
            'client' => $client,
            'addresses' => $client->addresses,
            'contacts' => $client->contacts,
            'orientation' => $request->input('orientation', 'portrait'),
            'preparedBy' => auth()->user()->name,
            'title' => __('Client Report') . ' #' . $client->client_id,
            'isRtl' => (bool)$request->header('X-Is-Rtl', false),
        ];
        // Use a dedicated view for single client pdf, e.g., pdf.client
        $pdf = (new PdfService())->generatePdf('pdf.client', $data, $data['orientation']);
    } else {
        // List of clients pdf generation (existing logic)
        $query = Client::query();
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

        $clients = $query->get();

        $statistics = [
            'total_clients' => $clients->count(),
            'total_active_clients' => $clients->where('client_status', 'active')->count(),
            'total_pending_clients' => $clients->where('client_status', 'pending')->count(),
            'total_inactive_clients' => $clients->where('client_status', 'inactive')->count(),
            'total_blocked_clients' => $clients->where('client_status', 'blocked')->count(),
        ];

        $data = [
            'isRtl' => (bool)$request->header('X-Is-Rtl', false),
            'title' => __('Clients Report'),
            'preparedBy' => auth()->user()->name,
            'clients' => $clients,
            'orientation' => $request->input('orientation', 'landscape'),
            'statistics' => $statistics,
            // Group data for charts/tables
            'tiers' => $clients->groupBy('client_tier')->map->count(),
            'industries' => $clients->groupBy('industry')->map->count(),
            'status' => $clients->groupBy('client_status')->map->count(),
        ];

        $pdf = (new PdfService())->generatePdf('pdf.clients', $data, $data['orientation']);
    }

    return response()->streamDownload(
        fn() => print($pdf),
        'clients-' . now()->format('Ymd-His') . '.pdf',
        ['Content-Type' => 'application/pdf']
    );
}
    
}
