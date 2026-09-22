<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visa_applications', function (Blueprint $table) {
            $table->id();

            // Customer
            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnDelete();

            // Visa information
            $table->string('visa_type');
            $table->string('country');
            $table->string('application_number')->nullable();

            // Dates
            $table->date('application_date')->nullable();
            $table->date('expiry_date')->nullable();

            // Status
            $table->enum('status', [
                'Pending',
                'Submitted',
                'Approved',
                'Rejected',
                'Expired'
            ])->default('Pending');

            // Required documents
            $table->json('required_documents')->nullable();

            // Additional information
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visa_applications');
    }
};