<!DOCTYPE html>
<html dir="{{ $isRtl ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <title>{{ __($title) }}</title>
    <style>
        /* Base styles */
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-size: 10px;
            line-height: 1.3;
            color: #333;
            background: #fff;
            font-family: 'Arial', sans-serif;
        }

        html[dir="rtl"] body {
            font-family: 'Tahoma', 'Arial', 'Noto Sans Arabic', sans-serif;
        }

        /* Variables */
        :root {
            --primary-color: #333;
            --accent-color: #cd473e;
            --secondary-color: #6a93a1;
            --light-bg: #f8f9fa;
            --header-border: #6a93a1;
            --header-height: 35px;
            /* Increased from 30px to allow more space for logo */
        }

        /* Page setup */
        @page {
            size: A4 landscape;
            margin: 15mm 15mm 15mm 15mm;
            /* Footer is now handled by Browsershot */
        }

        /* Header */
        .header-section {
            height: var(--header-height);
            padding: 0;
            border-bottom: 1px solid var(--header-border);
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-branding {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo {
            height: var(--header-height);
            display: flex;
            align-items: center;
        }

        .logo img {
            width: 150px;
            height: auto;
            margin: 0;
            /* Removed negative margin */
            max-height: 30px;
            /* Control max height to ensure it fits */
            object-fit: contain;
            /* Ensure the logo is displayed properly */
        }

        .document-info {
            text-align: right;
        }

        .document-title {
            color: var(--secondary-color);
            font-weight: bold;
            margin: 0;
            padding: 0;
            font-size: 12px;
            margin-bottom: 2px;
        }

        .prepared-by {
            font-size: 7px;
            margin-top: 2px;
        }

        /* Content */
        .content {
            margin: 0.5rem;
            padding-bottom: 20px;
            /* Reduced from 70px since footer is now in Browsershot */
        }

        .section-title {
            font-weight: bold;
            border-bottom: 1px solid var(--secondary-color);
            margin: 0.5rem 0 0.25rem;
            padding-bottom: 0.1rem;
            font-size: 12px;
        }

        /* Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0.25rem 0;
            font-size: 9px;
            table-layout: fixed;
        }

        .data-table th,
        .data-table td {
            padding: 0.25rem;
            line-height: 1.1;
            word-wrap: break-word;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .data-table th {
            background-color: var(--secondary-color);
            color: #fff;
        }

        .data-table tr:nth-child(even) {
            background-color: var(--light-bg);
        }

        /* Status Badges */
        .status-badge {
            display: inline-block;
            padding: 0.15rem 0.3rem;
            border-radius: 10px;
            font-size: 0.7rem;
            font-weight: bold;
        }

        .status-active {
            background-color: #00796b;
            color: #fff;
        }

        .status-pending {
            background-color: #f57c00;
            color: #fff;
        }

        .status-blocked {
            background-color: var(--accent-color);
            color: #fff;
        }

        .status-inactive {
            background-color: var(--primary-color);
            color: #fff;
        }

        /* Additional styles */
        .metrics-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 0.4rem;
        }

        .metrics-box {
            flex: 1;
            border: 1px solid #ddd;
            padding: 0.2rem;
            border-radius: 4px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .metric-title {
            color: var(--secondary-color);
            font-size: 0.85rem;
        }

        .metric-value {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .distribution-container {
            display: flex;
            gap: 1rem;
        }

        .distribution-section {
            flex: 1;
        }

        .distribution-section h4 {
            margin: 0.25rem 0;
            font-size: 0.9rem;
        }

        /* Print-specific adjustments */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }

            .page-break {
                page-break-before: always;
            }

            .data-table tr {
                page-break-inside: avoid;
            }

            .data-table {
                page-break-inside: auto;
            }
        }
    </style>
</head>

<body>
    <!-- Header Section -->
    <div class="header-section">
        <div class="header-branding">
            <div class="logo">
                <img src="{{ $ssPlusLogoSrc }}" alt="SS PLUS Logo">
            </div>
        </div>
        <div class="document-info">
            <h1 class="document-title">{{ __($title) }}</h1>
            <div class="prepared-by">{{ __('Prepared By') }}: {{ $preparedBy }}</div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        @yield('content')
    </div>

    <!-- Footer is now handled by Browsershot in PdfService.php -->
</body>

</html>
