@extends('pdf.portrait')

@section('content')
<div class="product-report">
    <!-- Header & Product Hero Section -->
    <header class="product-header">
        <h1>{{ $product->name ?? __('Product Name') }}</h1>
        <div class="product-id">#{{ $product->product_id ?? __('N/A') }}</div>
        <div class="status-badge status-{{ $product->product_status ?? 'inactive' }}">
            {{ __(ucwords($product->product_status ?? __('N/A'))) }}
        </div>
    </header>

    <main class="content">
        <!-- Quick Facts Grid -->
        <section class="quick-facts-grid metrics-container">
            <div class="fact-item metrics-box">
                <div class="metric-title">{{ __('Type') }}</div>
                <div class="metric-value">{{ __(ucwords($product->product_type ?? __('N/A'))) }}</div>
            </div>
            <div class="fact-item metrics-box">
                <div class="metric-title">{{ __('Brand') }}</div>
                <div class="metric-value">{{ $product->brand_name ?? __('N/A') }}</div>
            </div>
            <div class="fact-item metrics-box">
                <div class="metric-title">{{ __('Category') }}</div>
                <div class="metric-value">{{ __(ucwords($product->category_name ?? __('N/A'))) }}</div>
            </div>
            <div class="fact-item metrics-box">
                <div class="metric-title">{{ __('Subcategory') }}</div>
                <div class="metric-value">{{ $product->subcategory_name ?? __('N/A') }}</div>
            </div>
        </section>

        <!-- Description Section -->
        <section class="section">
            <h2 class="section-title">{{ __('Product Description') }}</h2>
            <div class="description-box">
                {{ $product->description ?? __('No description available.') }}
            </div>
        </section>

        <!-- Two-column Information -->
        <section class="two-columns">
            <!-- Left Column: Technical Specifications & Certifications -->
            <div class="column">
                <div class="section">
                    <h2 class="section-title">{{ __('Technical Specifications') }}</h2>
                    <table class="data-table">
                        <tr>
                            <th>{{ __('Dimensions') }}</th>
                            <td>{{ $product->dimensions ?? __('N/A') }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Stock Quantity') }}</th>
                            <td>{{ $product->stock_quantity ?? __('N/A') }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Warranty') }}</th>
                            <td>{{ $product->warranty_info ?? __('N/A') }}</td>
                        </tr>
                    </table>
                </div>
                
                <div class="section">
                    <h2 class="section-title">{{ __('Certifications') }}</h2>
                    <div class="certification-list">
                        @if($product->certifications)
                            @php
                                $certifications = json_decode($product->certifications, true);
                            @endphp
                            @if(is_array($certifications) && count($certifications) > 0)
                                <ul class="cert-items">
                                    @foreach($certifications as $cert)
                                        <li>{{ $cert }}</li>
                                    @endforeach
                                </ul>
                            @else
                                {{ $product->certifications }}
                            @endif
                        @else
                            {{ __('No certifications available') }}
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Right Column: Pricing & Financial Details -->
            <div class="column">
                <div class="section">
                    <h2 class="section-title">{{ __('Pricing Information') }}</h2>
                    <div class="price-highlight">
                        <div class="price-label">{{ __('Sale Price') }}</div>
                        <div class="price-value">
                            @if(isset($product->sale_price))
                                {{ number_format($product->sale_price, 3) }}
                            @else
                                {{ __('N/A') }}
                            @endif
                        </div>
                        @if(isset($product->discount_rate) && $product->discount_rate > 0)
                            <div class="discount-badge">-{{ $product->discount_rate }}%</div>
                        @endif
                    </div>
                    
                    <table class="data-table price-table">
                        <tr>
                            <th>{{ __('Base Price') }}</th>
                            <td>
                                @if(isset($product->base_price))
                                    {{ number_format($product->base_price, 3) }}
                                @else
                                    {{ __('N/A') }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('Purchase Price') }}</th>
                            <td>
                                @if(isset($product->purchase_price))
                                    {{ number_format($product->purchase_price, 3) }}
                                @else
                                    {{ __('N/A') }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('Tax Rate') }}</th>
                            <td>{{ $product->tax_rate ?? 0 }}%</td>
                        </tr>
                        <tr>
                            <th>{{ __('Tax Amount') }}</th>
                            <td>
                                @if(isset($product->tax_sale))
                                    {{ number_format($product->tax_sale, 3) }}
                                @else
                                    {{ __('N/A') }}
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
                
                <div class="section">
                    <h2 class="section-title">{{ __('Financial Metrics') }}</h2>
                    <div class="metrics-grid">
                        <div class="metric-card">
                            <div class="metric-name">{{ __('Profit Margin') }}</div>
                            <div class="metric-figure">{{ $product->profit_margin ?? 0 }}%</div>
                        </div>
                        <div class="metric-card">
                            <div class="metric-name">{{ __('Discount Rate') }}</div>
                            <div class="metric-figure">{{ $product->discount_rate ?? 0 }}%</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Footer Information -->
        <footer class="footer-info">
            <div class="created-at">
                <strong>{{ __('Created') }}:</strong> {{ $product->created_at ?? __('N/A') }}
            </div>
            <div class="product-id-footer">
                <strong>{{ __('Product ID') }}:</strong> #{{ $product->product_id ?? __('N/A') }}
            </div>
        </footer>
    </main>
</div>
@endsection

@section('styles')
<style>
    /* Define CSS Variables for easy theme management */
    :root {
        --primary-color: #0f172a;
        --secondary-color: #64748b;
        --accent-blue: #3b82f6;
        --accent-red: #dc2626;
        --bg-light: #f8fafc;
        --bg-muted: #f9f9f9;
        --text-default: #333;
        --border-color: #e2e8f0;
    }

    /* Core Layout & Typography */
    .product-report {
        font-family: 'Helvetica', 'Arial', sans-serif;
        color: var(--text-default);
        line-height: 1.5;
        padding: 15px;
    }
    
    header.product-header {
        text-align: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border-color);
    }
    
    header.product-header h1 {
        font-size: 24px;
        margin-bottom: 5px;
        font-weight: 600;
    }
    
    .product-id {
        font-size: 14px;
        color: var(--secondary-color);
    }
    
    .status-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
        margin-top: 8px;
    }
    
    .status-active {
        background-color: #e3f9e5;
        color: #0e6245;
    }
    
    .status-draft {
        background-color: var(--bg-light);
        color: var(--secondary-color);
    }
    
    .status-inactive {
        background-color: #fef2f2;
        color: #b91c1c;
    }
    
    main.content {
        padding: 10px 0;
    }
    
    .section {
        margin-bottom: 25px;
    }
    
    .section-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 10px;
        padding-bottom: 5px;
        border-bottom: 2px solid var(--border-color);
        text-transform: uppercase;
    }
    
    /* Quick Facts Grid */
    .quick-facts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }
    
    .fact-item {
        background-color: var(--bg-light);
        border-radius: 8px;
        padding: 10px;
        text-align: center;
    }
    
    .fact-icon {
        font-size: 18px;
        margin-bottom: 5px;
    }
    
    .fact-label {
        font-size: 11px;
        color: var(--secondary-color);
        margin-bottom: 2px;
    }
    
    .fact-value {
        font-size: 14px;
        font-weight: 500;
    }
    
    /* Description Box */
    .description-box {
        background-color: var(--bg-muted);
        padding: 12px;
        border-radius: 6px;
        font-size: 14px;
    }
    
    /* Two Column Layout */
    .two-columns {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }
    
    .column {
        flex: 1;
        min-width: 280px;
    }
    
    /* Data Tables */
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .data-table th,
    .data-table td {
        padding: 8px;
        font-size: 13px;
        border-bottom: 1px solid var(--border-color);
        border: 1px solid var(--border-color);
    }
    
    .data-table th {
        background-color: var(--bg-light);
        text-align: left;
        color: var(--secondary-color);
        font-weight: 500;
    }
    
    /* Price Highlight */
    .price-highlight {
        background-color: #f0f9ff;
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 15px;
        position: relative;
        border: 2px solid var(--accent-blue);
    }
    
    .price-label {
        font-size: 12px;
        color: var(--accent-blue);
        margin-bottom: 3px;
    }
    
    .price-value {
        font-size: 22px;
        font-weight: 600;
        color: var(--primary-color);
    }
    
    .discount-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: var(--accent-red);
        color: #fff;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 500;
    }
    
    /* Metrics Grid */
    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 10px;
    }
    
    .metric-card {
        background-color: var(--bg-light);
        border-radius: 6px;
        padding: 10px;
        text-align: center;
    }
    
    .metric-name {
        font-size: 12px;
        color: var(--secondary-color);
        margin-bottom: 4px;
    }
    
    .metric-figure {
        font-size: 18px;
        font-weight: 600;
        color: var(--primary-color);
    }
    
    /* Certification List */
    .cert-items {
        margin: 0;
        padding-left: 20px;
    }
    
    .cert-items li {
        font-size: 13px;
        margin-bottom: 4px;
    }
    
    /* Footer Information */
    .footer-info {
        margin-top: 30px;
        padding-top: 10px;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        color: var(--secondary-color);
    }

    /* Optional: Add page-breaks for multi-page PDFs */
    @media print {
        .section { page-break-inside: avoid; }
    }
</style>
@endsection
