<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Enforce one requirement record per customer per service type.
     */
    public function up(): void
    {
        // Remove any accidental duplicates, keeping the earliest record per customer+type.
        $dupes = DB::table('customer_services')
            ->select('customer_id', 'service_type', DB::raw('MIN(id) as keep_id'))
            ->groupBy('customer_id', 'service_type')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($dupes as $dupe) {
            DB::table('customer_services')
                ->where('customer_id', $dupe->customer_id)
                ->where('service_type', $dupe->service_type)
                ->where('id', '!=', $dupe->keep_id)
                ->delete();
        }

        Schema::table('customer_services', function ($table) {
            $table->unique(['customer_id', 'service_type']);
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::table('customer_services', function ($table) {
            $table->dropUnique(['customer_id', 'service_type']);
        });
    }
};
