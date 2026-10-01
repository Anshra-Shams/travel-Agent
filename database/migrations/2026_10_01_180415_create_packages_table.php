<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('service_type_id')
                ->constrained('service_types')
                ->cascadeOnDelete();

            $table->string('name');
            $table->text('description')->nullable();

            $table->date('departure_date')->nullable();
            $table->date('return_date')->nullable();
            $table->unsignedInteger('duration_days')->nullable();

            $table->decimal('original_price', 12, 2)->nullable();
            $table->decimal('discount', 12, 2)->nullable();
            $table->decimal('final_price', 12, 2)->nullable();

            $table->string('currency', 10)->default('PKR');
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->text('terms')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};