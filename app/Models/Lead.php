<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'phone',
        'email',
        'whatsapp',
        'service',
        'destination',
        'travel_date',
        'travelers',
        'source',
        'agent_id',
        'status',
        'follow_up_date',
        'notes',
    ];

    protected $casts = [
        'travel_date' => 'date',
        'follow_up_date' => 'date',
        'travelers' => 'integer',
    ];

    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function followUps()
    {
        return $this->hasMany(FollowUp::class)->latest();
    }

    public function activities()
    {
        return $this->hasMany(Activity::class)->latest();
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }
}
