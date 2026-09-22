<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisaApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'visa_type',
        'country',
        'application_number',
        'application_date',
        'expiry_date',
        'status',
        'required_documents',
        'notes',
    ];

    protected $casts = [
        'application_date' => 'date',
        'expiry_date' => 'date',
        'required_documents' => 'array',
    ];

    /**
     * Visa belongs to a customer.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}