<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->string('type')->default('quotation')->after('id')->index();
            $table->json('services')->nullable()->after('service_type');
            $table->string('payment_status')->nullable()->after('status');
            $table->date('travel_date')->nullable()->after('valid_until');
            $table->string('reference')->nullable()->after('payment_status');
        });

        Schema::table('quotations', function (Blueprint $table) {
            $table->date('valid_until')->nullable()->change();
        });

        // Backfill type and services for legacy quotation rows.
        $rows = DB::table('quotations')->whereNull('type')->get(['id', 'service_type']);
        foreach ($rows as $row) {
            DB::table('quotations')
                ->where('id', $row->id)
                ->update([
                    'type' => 'quotation',
                    'services' => json_encode([$row->service_type]),
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn(['type', 'services', 'payment_status', 'travel_date', 'reference']);
        });
    }
};