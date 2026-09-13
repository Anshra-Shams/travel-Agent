<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    public const TYPES = ['Debit', 'Credit'];

    public const STATUSES = ['Active', 'Inactive'];

    protected $fillable = [
        'code',
        'category_id',
        'name',
        'type',
        'opening_balance',
        'current_balance',
        'status',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function category()
    {
        return $this->belongsTo(AccountCategory::class, 'category_id');
    }

    public function transactions()
    {
        return $this->hasMany(AccountTransaction::class);
    }

    public function head(): Attribute
    {
        return Attribute::get(fn () => $this->category?->name ?? '—');
    }

    public static function generateCode(): string
    {
        $max = static::max('code');
        if ($max && preg_match('/(\d+)$/', $max, $m)) {
            return 'AC-' . str_pad(((int) $m[1]) + 1, 3, '0', STR_PAD_LEFT);
        }
        return 'AC-001';
    }
}