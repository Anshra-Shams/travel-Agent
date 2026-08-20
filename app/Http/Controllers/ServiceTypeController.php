<?php

namespace App\Http\Controllers;

use App\Models\ServiceType;
use Illuminate\Http\Request;

class ServiceTypeController extends Controller
{
    public function create()
    {
        return view('service-types.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:service_types,name'],
            'icon' => ['nullable', 'string', 'max:10'],
            'description' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $data['icon'] = $data['icon'] ?: '📋';
        $data['sort_order'] = ServiceType::max('sort_order') + 1;

        $serviceType = ServiceType::create($data);

        return redirect()
            ->route('services.index')
            ->with('success', "\"{$serviceType->name}\" service created successfully.");
    }

    public function edit(ServiceType $serviceType)
    {
        return view('service-types.edit', [
            'serviceType' => $serviceType,
        ]);
    }

    public function update(Request $request, ServiceType $serviceType)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:service_types,name,' . $serviceType->id],
            'icon' => ['nullable', 'string', 'max:10'],
            'description' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $data['icon'] = $data['icon'] ?: '📋';

        $serviceType->update($data);

        return redirect()
            ->route('services.index')
            ->with('success', "\"{$serviceType->name}\" service updated successfully.");
    }

    public function destroy(ServiceType $serviceType)
    {
        $name = $serviceType->name;
        $serviceType->delete();

        return redirect()
            ->route('services.index')
            ->with('success', "\"{$name}\" service deleted.");
    }
}
