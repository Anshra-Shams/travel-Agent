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
        Schema::create('customer_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('service_type');
            $table->string('status')->default('New');

            // Common service fields
            $table->string('destination')->nullable();
            $table->date('travel_date')->nullable();
            $table->unsignedInteger('travelers')->nullable();
            $table->text('requirements')->nullable();

            // Flight Ticket
            $table->string('departure')->nullable();
            $table->string('arrival')->nullable();
            $table->date('departure_date')->nullable();
            $table->date('return_date')->nullable();
            $table->string('trip_type')->nullable();
            $table->unsignedInteger('passenger_count')->nullable();
            $table->string('preferred_airline')->nullable();
            $table->string('flight_class')->nullable();

            // Visa
            $table->string('visa_country')->nullable();
            $table->string('visa_type')->nullable();
            $table->unsignedInteger('applicants')->nullable();
            $table->text('visa_requirements')->nullable();

            // Hotel
            $table->string('hotel_preference')->nullable();
            $table->date('check_in')->nullable();
            $table->date('check_out')->nullable();
            $table->unsignedInteger('rooms')->nullable();
            $table->unsignedInteger('adults')->nullable();
            $table->unsignedInteger('children')->nullable();
            $table->string('room_type')->nullable();

            // Umrah / Hajj / Tour Package
            $table->string('package_type')->nullable();
            $table->string('makkah_hotel')->nullable();
            $table->string('madinah_hotel')->nullable();
            $table->unsignedInteger('makkah_nights')->nullable();
            $table->unsignedInteger('madinah_nights')->nullable();
            $table->boolean('transport_requirement')->default(false);
            $table->boolean('visa_requirement')->default(false);
            $table->boolean('ticket_requirement')->default(false);
            $table->string('hotel_requirement')->nullable();

            // Worldwide Tour Package
            $table->string('duration')->nullable();
            $table->text('activities')->nullable();

            // Transportation
            $table->string('pickup_location')->nullable();
            $table->string('dropoff_location')->nullable();
            $table->date('pickup_date')->nullable();
            $table->string('pickup_time')->nullable();
            $table->string('vehicle_type')->nullable();
            $table->unsignedInteger('passengers')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_services');
    }
};
