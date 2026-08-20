<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id',
        'agent_id',
        'type',
        'description',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
}
