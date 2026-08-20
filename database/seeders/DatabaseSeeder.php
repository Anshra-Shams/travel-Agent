<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@travelagent.com'],
            [
                'name' => 'Admin',
                'role' => 'admin',
                'password' => Hash::make('admin123'),
            ]
        );

        $agents = [
            ['name' => 'Ali Raza', 'email' => 'ali@travelagent.com'],
            ['name' => 'Sara Ahmed', 'email' => 'sara@travelagent.com'],
            ['name' => 'Imran Malik', 'email' => 'imran@travelagent.com'],
        ];

        foreach ($agents as $agent) {
            User::updateOrCreate(
                ['email' => $agent['email']],
                [
                    'name' => $agent['name'],
                    'role' => 'agent',
                    'password' => Hash::make('agent123'),
                ]
            );
        }
    }
}
