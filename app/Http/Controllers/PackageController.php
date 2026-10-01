<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\ServiceType;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index(ServiceType $serviceType)
    {
        $packages = $serviceType->packages()
            ->latest()
            ->get();

        return view('packages.index', compact('serviceType', 'packages'));
    }

    public function create(ServiceType $serviceType)
    {
        return view('packages.create', compact('serviceType'));
    }

    public function store(Request $request, ServiceType $serviceType)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],

            'departure_date' => ['nullable', 'date'],
            'return_date' => ['nullable', 'date', 'after_or_equal:departure_date'],
            'duration_days' => ['nullable', 'integer', 'min:1'],

            'original_price' => ['nullable', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'final_price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:10'],

            'status' => ['required', 'in:active,inactive'],
            'terms' => ['nullable', 'string'],

            // Hotel Details
            'makkah_hotel' => ['nullable', 'string', 'max:255'],
            'makkah_distance' => ['nullable', 'string', 'max:100'],
            'madina_hotel' => ['nullable', 'string', 'max:255'],
            'madina_distance' => ['nullable', 'string', 'max:100'],
            'room_type' => ['nullable', 'string', 'max:100'],

            // Travel Details
            'airline' => ['nullable', 'string', 'max:255'],
            'flight_details' => ['nullable', 'string'],
            'visa' => ['nullable', 'string', 'max:255'],
            'transport' => ['nullable', 'string'],
            'insurance' => ['nullable', 'string'],

            // Ziyarat & Meals
            'makkah_ziyarat' => ['nullable', 'string'],
            'madina_ziyarat' => ['nullable', 'string'],
            'meals' => ['nullable', 'string'],

            // Services
            'included_services' => ['nullable', 'string'],
            'excluded_services' => ['nullable', 'string'],
        ]);

        $details = [
            'makkah_hotel' => $request->makkah_hotel,
            'makkah_distance' => $request->makkah_distance,

            'madina_hotel' => $request->madina_hotel,
            'madina_distance' => $request->madina_distance,

            'room_type' => $request->room_type,

            'airline' => $request->airline,
            'flight_details' => $request->flight_details,
            'visa' => $request->visa,

            'transport' => $request->transport,
            'insurance' => $request->insurance,

            'makkah_ziyarat' => $request->makkah_ziyarat,
            'madina_ziyarat' => $request->madina_ziyarat,

            'meals' => $request->meals,

            'included_services' => $request->included_services,
            'excluded_services' => $request->excluded_services,
        ];

        $data['details'] = $details;

        $serviceType->packages()->create($data);

        return redirect()
            ->route('packages.index', $serviceType)
            ->with('success', 'Package created successfully.');
    }

    public function show(ServiceType $serviceType, Package $package)
    {
        if ($package->service_type_id !== $serviceType->id) {
            abort(404);
        }

        return view('packages.show', compact('serviceType', 'package'));
    }

    public function print(ServiceType $serviceType, Package $package)
    {
        if ($package->service_type_id !== $serviceType->id) {
            abort(404);
        }

        return view('packages.print', compact('serviceType', 'package'));
    }
}