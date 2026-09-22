<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\VisaApplication;
use Illuminate\Http\Request;

class VisaApplicationController extends Controller
{
    /**
     * Display all visa applications.
     */
    public function index()
    {
        $visaApplications = VisaApplication::with('customer')
            ->latest()
            ->paginate(10);

        return view('visa.index', compact('visaApplications'));
    }

    /**
     * Show form to create a new visa application.
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();

        return view('visa.create', compact('customers'));
    }

    /**
     * Store a new visa application.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'visa_type' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'application_number' => 'nullable|string|max:100',
            'application_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:application_date',
            'status' => 'required|in:Pending,Submitted,Approved,Rejected,Expired',
            'required_documents' => 'nullable|array',
            'required_documents.*' => 'string|max:255',
            'notes' => 'nullable|string',
        ]);

        VisaApplication::create($validated);

        return redirect()
            ->route('visa.index')
            ->with('success', 'Visa application created successfully.');
    }

    /**
     * Display a single visa application.
     */
    public function show(VisaApplication $visaApplication)
    {
        $visaApplication->load('customer');

        return view('visa.show', compact('visaApplication'));
    }

    /**
     * Show form to edit a visa application.
     */
    public function edit(VisaApplication $visaApplication)
    {
        $customers = Customer::orderBy('name')->get();

        return view('visa.edit', compact('visaApplication', 'customers'));
    }

    /**
     * Update a visa application.
     */
    public function update(Request $request, VisaApplication $visaApplication)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'visa_type' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'application_number' => 'nullable|string|max:100',
            'application_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:application_date',
            'status' => 'required|in:Pending,Submitted,Approved,Rejected,Expired',
            'required_documents' => 'nullable|array',
            'required_documents.*' => 'string|max:255',
            'notes' => 'nullable|string',
        ]);

        $visaApplication->update($validated);

        return redirect()
            ->route('visa.index')
            ->with('success', 'Visa application updated successfully.');
    }

    /**
     * Delete a visa application.
     */
    public function destroy(VisaApplication $visaApplication)
    {
        $visaApplication->delete();

        return redirect()
            ->route('visa.index')
            ->with('success', 'Visa application deleted successfully.');
    }
}