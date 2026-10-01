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
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 40px;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #4f46e5;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }

        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #312e81;
            margin-bottom: 8px;
        }

        .company-info {
            font-size: 13px;
            line-height: 1.7;
            color: #555;
        }

        .package-title {
            text-align: center;
            margin: 25px 0;
        }

        .package-title h1 {
            margin: 0;
            font-size: 28px;
            color: #111827;
        }

        .package-title p {
            margin-top: 8px;
            color: #6b7280;
        }

        .section {
            margin-top: 25px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        .section-title {
            background: #f3f4f6;
            padding: 12px 16px;
            font-size: 17px;
            font-weight: bold;
            color: #312e81;
            border-bottom: 1px solid #e5e7eb;
        }

        .section-body {
            padding: 18px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .item {
            margin-bottom: 5px;
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
            white-space: pre-line;
        }

        .price-box {
            text-align: center;
            background: #f5f3ff;
            border: 2px solid #4f46e5;
            border-radius: 10px;
            padding: 22px;
            margin-top: 20px;
        }

        .original-price {
            color: #6b7280;
            text-decoration: line-through;
            font-size: 15px;
        }

        .discount {
            color: #dc2626;
            font-size: 14px;
            margin-top: 5px;
        }

        .final-price {
            font-size: 30px;
            font-weight: bold;
            color: #312e81;
            margin-top: 8px;
        }

        .terms {
            white-space: pre-line;
            line-height: 1.7;
            font-size: 14px;
            color: #374151;
        }

        .footer {
            text-align: center;
            margin-top: 35px;
            padding-top: 18px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #6b7280;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4f46e5;
            color: white;
            border: none;
            padding: 11px 18px;
            border-radius: 7px;
            cursor: pointer;
            font-size: 14px;
        }

        .print-button:hover {
            background: #4338ca;
        }

        @media print {

            body {
                background: white;
                padding: 0;
            }

            .print-container {
                max-width: 100%;
                padding: 20px;
            }

            .print-button {
                display: none;
            }

            .section {
                break-inside: avoid;
            }
        }

        @media (max-width: 650px) {

            body {
                padding: 10px;
            }

            .print-container {
                padding: 20px;
            }

            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <button class="print-button" onclick="window.print()">
        🖨 Print Package
    </button>

    <div class="print-container">

        {{-- Company Header --}}
        <div class="header">

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


        {{-- Package Title --}}
        <div class="package-title">

            <h1>
                {{ $package->name }}
            </h1>

            <p>
                {{ $serviceType->name }}
            </p>

        </div>


        {{-- Package Overview --}}
        <div class="section">

            <div class="section-title">
                Package Overview
            </div>

            <div class="section-body">

                @if($package->description)
                    <div class="item" style="margin-bottom: 18px;">
                        <div class="label">Description</div>

                        <div class="value">
                            {{ $package->description }}
                        </div>
                    </div>
                @endif

                <div class="grid">

                    <div class="item">
                        <div class="label">Departure Date</div>
                        <div class="value">
                            {{ $package->departure_date?->format('d M Y') ?? '-' }}
                        </div>
                    </div>

                    <div class="item">
                        <div class="label">Return Date</div>
                        <div class="value">
                            {{ $package->return_date?->format('d M Y') ?? '-' }}
                        </div>
                    </div>

                    <div class="item">
                        <div class="label">Duration</div>
                        <div class="value">
                            {{ $package->duration_days ?? '-' }} Days
                        </div>
                    </div>

                    <div class="item">
                        <div class="label">Status</div>
                        <div class="value">
                            {{ ucfirst($package->status) }}
                        </div>
                    </div>

                </div>

            </div>

        </div>


        {{-- Hotel Details --}}
        <div class="section">

            <div class="section-title">
                Hotel Details
            </div>

            <div class="section-body">

                <div class="grid">

                    <div class="item">
                        <div class="label">Makkah Hotel</div>
                        <div class="value">
                            {{ $package->details['makkah_hotel'] ?? '-' }}
                        </div>
                    </div>

                    <div class="item">
                        <div class="label">Makkah Distance</div>
                        <div class="value">
                            {{ $package->details['makkah_distance'] ?? '-' }}
                        </div>
                    </div>

                    <div class="item">
                        <div class="label">Madina Hotel</div>
                        <div class="value">
                            {{ $package->details['madina_hotel'] ?? '-' }}
                        </div>
                    </div>

                    <div class="item">
                        <div class="label">Madina Distance</div>
                        <div class="value">
                            {{ $package->details['madina_distance'] ?? '-' }}
                        </div>
                    </div>

                    <div class="item">
                        <div class="label">Room Type</div>
                        <div class="value">
                            {{ $package->details['room_type'] ?? '-' }}
                        </div>
                    </div>

                </div>

            </div>

        </div>


        {{-- Travel & Visa --}}
        <div class="section">

            <div class="section-title">
                Travel & Visa Details
            </div>

            <div class="section-body">

                <div class="grid">

                    <div class="item">
                        <div class="label">Airline</div>
                        <div class="value">
                            {{ $package->details['airline'] ?? '-' }}
                        </div>
                    </div>

                    <div class="item">
                        <div class="label">Visa</div>
                        <div class="value">
                            {{ $package->details['visa'] ?? '-' }}
                        </div>
                    </div>

                    <div class="item">
                        <div class="label">Flight Details</div>
                        <div class="value">
                            {{ $package->details['flight_details'] ?? '-' }}
                        </div>
                    </div>

                    <div class="item">
                        <div class="label">Transport</div>
                        <div class="value">
                            {{ $package->details['transport'] ?? '-' }}
                        </div>
                    </div>

                    <div class="item">
                        <div class="label">Insurance</div>
                        <div class="value">
                            {{ $package->details['insurance'] ?? '-' }}
                        </div>
                    </div>

                </div>

            </div>

        </div>


        {{-- Ziyarat & Meals --}}
        <div class="section">

            <div class="section-title">
                Ziyarat & Meals
            </div>

            <div class="section-body">

                <div class="grid">

                    <div class="item">
                        <div class="label">Makkah Ziyarat</div>
                        <div class="value">
                            {{ $package->details['makkah_ziyarat'] ?? '-' }}
                        </div>
                    </div>

                    <div class="item">
                        <div class="label">Madina Ziyarat</div>
                        <div class="value">
                            {{ $package->details['madina_ziyarat'] ?? '-' }}
                        </div>
                    </div>

                    <div class="item">
                        <div class="label">Meals</div>
                        <div class="value">
                            {{ $package->details['meals'] ?? '-' }}
                        </div>
                    </div>

                </div>

            </div>

        </div>


        {{-- Included / Excluded --}}
        <div class="section">

            <div class="section-title">
                Services
            </div>

            <div class="section-body">

                <div class="grid">

                    <div class="item">
                        <div class="label">Included Services</div>

                        <div class="value">
                            {{ $package->details['included_services'] ?? '-' }}
                        </div>
                    </div>

                    <div class="item">
                        <div class="label">Excluded Services</div>

                        <div class="value">
                            {{ $package->details['excluded_services'] ?? '-' }}
                        </div>
                    </div>

                </div>

            </div>

        </div>


        {{-- Pricing --}}
        <div class="price-box">

            <div class="label">
                Original Price
            </div>

            <div class="original-price">
                {{ $package->currency }}
                {{ number_format($package->original_price ?? 0) }}
            </div>

            @if(($package->discount ?? 0) > 0)

                <div class="discount">
                    Discount:
                    {{ $package->currency }}
                    {{ number_format($package->discount) }}
                </div>

            @endif

            <div class="label" style="margin-top: 12px;">
                Final Price Per Person
            </div>

            <div class="final-price">
                {{ $package->currency }}
                {{ number_format($package->final_price ?? 0) }}
            </div>

        </div>


        {{-- Terms --}}
        @if($package->terms)

            <div class="section">

                <div class="section-title">
                    Terms & Conditions
                </div>

                <div class="section-body">

                    <div class="terms">
                        {{ $package->terms }}
                    </div>

                </div>

            </div>

        @endif


        {{-- Footer --}}
        <div class="footer">

            <strong>
                IKRASH AL-MADINA TRAVELS & TOURS
            </strong>

            <br>

            0316-3026092 | 0311-2211108

            <br>

            ikrashalmadinatravelntours@gmail.com

            <br><br>

            Thank you for choosing Ikrash Al-Madina Travels & Tours.

        </div>

    </div>

</body>
</html>