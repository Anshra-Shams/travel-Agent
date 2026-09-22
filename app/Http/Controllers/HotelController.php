<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    /**
     * Display a listing of hotels.
     */
    public function index()
    {
        $hotels = Hotel::latest()->get();

        return view('hotels.index', compact('hotels'));
    }

    /**
     * Show the form for creating a new hotel.
     */
    public function create()
    {
        return view('hotels.create');
    }

    /**
     * Store a newly created hotel.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'hotel_name' => 'required|string|max:255',
            'hotel_code' => 'required|string|max:100|unique:hotels,hotel_code',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',

            'address' => 'nullable|string',

            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',

            'room_type' => 'nullable|string|max:255',
            'total_rooms' => 'required|integer|min:0',
            'price_per_night' => 'required|numeric|min:0',
            'meal_plan' => 'nullable|string|max:255',

            'check_in_time' => 'nullable',
            'check_out_time' => 'nullable',

            'status' => 'required|in:Active,Inactive',

            'notes' => 'nullable|string',
        ]);

        Hotel::create($validated);

        return redirect()
            ->route('hotels.index')
            ->with('success', 'Hotel added successfully.');
    }

    /**
     * Display the specified hotel.
     */
    public function show(Hotel $hotel)
    {
        return view('hotels.show', compact('hotel'));
    }

    /**
     * Show the form for editing the specified hotel.
     */
    public function edit(Hotel $hotel)
    {
        return view('hotels.edit', compact('hotel'));
    }

    /**
     * Update the specified hotel.
     */
    public function update(Request $request, Hotel $hotel)
    {
        $validated = $request->validate([
            'hotel_name' => 'required|string|max:255',

            'hotel_code' => 'required|string|max:100|unique:hotels,hotel_code,' . $hotel->id,

            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',

            'address' => 'nullable|string',

            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',

            'room_type' => 'nullable|string|max:255',
            'total_rooms' => 'required|integer|min:0',
            'price_per_night' => 'required|numeric|min:0',
            'meal_plan' => 'nullable|string|max:255',

            'check_in_time' => 'nullable',
            'check_out_time' => 'nullable',

            'status' => 'required|in:Active,Inactive',

            'notes' => 'nullable|string',
        ]);

        $hotel->update($validated);

        return redirect()
            ->route('hotels.index')
            ->with('success', 'Hotel updated successfully.');
    }

    /**
     * Remove the specified hotel.
     */
    public function destroy(Hotel $hotel)
    {
        $hotel->delete();

        return redirect()
            ->route('hotels.index')
            ->with('success', 'Hotel deleted successfully.');
    }
}
