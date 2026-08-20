<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Lead;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index()
    {
        return view('dashboard', [
            'totalLeads' => Lead::count(),
            'totalCustomers' => Customer::count(),
            'activeBookings' => 0,
            'pendingPayments' => 0,
            'upcomingTravel' => Lead::whereNotNull('travel_date')
                ->where('travel_date', '>=', now()->today())
                ->count(),
            'recentLeads' => Lead::with('assignedAgent')
                ->latest()
                ->take(5)
                ->get(),
            'upcomingFollowUps' => Lead::with('assignedAgent')
                ->whereNotNull('follow_up_date')
                ->where('follow_up_date', '>=', now()->today())
                ->orderBy('follow_up_date')
                ->take(5)
                ->get(),
        ]);
    }
}
