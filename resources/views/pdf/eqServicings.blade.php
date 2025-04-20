@extends('pdf.landscape')

@section('content')
    <!-- Key Metrics Section -->
    <div class="section-title">{{ __('Key Metrics') }}</div>
    <div class="metrics-container">
        <div class="metrics-box">
            <div class="metric-title">{{ __('Total EqServicings') }}</div>
            <div class="metric-value">{{ $statistics['total_eq_servicings'] }}</div>
        </div>
        <div class="metrics-box">
            <div class="metric-title">{{ __('Active') }}</div>
            <div class="metric-value">{{ $statistics['total_active_eq_servicings'] }}</div>
        </div>
        <div class="metrics-box">
            <div class="metric-title">{{ __('Draft') }}</div>
            <div class="metric-value">{{ $statistics['total_draft_eq_servicings'] }}</div>
        </div>
        <div class="metrics-box">
            <div class="metric-title">{{ __('Inactive') }}</div>
            <div class="metric-value">{{ $statistics['total_inactive_eq_servicings'] }}</div>
        </div>
    </div>

    <!-- Data Visualization Section - Optimized for space -->
    <div class="section-title">{{ __('EqServicing Distribution') }}</div>
    <div class="distribution-container">
        <div class="distribution-section">
            <h4>{{ __('By Type') }}</h4>
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="80%">{{ __('Type') }}</th>
                        <th width="20%">{{ __('Count') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($types as $type => $count)
                        <tr>
                            <td>{{ __(ucwords($type)) }}</td>
                            <td>{{ $count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="distribution-section">
            <h4>{{ __('By Category') }}</h4>
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="80%">{{ __('Category') }}</th>
                        <th width="20%">{{ __('Count') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category => $count)
                        <tr>
                            <td>{{ __(ucwords($category)) }}</td>
                            <td>{{ $count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- EqServicing List Section - Optimized table with fixed column widths -->
    <div class="section-title">{{ __('EqServicing List') }}</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">{{ __('ID') }}</th>
                <th width="20%">{{ __('Name') }}</th>
                <th width="10%">{{ __('Type') }}</th>
                <th width="15%">{{ __('Category') }}</th>
                <th width="10%">{{ __('Status') }}</th>
                <th width="20%">{{ __('Price') }}</th>
                <th width="20%">{{ __('Stock') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($eqServicings as $eqServicing)
                <tr>
                    <td>#{{ $eqServicing->eq_servicing_id }}</td>
                    <td>{{ Str::limit($eqServicing->name, 30) }}</td>
                    <td>{{ __(ucwords($eqServicing->eq_servicing_type)) }}</td>
                    <td>{{ __(ucwords($eqServicing->category_name)) }}</td>
                    <td>
                        <span class="status-badge status-{{ $eqServicing->eq_servicing_status }}">
                            {{ __(ucwords($eqServicing->eq_servicing_status)) }}
                        </span>
                    </td>
                    <td>
                        @if(isset($eqServicing->sale_price))
                            {{ number_format($eqServicing->sale_price, 3) }}
                        @else
                            {{ __('N/A') }}
                        @endif
                    </td>
                    <td>{{ $eqServicing->stock_quantity ?? __('N/A') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
