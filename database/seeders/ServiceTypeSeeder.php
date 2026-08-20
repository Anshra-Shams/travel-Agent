<?php

namespace Database\Seeders;

use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['name' => 'Flight Ticket', 'icon' => '✈️', 'description' => 'Domestic and international flight bookings', 'sort_order' => 1],
            ['name' => 'Visa', 'icon' => '🛂', 'description' => 'Visa processing and documentation', 'sort_order' => 2],
            ['name' => 'Hotel', 'icon' => '🏨', 'description' => 'Hotel reservations worldwide', 'sort_order' => 3],
            ['name' => 'Umrah', 'icon' => '🕋', 'description' => 'Complete Umrah pilgrimage packages', 'sort_order' => 4],
            ['name' => 'Hajj', 'icon' => '🕌', 'description' => 'Hajj pilgrimage packages and guidance', 'sort_order' => 5],
            ['name' => 'Worldwide Tour Package', 'icon' => '🌍', 'description' => 'Curated tour packages across the globe', 'sort_order' => 6],
            ['name' => 'Transportation', 'icon' => '🚗', 'description' => 'Airport transfers and ground transport', 'sort_order' => 7],
        ];

        foreach ($services as $service) {
            ServiceType::updateOrCreate(
                ['name' => $service['name']],
                $service
            );
        }
    }
}
