<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerService;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ServicesController extends Controller
{
    /**
     * Display the services page with DB-driven service category cards.
     */
    public function index()
    {
        $serviceTypes = ServiceType::where('status', 'active')->orderBy('sort_order')->get();
        $allCustomers = Customer::whereNotNull('service')->where('service', '<>', '[]')
            ->get(['id', 'name', 'service']);

        $results = [];

        foreach ($serviceTypes as $index => $serviceType) {
            $customers = $allCustomers->filter(
                fn ($c) => in_array($serviceType->name, $c->services_list, true)
            );

            $requirements = CustomerService::where('service_type', $serviceType->name)
                ->with('customer:id,name,phone')
                ->get();

            $colors = ServiceType::getColorForIndex($index);

            $results[] = [
                'serviceType' => $serviceType,
                'colors' => $colors,
                'customers' => $customers,
                'customerCount' => $customers->count(),
                'requirementCount' => $requirements->count(),
                'requirements' => $requirements,
            ];
        }

        return view('services.index', [
            'results' => $results,
        ]);
    }

    /**
     * Show all customers associated with a specific service type.
     */
    public function serviceTypeShow(Request $request, string $serviceType)
    {
        $serviceTypeModel = ServiceType::where('name', $serviceType)->first();

        if (!$serviceTypeModel) {
            abort(404);
        }

        $activeNames = ServiceType::getActiveNames();
        $index = array_search($serviceType, $activeNames, true);
        $colors = ServiceType::getColorForIndex($index !== false ? $index : 0);

        $customers = Customer::whereJsonContains('service', $serviceType)
            ->with(['services' => fn ($q) => $q->where('service_type', $serviceType)])
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($c) use ($search) {
                    $c->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function ($q) use ($request, $serviceType) {
                $q->whereHas('requirements', fn ($r) => $r
                    ->where('service_type', $serviceType)
                    ->where('status', $request->status)
                );
            })
            ->when($request->filled('travel_date_from'), function ($q) use ($request, $serviceType) {
                $q->whereHas('requirements', fn ($r) => $r
                    ->where('service_type', $serviceType)
                    ->whereDate('travel_date', '>=', $request->travel_date_from)
                );
            })
            ->when($request->filled('travel_date_to'), function ($q) use ($request, $serviceType) {
                $q->whereHas('requirements', fn ($r) => $r
                    ->where('service_type', $serviceType)
                    ->whereDate('travel_date', '<=', $request->travel_date_to)
                );
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('services.type', [
            'serviceType' => $serviceType,
            'serviceTypeModel' => $serviceTypeModel,
            'colors' => $colors,
            'customers' => $customers,
            'statuses' => CustomerService::STATUSES,
            'filters' => $request->only(['search', 'status', 'travel_date_from', 'travel_date_to']),
        ]);
    }

    /**
     * Show the form for creating a new customer service requirement.
     * When ?service= is passed, pre-select that service and filter customers.
     */
    public function create(Request $request)
    {
        $preselectedService = $request->query('service');
        $serviceTypeModel = null;

        if ($preselectedService) {
            $serviceTypeModel = ServiceType::where('name', $preselectedService)->first();
        }

        // If a service is pre-selected, only show customers who have that service
        if ($serviceTypeModel) {
            $customerIds = Customer::whereJsonContains('service', $serviceTypeModel->name)->pluck('id');
            $customers = Customer::whereIn('id', $customerIds)->orderBy('name')->get(['id', 'name', 'phone', 'email']);
        } else {
            $customers = Customer::orderBy('name')->get(['id', 'name', 'phone', 'email']);
        }

        $serviceTypes = ServiceType::getActiveNames();

        return view('services.create', [
            'customers' => $customers,
            'serviceTypes' => $serviceTypes,
            'statuses' => CustomerService::STATUSES,
            'selectedCustomerId' => $request->integer('customer') ?: null,
            'preselectedService' => $preselectedService,
        ]);
    }

    /**
     * Store a newly created customer service.
     */
    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['agent_id'] = auth()->id();
        $data = $this->normalizeCheckboxes($request, $data);

        $duplicate = CustomerService::where('customer_id', $data['customer_id'])
            ->where('service_type', $data['service_type'])
            ->exists();

        if ($duplicate) {
            throw ValidationException::withMessages([
                'service_type' => 'Requirements for this service already exist for this customer. Edit the existing service instead.',
            ]);
        }

        $service = CustomerService::create($data);

        $service->customer->activities()->create([
            'agent_id' => auth()->id(),
            'type' => 'note',
            'description' => "New {$service->service_type} service added",
        ]);

        return redirect()
            ->route('services.show', $service)
            ->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified customer service.
     */
    public function show(CustomerService $service)
    {
        $service->load(['customer', 'agent']);

        return view('services.show', [
            'service' => $service,
        ]);
    }

    /**
     * Show the form for editing the specified customer service.
     */
    public function edit(CustomerService $service)
    {
        $serviceTypes = ServiceType::getActiveNames();

        return view('services.edit', [
            'service' => $service,
            'customers' => Customer::orderBy('name')->get(['id', 'name', 'phone', 'email']),
            'serviceTypes' => $serviceTypes,
            'statuses' => CustomerService::STATUSES,
        ]);
    }

    /**
     * Update the specified customer service.
     */
    public function update(Request $request, CustomerService $service)
    {
        $oldStatus = $service->status;
        $oldType = $service->service_type;

        $data = $this->validated($request, $service);
        $data = $this->normalizeCheckboxes($request, $data);

        $service->update($data);

        $changes = [];
        if ($oldType !== $service->service_type) {
            $changes[] = "service type changed from {$oldType} to {$service->service_type}";
        }
        if ($oldStatus !== $service->status) {
            $changes[] = "status changed from {$oldStatus} to {$service->status}";
        }

        $service->customer->activities()->create([
            'agent_id' => auth()->id(),
            'type' => 'note',
            'description' => $changes
                ? 'Service updated (' . implode(', ', $changes) . ')'
                : 'Service details updated',
        ]);

        return redirect()
            ->route('services.show', $service)
            ->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified customer service.
     */
    public function destroy(CustomerService $service)
    {
        $customer = $service->customer;

        $customer->activities()->create([
            'agent_id' => auth()->id(),
            'type' => 'note',
            'description' => "{$service->service_type} service removed",
        ]);

        $service->delete();

        return redirect()
            ->route('services.index')
            ->with('success', 'Service deleted successfully.');
    }

    /**
     * Validate and prepare the service request data.
     */
    protected function validated(Request $request, ?CustomerService $service = null): array
    {
        $serviceTypeNames = implode(',', ServiceType::getActiveNames());

        $data = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'service_type' => ['required', 'in:' . $serviceTypeNames],
            'status' => ['required', 'in:' . implode(',', CustomerService::STATUSES)],
            'destination' => ['nullable', 'string', 'max:255'],
            'travel_date' => ['nullable', 'date'],
            'travelers' => ['nullable', 'integer', 'min:1', 'max:999'],
            'requirements' => ['nullable', 'string', 'max:5000'],

            // Flight Ticket
            'departure' => ['nullable', 'string', 'max:255'],
            'arrival' => ['nullable', 'string', 'max:255'],
            'departure_date' => ['nullable', 'date'],
            'return_date' => ['nullable', 'date'],
            'trip_type' => ['nullable', 'in:One Way,Round Trip,Multi City'],
            'passenger_count' => ['nullable', 'integer', 'min:1', 'max:999'],
            'preferred_airline' => ['nullable', 'string', 'max:255'],
            'flight_class' => ['nullable', 'in:Economy,Premium Economy,Business,First'],

            // Visa
            'visa_country' => ['nullable', 'string', 'max:255'],
            'visa_type' => ['nullable', 'string', 'max:255'],
            'applicants' => ['nullable', 'integer', 'min:1', 'max:999'],
            'visa_requirements' => ['nullable', 'string', 'max:5000'],

            // Hotel
            'hotel_preference' => ['nullable', 'string', 'max:255'],
            'check_in' => ['nullable', 'date'],
            'check_out' => ['nullable', 'date'],
            'rooms' => ['nullable', 'integer', 'min:1', 'max:99'],
            'adults' => ['nullable', 'integer', 'min:1', 'max:99'],
            'children' => ['nullable', 'integer', 'min:0', 'max:99'],
            'room_type' => ['nullable', 'string', 'max:255'],

            // Umrah / Hajj / Tour Package
            'package_type' => ['nullable', 'string', 'max:255'],
            'makkah_hotel' => ['nullable', 'string', 'max:255'],
            'madinah_hotel' => ['nullable', 'string', 'max:255'],
            'makkah_nights' => ['nullable', 'integer', 'min:0', 'max:365'],
            'madinah_nights' => ['nullable', 'integer', 'min:0', 'max:365'],
            'transport_requirement' => ['nullable', 'boolean'],
            'visa_requirement' => ['nullable', 'boolean'],
            'ticket_requirement' => ['nullable', 'boolean'],
            'hotel_requirement' => ['nullable', 'string', 'max:255'],

            // Worldwide Tour Package
            'duration' => ['nullable', 'string', 'max:255'],
            'activities' => ['nullable', 'string', 'max:5000'],

            // Transportation
            'pickup_location' => ['nullable', 'string', 'max:255'],
            'dropoff_location' => ['nullable', 'string', 'max:255'],
            'pickup_date' => ['nullable', 'date'],
            'pickup_time' => ['nullable', 'string', 'max:10'],
            'vehicle_type' => ['nullable', 'string', 'max:255'],
            'passengers' => ['nullable', 'integer', 'min:1', 'max:999'],
        ]);

        $customer = Customer::findOrFail($data['customer_id']);
        $allowed = $customer->services_list;

        if ($service && !in_array($service->service_type, $allowed, true)) {
            $allowed[] = $service->service_type;
        }

        if (!in_array($data['service_type'], $allowed, true)) {
            throw ValidationException::withMessages([
                'service_type' => 'The selected service is not associated with this customer. Save the service for the customer first.',
            ]);
        }

        return $data;
    }

    /**
     * Normalize checkboxes so an unchecked box is stored as false.
     */
    protected function normalizeCheckboxes(Request $request, array $data): array
    {
        $data['transport_requirement'] = $request->boolean('transport_requirement');
        $data['visa_requirement'] = $request->boolean('visa_requirement');
        $data['ticket_requirement'] = $request->boolean('ticket_requirement');

        return $data;
    }
}
