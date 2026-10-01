<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_type_id',
        'name',
        'description',
        'departure_date',
        'return_date',
        'duration_days',
        'original_price',
        'discount',
        'final_price',
        'currency',
        'status',
        'terms',
        'details',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',
        'duration_days' => 'integer',
        'original_price' => 'float',
        'discount' => 'float',
        'final_price' => 'float',
        'details' => 'array',
    ];

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }
}