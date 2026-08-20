<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerFollowUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'agent_id',
        'note',
        'follow_up_date',
        'completed_at',
    ];

    protected $casts = [
        'follow_up_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
}
