<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const LEGACY_MAP = [
        'Tickets' => 'Flight Ticket',
        'Hotels' => 'Hotel',
        'Tour Packages' => 'Worldwide Tour Package',
    ];

    public function up(): void
    {
        $customers = DB::table('customers')
            ->whereNotNull('service')
            ->where('service', '<>', '[]')
            ->get();

        foreach ($customers as $customer) {
            $services = json_decode($customer->service, true);

            if (!is_array($services)) {
                continue;
            }

            $normalized = array_map(fn (string $s) => self::LEGACY_MAP[$s] ?? $s, $services);
            $normalized = array_values(array_unique($normalized));

            if ($normalized !== $services) {
                DB::table('customers')
                    ->where('id', $customer->id)
                    ->update(['service' => json_encode($normalized)]);
            }
        }
    }

    public function down(): void
    {
        $reverseMap = array_flip(self::LEGACY_MAP);

        $customers = DB::table('customers')
            ->whereNotNull('service')
            ->where('service', '<>', '[]')
            ->get();

        foreach ($customers as $customer) {
            $services = json_decode($customer->service, true);

            if (!is_array($services)) {
                continue;
            }

            $reverted = array_map(fn (string $s) => $reverseMap[$s] ?? $s, $services);
            $reverted = array_values(array_unique($reverted));

            if ($reverted !== $services) {
                DB::table('customers')
                    ->where('id', $customer->id)
                    ->update(['service' => json_encode($reverted)]);
            }
        }
    }
};
