<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_name',
        'hotel_code',
        'city',
        'country',
        'address',
        'contact_person',
        'phone',
        'email',
        'room_type',
        'total_rooms',
        'price_per_night',
        'meal_plan',
        'check_in_time',
        'check_out_time',
        'status',
        'notes',
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
    ];
}