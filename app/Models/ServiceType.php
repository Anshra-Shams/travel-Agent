<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'description',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public const COLOR_PALETTE = [
        ['icon_bg' => 'bg-sky-100', 'icon_text' => 'text-sky-600', 'ring' => 'ring-sky-200', 'badge' => 'bg-sky-50 text-sky-700'],
        ['icon_bg' => 'bg-indigo-100', 'icon_text' => 'text-indigo-600', 'ring' => 'ring-indigo-200', 'badge' => 'bg-indigo-50 text-indigo-700'],
        ['icon_bg' => 'bg-rose-100', 'icon_text' => 'text-rose-600', 'ring' => 'ring-rose-200', 'badge' => 'bg-rose-50 text-rose-700'],
        ['icon_bg' => 'bg-emerald-100', 'icon_text' => 'text-emerald-600', 'ring' => 'ring-emerald-200', 'badge' => 'bg-emerald-50 text-emerald-700'],
        ['icon_bg' => 'bg-amber-100', 'icon_text' => 'text-amber-600', 'ring' => 'ring-amber-200', 'badge' => 'bg-amber-50 text-amber-700'],
        ['icon_bg' => 'bg-cyan-100', 'icon_text' => 'text-cyan-600', 'ring' => 'ring-cyan-200', 'badge' => 'bg-cyan-50 text-cyan-700'],
        ['icon_bg' => 'bg-slate-200', 'icon_text' => 'text-slate-600', 'ring' => 'ring-slate-200', 'badge' => 'bg-slate-100 text-slate-700'],
        ['icon_bg' => 'bg-violet-100', 'icon_text' => 'text-violet-600', 'ring' => 'ring-violet-200', 'badge' => 'bg-violet-50 text-violet-700'],
        ['icon_bg' => 'bg-pink-100', 'icon_text' => 'text-pink-600', 'ring' => 'ring-pink-200', 'badge' => 'bg-pink-50 text-pink-700'],
        ['icon_bg' => 'bg-teal-100', 'icon_text' => 'text-teal-600', 'ring' => 'ring-teal-200', 'badge' => 'bg-teal-50 text-teal-700'],
    ];

    public static function getColorForIndex(int $index): array
    {
        return self::COLOR_PALETTE[$index % count(self::COLOR_PALETTE)];
    }

    public static function getActiveNames(): array
    {
        return static::where('status', 'active')->orderBy('sort_order')->pluck('name')->toArray();
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function getCustomersAttribute()
    {
        return Customer::whereJsonContains('service', $this->name)
            ->with(['services' => fn ($q) => $q->where('service_type', $this->name)])
            ->orderBy('name')
            ->get();
    }

    public function getCustomerCountAttribute(): int
    {
        return Customer::whereJsonContains('service', $this->name)->count();
    }
}
