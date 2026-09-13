<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    public const TYPES = [
        'booking' => 'Booking',
        'quotation' => 'Quotation',
    ];

    public const STATUSES = [
        'Draft',
        'Sent',
        'Accepted',
        'Rejected',
        'Expired',
        'Cancelled',
    ];

    public const BOOKING_STATUSES = [
        'Pending',
        'Processing',
        'Confirmed',
        'Completed',
        'Cancelled',
    ];

    public const PAYMENT_STATUSES = [
        'Pending',
        'Partial',
        'Paid',
    ];

    protected $fillable = [
        'type',
        'quotation_number',
        'customer_id',
        'agent_id',
        'account_id',
        'customer_service_id',
        'service_type',
        'services',
        'destination',
        'quotation_date',
        'valid_until',
        'travel_date',
        'status',
        'payment_status',
        'reference',
        'subtotal',
        'discount',
        'tax',
        'grand_total',
        'payment_terms',
        'deposit_required',
        'remaining_amount',
        'payment_due_date',
        'notes',
        'terms_conditions',
    ];

    protected $casts = [
        'quotation_date' => 'date',
        'valid_until' => 'date',
        'travel_date' => 'date',
        'payment_due_date' => 'date',
        'services' => 'array',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'deposit_required' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (Quotation $quotation) {
            if (!$quotation->quotation_number) {
                $quotation->quotation_number = self::generateNumber($quotation->type ?? 'quotation');
            }
            if (!$quotation->agent_id) {
                $quotation->agent_id = auth()->id();
            }
        });
    }

    public static function generateNumber(string $type = 'quotation'): string
    {
        $prefix = $type === 'booking' ? 'BK' : 'QT';
        $last = self::where('quotation_number', 'like', $prefix . '-%')
            ->orderByDesc('quotation_number')
            ->value('quotation_number');
        if ($last && preg_match('/' . $prefix . '-(\d+)/', $last, $m)) {
            $next = (int) $m[1] + 1;
        } else {
            $next = 1;
        }
        return $prefix . '-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function serviceRequirement()
    {
        return $this->belongsTo(CustomerService::class, 'customer_service_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class)->orderBy('sort_order');
    }

    public function activities()
    {
        return $this->hasMany(QuotationActivity::class)->latest();
    }

    public function recalculate(): self
    {
        $this->subtotal = $this->items->sum('total');
        $this->grand_total = $this->subtotal - $this->discount + $this->tax;
        if ($this->deposit_required !== null) {
            $this->remaining_amount = max(0, $this->grand_total - $this->deposit_required);
        }
        return $this;
    }

    public function getServicesListAttribute(): array
    {
        $services = $this->services;

        if (is_string($services)) {
            $services = json_decode($services, true);
        }

        if (!is_array($services) || empty($services)) {
            return array_values(array_filter([
                $this->service_type,
            ], fn ($s) => is_string($s) && trim($s) !== ''));
        }

        return array_values(array_filter(
            $services,
            fn ($s) => is_string($s) && trim($s) !== ''
        ));
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? ucfirst($this->type);
    }

    public function getReferenceLabelAttribute(): string
    {
        if ($this->type === 'booking') {
            return $this->payment_status ?: '—';
        }
        return $this->reference ?: '—';
    }

    public function getIsBookingAttribute(): bool
    {
        return $this->type === 'booking';
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'Draft' => 'bg-gray-50 text-gray-700 ring-gray-200',
            'Sent' => 'bg-sky-50 text-sky-700 ring-sky-200',
            'Accepted' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
            'Rejected' => 'bg-rose-50 text-rose-700 ring-rose-200',
            'Expired' => 'bg-amber-50 text-amber-700 ring-amber-200',
            'Cancelled' => 'bg-slate-100 text-slate-600 ring-slate-200',
            'Pending' => 'bg-amber-50 text-amber-700 ring-amber-200',
            'Processing' => 'bg-sky-50 text-sky-700 ring-sky-200',
            'Confirmed' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
            'Completed' => 'bg-teal-50 text-teal-700 ring-teal-200',
            default => 'bg-gray-50 text-gray-700 ring-gray-200',
        };
    }

    public function getPaymentStatusColorAttribute(): string
    {
        return match ($this->payment_status) {
            'Pending' => 'bg-amber-50 text-amber-700 ring-amber-200',
            'Partial' => 'bg-sky-50 text-sky-700 ring-sky-200',
            'Paid' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
            default => 'bg-gray-50 text-gray-500 ring-gray-200',
        };
    }
}