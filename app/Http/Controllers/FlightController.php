<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function index()
    {
        $flights = Flight::latest()->get();

        return view('flights.index', compact('flights'));
    }

    public function create()
    {
        return view('flights.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'flight_number' => 'required|string|max:255',
            'airline' => 'required|string|max:255',
            'departure_from' => 'required|string|max:255',
            'destination_to' => 'required|string|max:255',
            'departure_at' => 'nullable|date',
            'arrival_at' => 'nullable|date',
            'class' => 'required|in:Economy,Business',
            'total_seats' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:Scheduled,Confirmed,Cancelled,Completed',
            'notes' => 'nullable|string',
        ]);

        Flight::create($validated);

        return redirect()
            ->route('flights.index')
            ->with('success', 'Flight added successfully.');
    }

    public function show(Flight $flight)
    {
        return view('flights.show', compact('flight'));
    }

    public function edit(Flight $flight)
    {
        return view('flights.edit', compact('flight'));
    }

    public function update(Request $request, Flight $flight)
    {
        $validated = $request->validate([
            'flight_number' => 'required|string|max:255',
            'airline' => 'required|string|max:255',
            'departure_from' => 'required|string|max:255',
            'destination_to' => 'required|string|max:255',
            'departure_at' => 'nullable|date',
            'arrival_at' => 'nullable|date',
            'class' => 'required|in:Economy,Business',
            'total_seats' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:Scheduled,Confirmed,Cancelled,Completed',
            'notes' => 'nullable|string',
        ]);

        $flight->update($validated);

        return redirect()
            ->route('flights.index')
            ->with('success', 'Flight updated successfully.');
    }

    public function destroy(Flight $flight)
    {
        $flight->delete();

        return redirect()
            ->route('flights.index')
            ->with('success', 'Flight deleted successfully.');
    }
}