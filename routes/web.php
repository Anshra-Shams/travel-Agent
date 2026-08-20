<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\ServiceTypeController;
use App\Http\Controllers\ServicesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Service type management (create/edit/delete service categories)
    Route::get('/service-types/create', [ServiceTypeController::class, 'create'])->name('service-types.create');
    Route::post('/service-types', [ServiceTypeController::class, 'store'])->name('service-types.store');
    Route::get('/service-types/{serviceType}/edit', [ServiceTypeController::class, 'edit'])->name('service-types.edit');
    Route::put('/service-types/{serviceType}', [ServiceTypeController::class, 'update'])->name('service-types.update');
    Route::delete('/service-types/{serviceType}', [ServiceTypeController::class, 'destroy'])->name('service-types.destroy');

    // Quotations module
    Route::get('/quotations', [QuotationController::class, 'index'])->name('quotations.index');
    Route::get('/quotations/create', [QuotationController::class, 'create'])->name('quotations.create');
    Route::post('/quotations', [QuotationController::class, 'store'])->name('quotations.store');
    Route::get('/quotations/{quotation}', [QuotationController::class, 'show'])->name('quotations.show');
    Route::get('/quotations/{quotation}/edit', [QuotationController::class, 'edit'])->name('quotations.edit');
    Route::put('/quotations/{quotation}', [QuotationController::class, 'update'])->name('quotations.update');
    Route::delete('/quotations/{quotation}', [QuotationController::class, 'destroy'])->name('quotations.destroy');
    Route::patch('/quotations/{quotation}/status', [QuotationController::class, 'updateStatus'])->name('quotations.status');

    // Services module
    Route::get('/services', [ServicesController::class, 'index'])->name('services.index');
    Route::get('/services/type/{serviceType}', [ServicesController::class, 'serviceTypeShow'])->name('services.type');
    Route::get('/services/create', [ServicesController::class, 'create'])->name('services.create');
    Route::post('/services', [ServicesController::class, 'store'])->name('services.store');
    Route::get('/services/{service}', [ServicesController::class, 'show'])->name('services.show');
    Route::get('/services/{service}/edit', [ServicesController::class, 'edit'])->name('services.edit');
    Route::patch('/services/{service}', [ServicesController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}', [ServicesController::class, 'destroy'])->name('services.destroy');

    // Leads module
    Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
    Route::get('/leads/create', [LeadController::class, 'create'])->name('leads.create');
    Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');
    Route::get('/leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
    Route::get('/leads/{lead}/edit', [LeadController::class, 'edit'])->name('leads.edit');
    Route::patch('/leads/{lead}', [LeadController::class, 'update'])->name('leads.update');
    Route::delete('/leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');
    Route::patch('/leads/{lead}/status', [LeadController::class, 'updateStatus'])->name('leads.status');
    Route::patch('/leads/{lead}/assign', [LeadController::class, 'assignAgent'])->name('leads.assign');
    Route::post('/leads/{lead}/follow-ups', [LeadController::class, 'storeFollowUp'])->name('leads.follow-ups.store');
    Route::patch('/follow-ups/{followUp}/complete', [LeadController::class, 'completeFollowUp'])->name('follow-ups.complete');
    Route::post('/leads/{lead}/convert', [LeadController::class, 'convertToCustomer'])->name('leads.convert');

    // Customers module
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}/services', [CustomerController::class, 'services'])->name('customers.services');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::patch('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::post('/customers/{customer}/follow-ups', [CustomerController::class, 'storeFollowUp'])->name('customers.follow-ups.store');
    Route::patch('/customer-follow-ups/{followUp}/complete', [CustomerController::class, 'completeFollowUp'])->name('customer-follow-ups.complete');

    // Placeholder pages for modules that will be built later.
    foreach (['bookings', 'payments', 'documents', 'follow-ups', 'reports', 'settings'] as $page) {
        Route::get('/' . $page, [PageController::class, 'show'])
            ->defaults('page', $page)
            ->name($page . '.index');
    }

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
