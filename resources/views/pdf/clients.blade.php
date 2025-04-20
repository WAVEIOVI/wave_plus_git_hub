@extends('pdf.landscape')

@section('content')
    <!-- Key Metrics Section -->
    <div class="section-title">{{ __('Key Metrics') }}</div>
    <div class="metrics-container">
        <div class="metrics-box">
            <div class="metric-title">{{ __('Total Clients') }}</div>
            <div class="metric-value">{{ $statistics['total_clients'] }}</div>
        </div>
        <div class="metrics-box">
            <div class="metric-title">{{ __('Active') }}</div>
            <div class="metric-value">{{ $statistics['total_active_clients'] }}</div>
        </div>
        <div class="metrics-box">
            <div class="metric-title">{{ __('Pending') }}</div>
            <div class="metric-value">{{ $statistics['total_pending_clients'] }}</div>
        </div>
        <div class="metrics-box">
            <div class="metric-title">{{ __('Blocked') }}</div>
            <div class="metric-value">{{ $statistics['total_blocked_clients'] }}</div>
        </div>
    </div>

    <!-- Data Visualization Section - Optimized for space -->
    <div class="section-title">{{ __('Client Distribution') }}</div>
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

    <!-- Client List Section - Optimized table with fixed column widths -->
    <div class="section-title">{{ __('Client List') }}</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">{{ __('ID') }}</th>
                <th width="20%">{{ __('Client Name') }}</th>
                <th width="10%">{{ __('Tier') }}</th>
                <th width="15%">{{ __('Industry') }}</th>
                <th width="10%">{{ __('Status') }}</th>
                <th width="20%">{{ __('Address') }}</th>
                <th width="20%">{{ __('Contact') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($clients as $client)
                <tr>
                    <td>#{{ $client->client_id }}</td>
                    <td>{{ Str::limit($client->legal_name, 30) }}</td>
                    <td>{{ __(ucwords($client->client_tier)) }}</td>
                    <td>{{ __(ucwords($client->industry)) }}</td>
                    <td>
                        <span class="status-badge status-{{ $client->client_status }}">
                            {{ __(ucwords($client->client_status)) }}
                        </span>
                    </td>
                    <td>{{ Str::limit($client->hq_address ?? __('N/A'), 25) }}</td>
                    <td>
                        {{ Str::limit($client->primary_phone ?? __('N/A'), 15) }}
                        {{ $client->primary_email ? '/ ' . Str::limit($client->primary_email, 15) : '' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
