<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== DEFAULT ADMIN (only one) =====
        User::updateOrCreate(
            ['email' => 'admin@rkvitsolutions.com'],
            [
                'name' => 'RKVIT Admin',
                'password' => Hash::make('Admin@123'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // ===== Demo Customer =====
        User::updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Demo Customer',
                'password' => Hash::make('Customer@123'),
                'role' => 'customer',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
