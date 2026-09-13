<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Convert the leads.service column from a single value to a JSON array
     * so leads can track multiple interested services.
     */
    public function up(): void
    {
        DB::table('leads')->orderBy('id')->chunkById(100, function ($leads) {
            foreach ($leads as $lead) {
                if ($lead->service !== null && !is_array(json_decode($lead->service, true))) {
                    DB::table('leads')->where('id', $lead->id)->update([
                        'service' => json_encode([$lead->service]),
                    ]);
                }
            }
        });

        DB::statement('ALTER TABLE leads MODIFY service LONGTEXT NULL');
    }

    /**
     * Convert back to a single service value and restore the column type.
     */
    public function down(): void
    {
        DB::table('leads')->orderBy('id')->chunkById(100, function ($leads) {
            foreach ($leads as $lead) {
                $services = json_decode($lead->service ?? '', true);
                $first = is_array($services) ? ($services[0] ?? null) : null;

                DB::table('leads')->where('id', $lead->id)->update([
                    'service' => is_string($first) ? $first : 'Flight Ticket',
                ]);
            }
        });

        DB::statement('ALTER TABLE leads MODIFY service VARCHAR(255) NOT NULL');
    }
};