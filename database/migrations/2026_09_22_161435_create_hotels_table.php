<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();

            $table->string('hotel_name');
            $table->string('hotel_code')->unique();

            $table->string('city');
            $table->string('country');

            $table->text('address')->nullable();

            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            $table->string('room_type')->nullable();
            $table->integer('total_rooms')->default(0);

            $table->decimal('price_per_night', 12, 2)->default(0);

            $table->string('meal_plan')->nullable();

            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();

            $table->enum('status', [
                'Active',
                'Inactive'
            ])->default('Active');

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};