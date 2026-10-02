<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'service_type_id',
        'package_id',
        'quantity',
        'unit_price',
        'subtotal',
        'discount',
        'grand_total',
        'paid_amount',
        'currency',
        'status',
        'issued_at',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'float',
        'subtotal' => 'float',
        'discount' => 'float',
        'grand_total' => 'float',
        'paid_amount' => 'float',
        'issued_at' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function getRemainingAmountAttribute(): float
    {
        return max(
            0,
            (float) $this->grand_total - (float) $this->paid_amount
        );
    }
}