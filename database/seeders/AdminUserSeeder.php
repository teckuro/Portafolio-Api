<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminUser;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminUsers = [
    {
        'name': 'Admin',
        'email': 'admin@portfolio.com',
        'password': '$2y$12$RePzM3Pistk7\/TziQF8Agua6RXgxIAnqRrIhIRJ8E4DUixU7eKHX2',
        'role': 'admin'
    }
];

        foreach ($adminUsers as $adminUser) {
            AdminUser::create($adminUser);
        }
    }
}