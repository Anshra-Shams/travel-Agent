<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->string('status', 50)->default('Draft')->change();
        });
    }

    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->enum('status', ['Draft', 'Sent', 'Accepted', 'Rejected', 'Expired', 'Cancelled'])
                ->default('Draft')->change();
        });
    }
};