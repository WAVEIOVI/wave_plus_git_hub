@extends('pdf.portrait')

@section('content')
<div class="supplier-report">
    <!-- Supplier Information Section -->
    <div class="content">
        <!-- Supplier Profile Section -->
        <div class="section-title">{{ __('Supplier Profile') }}</div>
        
        <!-- Key Info Metrics -->
        <div class="metrics-container">
            <div class="metrics-box">
                <div class="metric-title">{{ __('Supplier ID') }}</div>
                <div class="metric-value">#{{ $supplier->supplier_id ?? __('N/A') }}</div>
            </div>
            <div class="metrics-box">
                <div class="metric-title">{{ __('Status') }}</div>
                <div class="metric-value status-{{ $supplier->supplier_status ?? 'inactive' }}">
                    {{ __(ucwords($supplier->supplier_status ?? __('N/A'))) }}
                </div>
            </div>
            <div class="metrics-box">
                <div class="metric-title">{{ __('Tier') }}</div>
                <div class="metric-value">{{ __(ucwords($supplier->supplier_tier ?? __('N/A'))) }}</div>
            </div>
            <div class="metrics-box">
                <div class="metric-title">{{ __('Industry') }}</div>
                <div class="metric-value">{{ __(ucwords($supplier->industry ?? __('N/A'))) }}</div>
            </div>
        </div>

        <!-- Core Information -->
        <div class="section-title">{{ __('Core Information') }}</div>
        <div class="distribution-container">
            <!-- Basic Info -->
            <div class="distribution-section">
                <table class="data-table">
                    <tr>
                        <th>{{ __('Legal Name') }}</th>
                        <td>{{ $supplier->legal_name ?? __('N/A') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Commercial Name') }}</th>
                        <td>{{ $supplier->commercial_name ?? __('N/A') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Company Size') }}</th>
                        <td>{{ $supplier->company_size ?? __('N/A') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Legal Status') }}</th>
                        <td>{{ $supplier->legal_status ?? __('N/A') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Supplier Since') }}</th>
                        <td>{{ $supplier->supplier_since ?? __('N/A') }}</td>
                    </tr>
                </table>
            </div>

            <!-- Legal & Identification -->
            <div class="distribution-section">
                <table class="data-table">
                    <tr>
                        <th>{{ __('Tax ID') }}</th>
                        <td>{{ $supplier->tax_id ?? __('N/A') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('RNE ID') }}</th>
                        <td>{{ $supplier->rne_id ?? __('N/A') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Founding Year') }}</th>
                        <td>{{ $supplier->founding_year ?? __('N/A') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Website') }}</th>
                        <td>{{ $supplier->website ?? __('N/A') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Certifications') }}</th>
                        <td>
                            @if($supplier->certifications)
                                @php
                                    $certifications = json_decode($supplier->certifications, true);
                                @endphp
                                @if(is_array($certifications) && count($certifications) > 0)
                                    <ul style="margin: 0; padding-left: 15px;">
                                        @foreach($certifications as $cert)
                                            <li>{{ $cert }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    {{ $supplier->certifications }}
                                @endif
                            @else
                                {{ __('N/A') }}
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Primary Contact Information -->
        <div class="section-title">{{ __('Primary Contact Information') }}</div>
        <div class="distribution-container">
            <!-- Contact Info -->
            <div class="distribution-section">
                <table class="data-table">
                    <tr>
                        <th>{{ __('Headquarters') }}</th>
                        <td>
                            @if($supplier->hq_address || $supplier->hq_city || $supplier->hq_postcode || $supplier->hq_country)
                                {{ $supplier->hq_address ?? '' }}{{ $supplier->hq_address ? ', ' : '' }}
                                {{ $supplier->hq_postcode ?? '' }}{{ $supplier->hq_postcode ? ' ' : '' }}
                                {{ $supplier->hq_city ?? '' }}{{ ($supplier->hq_city && $supplier->hq_country) ? ', ' : '' }}
                                {{ $supplier->hq_country ?? '' }}
                            @else
                                {{ __('N/A') }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('Primary Phone') }}</th>
                        <td>{{ $supplier->primary_phone ?? __('N/A') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Primary Email') }}</th>
                        <td>{{ $supplier->primary_email ?? __('N/A') }}</td>
                    </tr>
                </table>
            </div>

            <!-- Account Management -->
            <div class="distribution-section">
                <table class="data-table">
                    <tr>
                        <th>{{ __('Account Manager') }}</th>
                        <td>{{ $supplier->account_manager ?? __('N/A') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Satisfaction Score') }}</th>
                        <td>
                            @if(isset($supplier->satisfaction_score))
                                {{ $supplier->satisfaction_score }}
                            @else
                                {{ __('N/A') }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('Churn Risk') }}</th>
                        <td>
                            @if(isset($supplier->churn_risk))
                                {{ $supplier->churn_risk }}
                            @else
                                {{ __('N/A') }}
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Financial Information -->
        <div class="section-title">{{ __('Financial Information') }}</div>
        <div class="distribution-container">
            <!-- Payment Terms -->
            <div class="distribution-section">
                <table class="data-table">
                    <tr>
                        <th>{{ __('Payment Terms') }}</th>
                        <td>{{ $supplier->payment_terms ?? __('N/A') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Credit Limit') }}</th>
                        <td>
                            @if(isset($supplier->credit_limit))
                                {{ number_format($supplier->credit_limit, 2) }} {{ $supplier->preferred_currency ?? '' }}
                            @else
                                {{ __('N/A') }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('Outstanding Balance') }}</th>
                        <td>
                            @if(isset($supplier->outstanding_balance))
                                {{ number_format($supplier->outstanding_balance, 2) }} {{ $supplier->preferred_currency ?? '' }}
                            @else
                                {{ __('N/A') }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('Last Mission Date') }}</th>
                        <td>{{ $supplier->last_mission_date ?? __('N/A') }}</td>
                    </tr>
                </table>
            </div>

            <!-- Banking Information -->
            <div class="distribution-section">
                <table class="data-table">
                    <tr>
                        <th>{{ __('Bank') }}</th>
                        <td>{{ $supplier->bank ?? __('N/A') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Bank Agency') }}</th>
                        <td>{{ $supplier->bank_agence ?? __('N/A') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Bank RIB') }}</th>
                        <td>{{ $supplier->bank_rib ?? __('N/A') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- VAT Exemption Details -->
        @if($supplier->vat_exemption_certificate_id || $supplier->vat_exemption_certificate_issue_date || $supplier->vat_exemption_certificate_expiration_date)
        <div class="section-title">{{ __('VAT Exemption Details') }}</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="30%">{{ __('Certificate ID') }}</th>
                    <th width="35%">{{ __('Issue Date') }}</th>
                    <th width="35%">{{ __('Expiration Date') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $supplier->vat_exemption_certificate_id ?? __('N/A') }}</td>
                    <td>{{ $supplier->vat_exemption_certificate_issue_date ?? __('N/A') }}</td>
                    <td>{{ $supplier->vat_exemption_certificate_expiration_date ?? __('N/A') }}</td>
                </tr>
            </tbody>
        </table>
        @endif

        <!-- Addresses Section -->
        @if(isset($addresses) && count($addresses) > 0)
        <div class="section-title">{{ __('Additional Addresses') }}</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="12%">{{ __('Type') }}</th>
                    <th width="30%">{{ __('Address') }}</th>
                    <th width="15%">{{ __('City') }}</th>
                    <th width="10%">{{ __('Postcode') }}</th>
                    <th width="12%">{{ __('Country') }}</th>
                    <th width="21%">{{ __('Contact') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($addresses as $address)
                <tr>
                    <td>{{ $address->type ?? __('N/A') }}</td>
                    <td>{{ $address->address_street ?? __('N/A') }}</td>
                    <td>{{ $address->city ?? __('N/A') }}</td>
                    <td>{{ $address->postcode ?? __('N/A') }}</td>
                    <td>{{ $address->country ?? __('N/A') }}</td>
                    <td>
                        @if($address->phone || $address->email)
                            {{ $address->phone ? $address->phone . ' / ' : '' }}
                            {{ $address->email ?: '' }}
                        @else
                            {{ __('N/A') }}
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <!-- Contacts Section -->
        @if(isset($contacts) && count($contacts) > 0)
        <div class="section-title">{{ __('Contact Persons') }}</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="20%">{{ __('Name') }}</th>
                    <th width="15%">{{ __('Job Title') }}</th>
                    <th width="15%">{{ __('Department') }}</th>
                    <th width="15%">{{ __('Phone') }}</th>
                    <th width="25%">{{ __('Email') }}</th>
                    <th width="10%">{{ __('Status') }}</th>
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
                    <td>{{ $contact->phone ?? __('N/A') }}</td>
                    <td>{{ $contact->email ?? __('N/A') }}</td>
                    <td>{{ $contact->status ?? __('N/A') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <!-- Additional Notes -->
        @if(isset($supplier->notes) && !empty($supplier->notes))
        <div class="section-title">{{ __('Additional Notes') }}</div>
        <div class="notes-container" style="padding: 0.5rem; background-color: var(--light-bg); border-radius: 4px;">
            {{ $supplier->notes }}
        </div>
        @endif
    </div>
</div>
@endsection
