<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@hirewise'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('Admin123!'),
                'role' => 'super_admin',
                'is_active' => true,
            ]
        );
    }
}