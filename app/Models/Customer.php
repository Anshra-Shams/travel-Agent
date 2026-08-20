<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    public const SOURCES = [
        'direct' => 'Direct',
        'lead' => 'Lead Conversion',
    ];

    protected $fillable = [
        'lead_id',
        'name',
        'phone',
        'email',
        'whatsapp',
        'cnic',
        'passport_number',
        'passport_expiry',
        'date_of_birth',
        'gender',
        'address',
        'country',
        'service',
        'destination',
        'travel_date',
        'travelers',
        'source',
        'status',
        'customer_source',
        'notes',
    ];

    protected $casts = [
        'travel_date' => 'date',
        'passport_expiry' => 'date',
        'date_of_birth' => 'date',
        'travelers' => 'integer',
        'service' => 'array',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function followUps()
    {
        return $this->hasMany(CustomerFollowUp::class)->latest();
    }

    public function activities()
    {
        return $this->hasMany(CustomerActivity::class)->latest();
    }

    public function services()
    {
        return $this->hasMany(CustomerService::class)->latest();
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class)->latest();
    }

    public function getSourceLabelAttribute()
    {
        return self::SOURCES[$this->customer_source] ?? ucfirst($this->customer_source);
    }

    public function getServicesListAttribute()
    {
        $services = $this->service;

        if (is_string($services)) {
            $services = json_decode($services, true);
        }

        return array_values(array_filter(
            (array) $services,
            fn ($service) => is_string($service) && trim($service) !== ''
        ));
    }
}
