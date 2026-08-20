<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Convert the customers.service column to a JSON array of services.
     */
    public function up(): void
    {
        $customers = DB::table('customers')
            ->whereNotNull('service')
            ->where('service', '<>', '')
            ->get(['id', 'service']);

        foreach ($customers as $customer) {
            $decoded = json_decode($customer->service);

            if (!is_array($decoded)) {
                DB::table('customers')->where('id', $customer->id)->update([
                    'service' => json_encode([$customer->service]),
                ]);
            }
        }

        DB::statement('ALTER TABLE customers MODIFY service JSON NULL');
    }

    /**
     * Revert the customers.service column back to a single service string.
     */
    public function down(): void
    {
        $customers = DB::table('customers')->whereNotNull('service')->get(['id', 'service']);

        foreach ($customers as $customer) {
            $services = json_decode($customer->service, true);

            DB::table('customers')->where('id', $customer->id)->update([
                'service' => is_array($services) && $services ? $services[0] : null,
            ]);
        }

        DB::statement('ALTER TABLE customers MODIFY service VARCHAR(255) NULL');
    }
};
