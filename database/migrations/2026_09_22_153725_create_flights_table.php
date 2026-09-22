```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();

            // Flight information
            $table->string('flight_number');
            $table->string('airline');

            // Route
            $table->string('departure_from');
            $table->string('destination_to');

            // Schedule
            $table->dateTime('departure_at')->nullable();
            $table->dateTime('arrival_at')->nullable();

            // Flight class
            $table->enum('class', [
                'Economy',
                'Business'
            ])->default('Economy');

            // Seats & price
            $table->integer('total_seats')->default(0);
            $table->decimal('price', 12, 2)->default(0);

            // Status
            $table->enum('status', [
                'Scheduled',
                'Confirmed',
                'Cancelled',
                'Completed'
            ])->default('Scheduled');

            // Additional information
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
