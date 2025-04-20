@extends('pdf.portrait')

@section('content')
<div class="client-report">
    <!-- Client Information Section -->
    <div class="content">
        <!-- Client Profile Section -->
        <div class="section-header">
            <div class="section-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            </div>
            <div class="section-title">{{ __('Client Profile') }}</div>
        </div>
        
        <!-- Client Info Metrics -->
        <div class="profile-metrics">
            <div class="metric-item">
                <div class="metric-label">{{ __('Client ID') }}</div>
                <div class="metric-value">#{{ $client->client_id ?? __('N/A') }}</div>
            </div>
            <div class="metric-item">
                <div class="metric-label">{{ __('Established') }}</div>
                <div class="metric-value">{{ $client->client_since ?? __('N/A') }}</div>
            </div>
            <div class="metric-item">
                <div class="metric-label">{{ __('Status') }}</div>
                <div class="metric-badge status-{{ $client->client_status ?? 'inactive' }}">
                    {{ __(ucwords($client->client_status ?? __('N/A'))) }}
                </div>
            </div>
            <div class="metric-item">
                <div class="metric-label">{{ __('Client Type') }}</div>
                <div class="metric-badge client-type">
                    {{ __(ucwords($client->client_tier ?? __('N/A'))) }}
                </div>
            </div>
            <div class="metric-item">
                <div class="metric-label">{{ __('Tax ID') }}</div>
                <div class="metric-value">{{ $client->tax_id ?? __('N/A') }}</div>
            </div>
            <div class="metric-item">
                <div class="metric-label">{{ __('Activity Sector') }}</div>
                <div class="metric-value">{{ __(ucwords($client->industry ?? __('N/A'))) }}</div>
            </div>
        </div>

        <!-- Core Details Section -->
        <div class="section-header">
            <div class="section-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            </div>
            <div class="section-title">{{ __('Core Details') }}</div>
        </div>
        
        <div class="two-column-grid">
            <!-- Primary Information -->
            <div class="info-card">
                <div class="card-header">{{ __('Primary Information') }}</div>
                <div class="card-content">
                    <div class="info-row">
                        <div class="info-label">{{ __('Client') }}:</div>
                        <div class="info-value">{{ $client->legal_name ?? __('N/A') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">{{ __('Website') }}:</div>
                        <div class="info-value">{{ $client->website ?? __('N/A') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">{{ __('Communication') }}:</div>
                        <div class="info-value">{{ $client->primary_phone ?? __('N/A') }} | {{ $client->primary_email ?? __('N/A') }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Geographic Data -->
            <div class="info-card">
                <div class="card-header">{{ __('Geographic Data') }}</div>
                <div class="card-content">
                    <div class="info-row">
                        <div class="info-label">{{ __('Headquarters') }}:</div>
                        <div class="info-value">
                            @if($client->hq_address)
                                {{ strtoupper($client->hq_address) }}
                            @else
                                {{ __('N/A') }}
                            @endif
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">{{ __('Location') }}:</div>
                        <div class="info-value">
                            @if($client->hq_city || $client->hq_country)
                                {{ $client->hq_city ?? '' }}{{ ($client->hq_city && $client->hq_country) ? ', ' : '' }}{{ $client->hq_country ?? '' }}
                            @else
                                {{ __('N/A') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial & Legal Section -->
        <div class="section-header">
            <div class="section-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            </div>
            <div class="section-title">{{ __('Financial & Legal Details') }}</div>
        </div>
        
        <div class="two-column-grid">
            <!-- Corporate Structure -->
            <div class="info-card">
                <div class="card-header">{{ __('Corporate Structure') }}</div>
                <div class="card-content">
                    <div class="info-row">
                        <div class="info-label">{{ __('Legal Form') }}:</div>
                        <div class="info-value">{{ $client->legal_status ?? __('N/A') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">{{ __('Registered Capital') }}:</div>
                        <div class="info-value">
                            @if(isset($client->registered_capital))
                                {{ number_format($client->registered_capital, 0) }} {{ $client->preferred_currency ?? '' }}
                            @else
                                {{ __('N/A') }}
                            @endif
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">{{ __('RNE ID') }}:</div>
                        <div class="info-value">{{ $client->rne_id ?? __('N/A') }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Tax Information -->
            <div class="info-card">
                <div class="card-header">{{ __('Tax Information') }}</div>
                <div class="card-content">
                    <div class="info-row">
                        <div class="info-label">{{ __('VAT Exemption') }}:</div>
                        <div class="info-value">
                            @if($client->vat_exemption_certificate_id)
                                <span class="vat-badge vat-yes">{{ __('Yes') }}</span>
                            @else
                                <span class="vat-badge vat-no">{{ __('No') }}</span>
                            @endif
                        </div>
                    </div>
                    @if($client->vat_exemption_certificate_id)
                    <div class="info-row">
                        <div class="info-label">{{ __('Certification Period') }}:</div>
                        <div class="info-value">
                            {{ $client->vat_exemption_certificate_issue_date ?? '' }} - {{ $client->vat_exemption_certificate_expiration_date ?? '' }}
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">{{ __('Certification ID') }}:</div>
                        <div class="info-value">{{ $client->vat_exemption_certificate_id ?? __('N/A') }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Contact Persons Section -->
        @if(isset($contacts) && count($contacts) > 0)
        <div class="section-header">
            <div class="section-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
            <div class="section-title">{{ __('Contact Persons') }}</div>
        </div>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th width="25%">{{ __('Name') }}</th>
                    <th width="20%">{{ __('Position') }}</th>
                    <th width="15%">{{ __('Department') }}</th>
                    <th width="25%">{{ __('Contact') }}</th>
                    <th width="15%">{{ __('Status') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contacts as $contact)
                <tr>
                    <td>
                        @if($contact->first_name || $contact->last_name)
                            {{ $contact->first_name ?? '' }} {{ $contact->last_name ?? '' }}
                        @else
                            {{ __('N/A') }}
                        @endif
                    </td>
                    <td>{{ $contact->job_title ?? __('N/A') }}</td>
                    <td>{{ $contact->department ?? __('N/A') }}</td>
                    <td>
                        {{ $contact->email ?? '' }}
                        @if($contact->phone && $contact->email)
                            <br>
                        @endif
                        {{ $contact->phone ?? '' }}
                    </td>
                    <td>
                        <span class="contact-status status-{{ strtolower($contact->status ?? 'inactive') }}">
                            {{ $contact->status ?? __('N/A') }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <!-- Additional Addresses Section -->
        @if(isset($addresses) && count($addresses) > 0)
        <div class="section-header">
            <div class="section-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            </div>
            <div class="section-title">{{ __('Additional Addresses') }}</div>
        </div>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th width="15%">{{ __('Type') }}</th>
                    <th width="40%">{{ __('Address') }}</th>
                    <th width="25%">{{ __('City & Country') }}</th>
                    <th width="20%">{{ __('Contact') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($addresses as $address)
                <tr>
                    <td>{{ $address->type ?? __('N/A') }}</td>
                    <td>{{ $address->address_street ?? __('N/A') }}</td>
                    <td>
                        {{ $address->city ?? '' }}
                        @if($address->city && $address->country)
                            , 
                        @endif
                        {{ $address->country ?? '' }}
                        @if($address->postcode)
                            ({{ $address->postcode }})
                        @endif
                    </td>
                    <td>
                        @if($address->phone || $address->email)
                            {{ $address->phone ? $address->phone : '' }}
                            @if($address->phone && $address->email)
                                <br>
                            @endif
                            {{ $address->email ? $address->email : '' }}
                        @else
                            {{ __('N/A') }}
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <!-- Financial Information -->
        <div class="section-header">
            <div class="section-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            </div>
            <div class="section-title">{{ __('Financial Information') }}</div>
        </div>
        
        <div class="two-column-grid">
            <!-- Payment Terms -->
            <div class="info-card">
                <div class="card-header">{{ __('Payment Terms') }}</div>
                <div class="card-content">
                    <div class="info-row">
                        <div class="info-label">{{ __('Terms') }}:</div>
                        <div class="info-value">{{ $client->payment_terms ?? __('N/A') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">{{ __('Credit Limit') }}:</div>
                        <div class="info-value">
                            @if(isset($client->credit_limit))
                                {{ number_format($client->credit_limit, 2) }} {{ $client->preferred_currency ?? '' }}
                            @else
                                {{ __('N/A') }}
                            @endif
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">{{ __('Outstanding Balance') }}:</div>
                        <div class="info-value">
                            @if(isset($client->outstanding_balance))
                                {{ number_format($client->outstanding_balance, 2) }} {{ $client->preferred_currency ?? '' }}
                            @else
                                {{ __('N/A') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Banking Information -->
            <div class="info-card">
                <div class="card-header">{{ __('Banking Information') }}</div>
                <div class="card-content">
                    <div class="info-row">
                        <div class="info-label">{{ __('Bank') }}:</div>
                        <div class="info-value">{{ $client->bank ?? __('N/A') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">{{ __('Bank Agency') }}:</div>
                        <div class="info-value">{{ $client->bank_agence ?? __('N/A') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">{{ __('Bank RIB') }}:</div>
                        <div class="info-value">{{ $client->bank_rib ?? __('N/A') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Notes -->
        @if(isset($client->notes) && !empty($client->notes))
        <div class="section-header">
            <div class="section-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            </div>
            <div class="section-title">{{ __('Additional Notes') }}</div>
        </div>
        <div class="notes-container">
            {{ $client->notes }}
        </div>
        @endif
    </div>
</div>

<style>
/* Additional styles for client report */
.client-report {
    font-family: 'Arial', sans-serif;
}

/* Section Headers with Icons */
.section-header {
    display: flex;
    align-items: center;
    margin: 1.5rem 0 0.75rem;
    padding-bottom: 0.3rem;
    border-bottom: 1px solid #0066cc;
    color: #0066cc;
}

.section-icon {
    display: flex;
    align-items: center;
    margin-right: 0.5rem;
    color: #0066cc;
}

.section-title {
    font-size: 14px;
    font-weight: bold;
    color: #0066cc;
    margin: 0;
    padding: 0;
    border-bottom: none;
}

/* Client Profile Metrics */
.profile-metrics {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.metric-item {
    flex-basis: calc(33.333% - 0.5rem);
    background-color: #f8f9fa;
    border-radius: 4px;
    padding: 0.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.metric-label {
    font-size: 10px;
    color: #666;
    margin-bottom: 0.25rem;
}

.metric-value {
    font-size: 12px;
    font-weight: bold;
    color: #333;
}

.metric-badge {
    display: inline-block;
    padding: 0.15rem 0.5rem;
    border-radius: 12px;
    font-size: 11px;
    font-weight: bold;
    text-align: center;
}

/* Status badges */
.status-active {
    background-color: #28a745;
    color: white;
}

.status-pending {
    background-color: #ffc107;
    color: #212529;
}

.status-inactive {
    background-color: #6c757d;
    color: white;
}

.status-blocked {
    background-color: #dc3545;
    color: white;
}

.client-type {
    background-color: #0066cc;
    color: white;
}

/* Two column grid */
.two-column-grid {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

/* Info cards */
.info-card {
    flex: 1;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    overflow: hidden;
}

.card-header {
    background-color: #e9ecef;
    color: #495057;
    font-weight: bold;
    padding: 0.5rem;
    font-size: 11px;
}

.card-content {
    padding: 0.5rem;
}

.info-row {
    display: flex;
    margin-bottom: 0.4rem;
    font-size: 10px;
}

.info-label {
    flex: 0 0 40%;
    font-weight: bold;
    color: #495057;
}

.info-value {
    flex: 0 0 60%;
}

/* Tables */
.data-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1rem;
    font-size: 10px;
}

.data-table th {
    background-color: #0066cc;
    color: white;
    padding: 0.4rem 0.5rem;
    text-align: left;
}

.data-table td {
    padding: 0.4rem 0.5rem;
    border-bottom: 1px solid #dee2e6;
}

.data-table tr:nth-child(even) {
    background-color: #f8f9fa;
}

/* Contact status */
.contact-status {
    display: inline-block;
    padding: 0.15rem 0.4rem;
    border-radius: 10px;
    font-size: 9px;
    text-align: center;
}

/* VAT badge */
.vat-badge {
    display: inline-block;
    padding: 0.15rem 0.5rem;
    border-radius: 4px;
    font-weight: bold;
    font-size: 10px;
}

.vat-yes {
    background-color: #28a745;
    color: white;
}

.vat-no {
    background-color: #6c757d;
    color: white;
}

/* Notes container */
.notes-container {
    background-color: #f8f9fa;
    border-radius: 4px;
    padding: 0.75rem;
    margin-bottom: 1rem;
    font-size: 10px;
    border: 1px solid #dee2e6;
}

/* Content padding */
.content {
    margin: 1rem;
    padding-bottom: 70px; /* Increased padding to prevent footer overlap */
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .two-column-grid {
        flex-direction: column;
    }
    
    .metric-item {
        flex-basis: 100%;
    }
}
</style>
@endsection
