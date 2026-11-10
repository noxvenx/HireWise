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
            ['email' => 'admin@hirewise.local'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('GantiPassword123!'),
                'role' => 'super_admin',
                'is_active' => true,
            ]
        );
    }
}