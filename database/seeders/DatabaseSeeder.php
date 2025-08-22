<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Verificar si ya existe un usuario de prueba
        if (!User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
            $this->command->info('Test user created successfully!');
        } else {
            $this->command->info('Test user already exists. Skipping creation.');
        }

        // Seed portfolio data
        $this->call([
            AdminUserSeeder::class,
            ProjectSeeder::class,
            WorkSeeder::class,
        ]);
    }
}
