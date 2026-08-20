<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('quotation_number')->unique();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('customer_service_id')->nullable()->constrained('customer_services')->nullOnDelete();
            $table->string('service_type');
            $table->string('destination')->nullable();

            $table->date('quotation_date');
            $table->date('valid_until');
            $table->enum('status', ['Draft', 'Sent', 'Accepted', 'Rejected', 'Expired', 'Cancelled'])->default('Draft');

            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);

            $table->text('payment_terms')->nullable();
            $table->decimal('deposit_required', 12, 2)->nullable();
            $table->decimal('remaining_amount', 12, 2)->nullable();
            $table->date('payment_due_date')->nullable();
            $table->text('notes')->nullable();
            $table->text('terms_conditions')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
