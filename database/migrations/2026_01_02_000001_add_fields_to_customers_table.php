<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('cnic')->nullable()->after('whatsapp');
            $table->string('passport_number')->nullable()->after('cnic');
            $table->date('passport_expiry')->nullable()->after('passport_number');
            $table->date('date_of_birth')->nullable()->after('passport_expiry');
            $table->string('gender')->nullable()->after('date_of_birth');
            $table->string('address')->nullable()->after('gender');
            $table->string('country')->nullable()->after('address');
            $table->string('customer_source')->default('direct')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'cnic',
                'passport_number',
                'passport_expiry',
                'date_of_birth',
                'gender',
                'address',
                'country',
                'customer_source',
            ]);
        });
    }
};
