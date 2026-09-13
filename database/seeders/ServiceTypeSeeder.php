<?php

namespace Database\Seeders;

use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['name' => 'Flight Ticket', 'icon' => '✈️', 'description' => 'Domestic and international flight bookings', 'amount' => 120000, 'sort_order' => 1],
            ['name' => 'Visa', 'icon' => '🛂', 'description' => 'Visa processing and documentation', 'amount' => 35000, 'sort_order' => 2],
            ['name' => 'Hotel', 'icon' => '🏨', 'description' => 'Hotel reservations worldwide', 'amount' => 85000, 'sort_order' => 3],
            ['name' => 'Umrah', 'icon' => '🕋', 'description' => 'Complete Umrah pilgrimage packages', 'amount' => 350000, 'sort_order' => 4],
            ['name' => 'Hajj', 'icon' => '🕌', 'description' => 'Hajj pilgrimage packages and guidance', 'amount' => 1450000, 'sort_order' => 5],
            ['name' => 'Worldwide Tour Package', 'icon' => '🌍', 'description' => 'Curated tour packages across the globe', 'amount' => 500000, 'sort_order' => 6],
            ['name' => 'Transportation', 'icon' => '🚗', 'description' => 'Airport transfers and ground transport', 'amount' => 15000, 'sort_order' => 7],
        ];

        foreach ($services as $service) {
            ServiceType::updateOrCreate(
                ['name' => $service['name']],
                $service
            );
        }
    }
}
