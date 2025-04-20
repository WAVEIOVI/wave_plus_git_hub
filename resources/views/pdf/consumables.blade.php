@extends('pdf.landscape')

@section('content')
    <!-- Key Metrics Section -->
    <div class="section-title">{{ __('Key Metrics') }}</div>
    <div class="metrics-container">
        <div class="metrics-box">
            <div class="metric-title">{{ __('Total Consumables') }}</div>
            <div class="metric-value">{{ $statistics['total_consumables'] }}</div>
        </div>
        <div class="metrics-box">
            <div class="metric-title">{{ __('Active') }}</div>
            <div class="metric-value">{{ $statistics['total_active_consumables'] }}</div>
        </div>
        <div class="metrics-box">
            <div class="metric-title">{{ __('Draft') }}</div>
            <div class="metric-value">{{ $statistics['total_draft_consumables'] }}</div>
        </div>
        <div class="metrics-box">
            <div class="metric-title">{{ __('Inactive') }}</div>
            <div class="metric-value">{{ $statistics['total_inactive_consumables'] }}</div>
        </div>
    </div>

    <!-- Data Visualization Section - Optimized for space -->
    <div class="section-title">{{ __('Consumable Distribution') }}</div>
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

    <!-- Consumable List Section - Optimized table with fixed column widths -->
    <div class="section-title">{{ __('Consumable List') }}</div>
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
            @foreach ($consumables as $consumable)
                <tr>
                    <td>#{{ $consumable->consumable_id }}</td>
                    <td>{{ Str::limit($consumable->name, 30) }}</td>
                    <td>{{ __(ucwords($consumable->consumable_type)) }}</td>
                    <td>{{ __(ucwords($consumable->category_name)) }}</td>
                    <td>
                        <span class="status-badge status-{{ $consumable->consumable_status }}">
                            {{ __(ucwords($consumable->consumable_status)) }}
                        </span>
                    </td>
                    <td>
                        @if(isset($consumable->sale_price))
                            {{ number_format($consumable->sale_price, 3) }}
                        @else
                            {{ __('N/A') }}
                        @endif
                    </td>
                    <td>{{ $consumable->stock_quantity ?? __('N/A') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
