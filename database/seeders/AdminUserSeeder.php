<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminUser;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Verificar si ya existe un admin user
        if (AdminUser::count() > 0) {
            $this->command->info('Admin users table already has data. Skipping seeding.');
            return;
        }

        $adminUsers = [
            [
                'name' => 'Admin',
                'email' => 'admin@portfolio.com',
                'password' => '$2y$12$RePzM3Pistk7/TziQF8Agua6RXgxIAnqRrIhIRJ8E4DUixU7eKHX2',
                'role' => 'admin'
            ]
        ];

        try {
            foreach ($adminUsers as $adminUser) {
                AdminUser::create($adminUser);
            }
            $this->command->info('Admin users seeded successfully!');
        } catch (\Exception $e) {
            $this->command->error('Error seeding admin users: ' . $e->getMessage());
            throw $e;
        }
    }
}