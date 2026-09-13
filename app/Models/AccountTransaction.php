<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'date',
        'reference',
        'description',
        'debit',
        'credit',
    ];

    protected $casts = [
        'date' => 'date',
        'debit' => 'decimal:2',
        'credit' => 'decimal:2',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function recalculateBalance(Account $account): void
    {
        $total = $account->opening_balance + $account->transactions()->sum('credit') - $account->transactions()->sum('debit');

        $account->update(['current_balance' => $total]);
    }
}