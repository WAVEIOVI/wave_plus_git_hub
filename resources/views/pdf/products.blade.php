@extends('pdf.landscape')

@section('content')
    <!-- Key Metrics Section -->
    <div class="section-title">{{ __('Key Metrics') }}</div>
    <div class="metrics-container">
        <div class="metrics-box">
            <div class="metric-title">{{ __('Total Products') }}</div>
            <div class="metric-value">{{ $statistics['total_products'] }}</div>
        </div>
        <div class="metrics-box">
            <div class="metric-title">{{ __('Active') }}</div>
            <div class="metric-value">{{ $statistics['total_active_products'] }}</div>
        </div>
        <div class="metrics-box">
            <div class="metric-title">{{ __('Draft') }}</div>
            <div class="metric-value">{{ $statistics['total_draft_products'] }}</div>
        </div>
        <div class="metrics-box">
            <div class="metric-title">{{ __('Inactive') }}</div>
            <div class="metric-value">{{ $statistics['total_inactive_products'] }}</div>
        </div>
    </div>

    <!-- Data Visualization Section - Optimized for space -->
    <div class="section-title">{{ __('Product Distribution') }}</div>
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

    <!-- Product List Section - Optimized table with fixed column widths -->
    <div class="section-title">{{ __('Product List') }}</div>
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
            @foreach ($products as $product)
                <tr>
                    <td>#{{ $product->product_id }}</td>
                    <td>{{ Str::limit($product->name, 30) }}</td>
                    <td>{{ __(ucwords($product->product_type)) }}</td>
                    <td>{{ __(ucwords($product->category_name)) }}</td>
                    <td>
                        <span class="status-badge status-{{ $product->product_status }}">
                            {{ __(ucwords($product->product_status)) }}
                        </span>
                    </td>
                    <td>
                        @if(isset($product->sale_price))
                            {{ number_format($product->sale_price, 3) }}
                        @else
                            {{ __('N/A') }}
                        @endif
                    </td>
                    <td>{{ $product->stock_quantity ?? __('N/A') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
