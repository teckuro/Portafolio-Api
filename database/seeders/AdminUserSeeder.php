<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        AdminUser::create([
            'name' => 'Admin',
            'email' => 'admin@portfolio.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);
    }
}
