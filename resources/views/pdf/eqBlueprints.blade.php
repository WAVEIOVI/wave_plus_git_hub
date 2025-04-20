@extends('pdf.landscape')

@section('content')
    <!-- Key Metrics Section -->
    <div class="section-title">{{ __('Key Metrics') }}</div>
    <div class="metrics-container">
        <div class="metrics-box">
            <div class="metric-title">{{ __('Total EqBlueprints') }}</div>
            <div class="metric-value">{{ $statistics['total_eq_blueprints'] }}</div>
        </div>
        <div class="metrics-box">
            <div class="metric-title">{{ __('Active') }}</div>
            <div class="metric-value">{{ $statistics['total_active_eq_blueprints'] }}</div>
        </div>
        <div class="metrics-box">
            <div class="metric-title">{{ __('Draft') }}</div>
            <div class="metric-value">{{ $statistics['total_draft_eq_blueprints'] }}</div>
        </div>
        <div class="metrics-box">
            <div class="metric-title">{{ __('Inactive') }}</div>
            <div class="metric-value">{{ $statistics['total_inactive_eq_blueprints'] }}</div>
        </div>
    </div>

    <!-- Data Visualization Section - Optimized for space -->
    <div class="section-title">{{ __('EqBlueprint Distribution') }}</div>
    <div class="distribution-container">
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

    <!-- EqBlueprint List Section - Optimized table with fixed column widths -->
    <div class="section-title">{{ __('EqBlueprint List') }}</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="10%">{{ __('ID') }}</th>
                <th width="50%">{{ __('Name') }}</th>
                <th width="15%">{{ __('Category') }}</th>
                <th width="20%">{{ __('Status') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($eqBlueprints as $eqBlueprint)
                <tr>
                    <td>#{{ $eqBlueprint->eq_blueprint_id }}</td>
                    <td>{{ Str::limit($eqBlueprint->name, 30) }}</td>
                    <td>{{ __(ucwords($eqBlueprint->category_name)) }}</td>
                    <td>
                        <span class="status-badge status-{{ $eqBlueprint->eq_blueprint_status }}">
                            {{ __(ucwords($eqBlueprint->eq_blueprint_status)) }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
