<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $package->name }} - IKRASH AL-MADINA TRAVELS & TOURS</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            background: #f3f4f6;
            font-family: Arial, Helvetica, sans-serif;
            color: #1f2937;
        }

        .print-container {
            max-width: 950px;
            margin: 0 auto;
            background: #ffffff;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        /* ==============================
           HEADER
        ============================== */

        .header {
            display: flex;
            align-items: center;
            width: 100%;
            border-bottom: 3px solid #4f46e5;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }

        /* LOGO - LEFT SIDE */
        .company-logo {
            width: 180px;
            min-width: 180px;
            text-align: left;
            padding-right: 20px;
        }

        .company-logo img {
            display: block;
            width: 160px;
            max-width: 160px;
            height: auto;
            max-height: 110px;
            object-fit: contain;
            object-position: left center;
        }

        /* COMPANY DETAILS - RIGHT OF LOGO */
        .company-details {
            flex: 1;
            text-align: left;
        }

        .company-name {
            font-size: 27px;
            font-weight: 800;
            color: #312e81;
            margin-bottom: 8px;
            line-height: 1.2;
        }

        .company-info {
            font-size: 13px;
            color: #4b5563;
            line-height: 1.7;
        }

        /* ==============================
           PACKAGE TITLE
        ============================== */

        .package-title {
            text-align: center;
            margin: 25px 0;
        }

        .package-title h1 {
            margin: 0 0 8px;
            font-size: 27px;
            color: #111827;
        }

        .package-title p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }

        /* ==============================
           SECTIONS
        ============================== */

        .section {
            margin-top: 25px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        .section-title {
            background: #eef2ff;
            color: #312e81;
            font-size: 16px;
            font-weight: 700;
            padding: 12px 15px;
            border-bottom: 1px solid #e5e7eb;
        }

        .section-content {
            padding: 15px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px 25px;
        }

        .item {
            padding: 8px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .value {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
        }

        /* ==============================
           DESCRIPTION
        ============================== */

        .description {
            font-size: 14px;
            line-height: 1.7;
            color: #374151;
            white-space: pre-line;
        }

        /* ==============================
           PRICE
        ============================== */

        .price-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .price-box:last-child {
            border-bottom: none;
        }

        .price-label {
            font-size: 14px;
            color: #4b5563;
        }

        .price-value {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
        }

        .final-price {
            background: #eef2ff;
            padding: 18px;
            border-radius: 8px;
            margin-top: 10px;
        }

        .final-price .price-label {
            font-size: 17px;
            font-weight: 700;
            color: #312e81;
        }

        .final-price .price-value {
            font-size: 22px;
            color: #312e81;
        }

        /* ==============================
           LISTS
        ============================== */

        .package-list {
            margin: 0;
            padding-left: 20px;
        }

        .package-list li {
            margin-bottom: 7px;
            font-size: 14px;
            line-height: 1.5;
        }

        /* ==============================
           FOOTER
        ============================== */

        .footer {
            margin-top: 35px;
            padding-top: 18px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            line-height: 1.6;
        }

        /* ==============================
           PRINT BUTTON
        ============================== */

        .print-button-wrapper {
            max-width: 950px;
            margin: 0 auto 20px;
            text-align: right;
        }

        .print-button {
            border: none;
            background: #4f46e5;
            color: white;
            padding: 11px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        .print-button:hover {
            background: #4338ca;
        }

        /* ==============================
           PRINT
        ============================== */

        @media print {

            @page {
                size: A4;
                margin: 12mm;
            }

            html,
            body {
                margin: 0 !important;
                padding: 0 !important;
                background: #ffffff !important;
            }

            .print-button-wrapper {
                display: none !important;
            }

            .print-container {
                width: 100% !important;
                max-width: none !important;
                margin: 0 !important;
                padding: 10px !important;
                box-shadow: none !important;
            }

            /* KEEP LOGO ON LEFT IN PRINT */
            .header {
                display: flex !important;
                flex-direction: row !important;
                align-items: center !important;
                justify-content: flex-start !important;
                width: 100% !important;
            }

            .company-logo {
                display: block !important;
                width: 180px !important;
                min-width: 180px !important;
                flex: 0 0 180px !important;
                text-align: left !important;
                padding-right: 20px !important;
            }

            .company-logo img {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
                width: 160px !important;
                max-width: 160px !important;
                height: auto !important;
                max-height: 110px !important;
                margin: 0 !important;
                object-fit: contain !important;
                object-position: left center !important;
            }

            .company-details {
                display: block !important;
                flex: 1 !important;
                text-align: left !important;
            }

            .section {
                break-inside: avoid;
                page-break-inside: avoid;
            }

            .package-title {
                break-inside: avoid;
                page-break-inside: avoid;
            }

            .footer {
                break-inside: avoid;
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>

    {{-- PRINT BUTTON --}}
    <div class="print-button-wrapper">
        <button
            type="button"
            class="print-button"
            onclick="window.print()"
        >
            🖨️ Print Package
        </button>
    </div>

    {{-- MAIN PRINT AREA --}}
    <div class="print-container">

        {{-- ==============================
             COMPANY HEADER
        ============================== --}}

        <div class="header">

            {{-- LOGO LEFT --}}
            <div class="company-logo">
                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="IKRASH AL-MADINA TRAVELS & TOURS Logo"
                >
            </div>

            {{-- COMPANY INFORMATION --}}
            <div class="company-details">

                <div class="company-name">
                    IKRASH AL-MADINA TRAVELS & TOURS
                </div>

                <div class="company-info">
                    📞 0316-3026092 | 0311-2211108
                    <br>
                    ✉ ikrashalmadinatravelntours@gmail.com
                    <br>
                    Facebook: IkrashAlMadinaTravels
                </div>

            </div>

        </div>


        {{-- ==============================
             PACKAGE TITLE
        ============================== --}}

        <div class="package-title">

            <h1>
                {{ $package->name }}
            </h1>

            <p>
                Travel Package Details
            </p>

        </div>


        {{-- ==============================
             PACKAGE OVERVIEW
        ============================== --}}

        <div class="section">

            <div class="section-title">
                Package Overview
            </div>

            <div class="section-content">

                <div class="grid">

                    <div class="item">
                        <div class="label">
                            Package Name
                        </div>

                        <div class="value">
                            {{ $package->name }}
                        </div>
                    </div>

                    <div class="item">
                        <div class="label">
                            Service
                        </div>

                        <div class="value">
                            {{ $package->serviceType?->name ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="item">
                        <div class="label">
                            Duration
                        </div>

                        <div class="value">
                            @if($package->duration_days)
                                {{ $package->duration_days }} Days
                            @else
                                N/A
                            @endif
                        </div>
                    </div>

                    <div class="item">
                        <div class="label">
                            Status
                        </div>

                        <div class="value">
                            {{ ucfirst($package->status ?? 'Active') }}
                        </div>
                    </div>

                </div>

            </div>

        </div>


        {{-- ==============================
             TRAVEL DATES
        ============================== --}}

        <div class="section">

            <div class="section-title">
                Travel & Visa Details
            </div>

            <div class="section-content">

                <div class="grid">

                    <div class="item">
                        <div class="label">
                            Departure Date
                        </div>

                        <div class="value">
                            {{ $package->departure_date ? $package->departure_date->format('d M Y') : 'N/A' }}
                        </div>
                    </div>

                    <div class="item">
                        <div class="label">
                            Return Date
                        </div>

                        <div class="value">
                            {{ $package->return_date ? $package->return_date->format('d M Y') : 'N/A' }}
                        </div>
                    </div>

                </div>

            </div>

        </div>


        {{-- ==============================
             DESCRIPTION
        ============================== --}}

        @if($package->description)

            <div class="section">

                <div class="section-title">
                    Package Description
                </div>

                <div class="section-content">

                    <div class="description">
                        {{ $package->description }}
                    </div>

                </div>

            </div>

        @endif


        {{-- ==============================
             PACKAGE DETAILS
        ============================== --}}

        @if($package->details)

            <div class="section">

                <div class="section-title">
                    Package Details
                </div>

                <div class="section-content">

                    @if(is_array($package->details))

                        <ul class="package-list">

                            @foreach($package->details as $key => $value)

                                @if(is_array($value))

                                    <li>
                                        <strong>
                                            {{ ucfirst(str_replace('_', ' ', $key)) }}:
                                        </strong>

                                        {{ implode(', ', $value) }}
                                    </li>

                                @else

                                    <li>
                                        <strong>
                                            {{ ucfirst(str_replace('_', ' ', $key)) }}:
                                        </strong>

                                        {{ $value }}
                                    </li>

                                @endif

                            @endforeach

                        </ul>

                    @else

                        <div class="description">
                            {{ $package->details }}
                        </div>

                    @endif

                </div>

            </div>

        @endif


        {{-- ==============================
             TERMS
        ============================== --}}

        @if($package->terms)

            <div class="section">

                <div class="section-title">
                    Terms & Conditions
                </div>

                <div class="section-content">

                    <div class="description">
                        {{ $package->terms }}
                    </div>

                </div>

            </div>

        @endif


        {{-- ==============================
             PRICING
        ============================== --}}

        <div class="section">

            <div class="section-title">
                Pricing
            </div>

            <div class="section-content">

                <div class="price-box">

                    <div class="price-label">
                        Original Price
                    </div>

                    <div class="price-value">
                        {{ $package->currency ?? 'PKR' }}
                        {{ number_format((float) ($package->original_price ?? 0), 2) }}
                    </div>

                </div>


                @if((float) ($package->discount ?? 0) > 0)

                    <div class="price-box">

                        <div class="price-label">
                            Discount
                        </div>

                        <div class="price-value">
                            {{ number_format((float) $package->discount, 2) }}
                        </div>

                    </div>

                @endif


                <div class="final-price">

                    <div class="price-box">

                        <div class="price-label">
                            Final Price
                        </div>

                        <div class="price-value">
                            {{ $package->currency ?? 'PKR' }}
                            {{ number_format((float) ($package->final_price ?? $package->original_price ?? 0), 2) }}
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ==============================
             FOOTER
        ============================== --}}

        <div class="footer">

            <strong>
                IKRASH AL-MADINA TRAVELS & TOURS
            </strong>

            <br>

            Thank you for choosing our travel services.

            <br>

            This document is system generated.

        </div>

    </div>

</body>
</html>
