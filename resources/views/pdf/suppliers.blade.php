@extends('pdf.landscape')

@section('content')
    <!-- Key Metrics Section -->
    <div class="section-title">{{ __('Key Metrics') }}</div>
    <div class="metrics-container">
        <div class="metrics-box">
            <div class="metric-title">{{ __('Total Suppliers') }}</div>
            <div class="metric-value">{{ $statistics['total_suppliers'] }}</div>
        </div>
        <div class="metrics-box">
            <div class="metric-title">{{ __('Active') }}</div>
            <div class="metric-value">{{ $statistics['total_active_suppliers'] }}</div>
        </div>
        <div class="metrics-box">
            <div class="metric-title">{{ __('Pending') }}</div>
            <div class="metric-value">{{ $statistics['total_pending_suppliers'] }}</div>
        </div>
        <div class="metrics-box">
            <div class="metric-title">{{ __('Blocked') }}</div>
            <div class="metric-value">{{ $statistics['total_blocked_suppliers'] }}</div>
        </div>
    </div>

    <!-- Data Visualization Section - Optimized for space -->
    <div class="section-title">{{ __('Supplier Distribution') }}</div>
    <div class="distribution-container">
        <div class="distribution-section">
            <h4>{{ __('By Tier') }}</h4>
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="80%">{{ __('Tier') }}</th>
                        <th width="20%">{{ __('Count') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tiers as $tier => $count)
                        <tr>
                            <td>{{ __(ucwords($tier)) }}</td>
                            <td>{{ $count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="distribution-section">
            <h4>{{ __('By Industry') }}</h4>
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="80%">{{ __('Industry') }}</th>
                        <th width="20%">{{ __('Count') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($industries as $industry => $count)
                        <tr>
                            <td>{{ __(ucwords($industry)) }}</td>
                            <td>{{ $count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Supplier List Section - Optimized table with fixed column widths -->
    <div class="section-title">{{ __('Supplier List') }}</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">{{ __('ID') }}</th>
                <th width="20%">{{ __('Supplier Name') }}</th>
                <th width="10%">{{ __('Tier') }}</th>
                <th width="15%">{{ __('Industry') }}</th>
                <th width="10%">{{ __('Status') }}</th>
                <th width="20%">{{ __('Address') }}</th>
                <th width="20%">{{ __('Contact') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($suppliers as $supplier)
                <tr>
                    <td>#{{ $supplier->supplier_id }}</td>
                    <td>{{ Str::limit($supplier->legal_name, 30) }}</td>
                    <td>{{ __(ucwords($supplier->supplier_tier)) }}</td>
                    <td>{{ __(ucwords($supplier->industry)) }}</td>
                    <td>
                        <span class="status-badge status-{{ $supplier->supplier_status }}">
                            {{ __(ucwords($supplier->supplier_status)) }}
                        </span>
                    </td>
                    <td>{{ Str::limit($supplier->hq_address ?? __('N/A'), 25) }}</td>
                    <td>
                        {{ Str::limit($supplier->primary_phone ?? __('N/A'), 15) }}
                        {{ $supplier->primary_email ? '/ ' . Str::limit($supplier->primary_email, 15) : '' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
