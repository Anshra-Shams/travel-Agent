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

    public function getSpecificRequirementsAttribute(): array
    {
        $specific = collect();

        if ($this->service_type === 'Flight Ticket') {
            $specific = collect([
                ['Departure', $this->departure],
                ['Arrival', $this->arrival],
                ['Departure Date', $this->departure_date?->format('d M Y')],
                ['Return Date', $this->return_date?->format('d M Y')],
                ['Trip Type', $this->trip_type],
                ['Passenger Count', $this->passenger_count],
                ['Preferred Airline', $this->preferred_airline],
                ['Class', $this->flight_class],
            ]);
        } elseif ($this->service_type === 'Visa') {
            $specific = collect([
                ['Country', $this->visa_country],
                ['Visa Type', $this->visa_type],
                ['Number of Applicants', $this->applicants],
                ['Visa Requirements', $this->visa_requirements],
            ]);
        } elseif ($this->service_type === 'Hotel') {
            $specific = collect([
                ['Hotel Preference', $this->hotel_preference],
                ['Check-in', $this->check_in?->format('d M Y')],
                ['Check-out', $this->check_out?->format('d M Y')],
                ['Rooms', $this->rooms],
                ['Adults', $this->adults],
                ['Children', $this->children],
                ['Room Type', $this->room_type],
            ]);
        } elseif (in_array($this->service_type, ['Umrah', 'Hajj'], true)) {
            $specific = collect([
                ['Package Type', $this->package_type],
                ['Makkah Hotel', $this->makkah_hotel],
                ['Madinah Hotel', $this->madinah_hotel],
                ['Nights in Makkah', $this->makkah_nights],
                ['Nights in Madinah', $this->madinah_nights],
            ]);
            if ($this->transport_requirement || $this->visa_requirement || $this->ticket_requirement) {
                $included = collect();
                if ($this->transport_requirement) $included->push('Transport');
                if ($this->visa_requirement) $included->push('Visa');
                if ($this->ticket_requirement) $included->push('Ticket');
                $specific->push(['Included in Package', $included->implode(', ')]);
            }
        } elseif ($this->service_type === 'Worldwide Tour Package') {
            $specific = collect([
                ['Package Type', $this->package_type],
                ['Duration', $this->duration],
                ['Hotel Requirement', $this->hotel_requirement],
                ['Transport Included', $this->transport_requirement ? 'Yes' : 'No'],
                ['Activities / Special Requirements', $this->activities],
            ]);
        } elseif ($this->service_type === 'Transportation') {
            $specific = collect([
                ['Pickup Location', $this->pickup_location],
                ['Drop-off Location', $this->dropoff_location],
                ['Pickup Date', $this->pickup_date?->format('d M Y')],
                ['Pickup Time', $this->pickup_time],
                ['Vehicle Type', $this->vehicle_type],
                ['Number of Passengers', $this->passengers],
            ]);
        }

        return $specific
            ->filter(fn ($row) => filled($row[1]))
            ->map(fn ($row) => ['label' => $row[0], 'value' => $row[1]])
            ->values()
            ->toArray();
    }
}
