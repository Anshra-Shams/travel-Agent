<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerService extends Model
{
    use HasFactory;

    public const STATUSES = [
        'New',
        'Requirements Pending',
        'Ready for Quotation',
        'Processing',
        'Completed',
        'Cancelled',
    ];

    protected $fillable = [
        'customer_id',
        'agent_id',
        'service_type',
        'status',

        // Common
        'destination',
        'travel_date',
        'travelers',
        'requirements',

        // Flight Ticket
        'departure',
        'arrival',
        'departure_date',
        'return_date',
        'trip_type',
        'passenger_count',
        'preferred_airline',
        'flight_class',

        // Visa
        'visa_country',
        'visa_type',
        'applicants',
        'visa_requirements',

        // Hotel
        'hotel_preference',
        'check_in',
        'check_out',
        'rooms',
        'adults',
        'children',
        'room_type',

        // Umrah / Hajj / Tour Package
        'package_type',
        'makkah_hotel',
        'madinah_hotel',
        'makkah_nights',
        'madinah_nights',
        'transport_requirement',
        'visa_requirement',
        'ticket_requirement',
        'hotel_requirement',

        // Worldwide Tour Package
        'duration',
        'activities',

        // Transportation
        'pickup_location',
        'dropoff_location',
        'pickup_date',
        'pickup_time',
        'vehicle_type',
        'passengers',
    ];

    protected $casts = [
        'travel_date' => 'date',
        'departure_date' => 'date',
        'return_date' => 'date',
        'check_in' => 'date',
        'check_out' => 'date',
        'pickup_date' => 'date',
        'travelers' => 'integer',
        'passenger_count' => 'integer',
        'applicants' => 'integer',
        'rooms' => 'integer',
        'adults' => 'integer',
        'children' => 'integer',
        'makkah_nights' => 'integer',
        'madinah_nights' => 'integer',
        'passengers' => 'integer',
        'transport_requirement' => 'boolean',
        'visa_requirement' => 'boolean',
        'ticket_requirement' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class, 'customer_service_id');
    }
}
