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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('service');
            $table->string('destination')->nullable();
            $table->date('travel_date')->nullable();
            $table->unsignedInteger('travelers')->default(1);
            $table->string('source')->nullable();
            $table->foreignId('agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('New');
            $table->date('follow_up_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
