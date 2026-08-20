<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Titles for the module pages.
     */
    protected array $titles = [
        'leads' => 'Leads',
        'customers' => 'Customers',
        'quotations' => 'Quotations',
        'bookings' => 'Bookings',
        'payments' => 'Payments',
        'documents' => 'Documents',
        'follow-ups' => 'Follow-ups',
        'reports' => 'Reports',
        'settings' => 'Settings',
    ];

    /**
     * Show a placeholder page for modules under construction.
     */
    public function show(Request $request)
    {
        $page = $request->route('page');

        $title = $this->titles[$page] ?? ucfirst(str_replace('-', ' ', $page));

        return view('pages.index', compact('page', 'title'));
    }
}
