<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        // Verificar si ya existen registros
        if (Project::count() > 0) {
            $this->command->info('Projects table already has data. Skipping seeding.');
            return;
        }

        $baseUrl = 'https://web-production-eeecb.up.railway.app/api/files/projects';

        $projects = [
            [
                'title' => 'Portfolio Personal',
                'description' => 'Portfolio personal desarrollado con Angular y Laravel. Incluye gestión de proyectos, experiencia laboral y panel de administración completo con autenticación y autorización.',
                'short_description' => 'Portfolio personal con Angular y Laravel',
                'image_url' => "{$baseUrl}/portfoliopersonal.svg",
                'project_url' => 'https://portfolio.example.com',
                'github_url' => 'https://github.com/username/portfolio',
                'tech_stack' => [
                    'Angular',
                    'Laravel',
                    'PHP',
                    'TypeScript',
                    'CSS',
                    'SQLite'
                ],
                'features' => [
                    'Panel de administración',
                    'Gestión de proyectos',
                    'Experiencia laboral',
                    'Autenticación'
                ],
                'status' => 'active',
                'is_featured' => true,
                'order' => 1
            ],
            [
                'title' => 'E-commerce Platform',
                'description' => 'Plataforma de comercio electrónico completa con carrito de compras, gestión de productos, sistema de pagos integrado y panel de administración.',
                'short_description' => 'Plataforma de comercio electrónico completa',
                'image_url' => "{$baseUrl}/ecommerceplatform.svg",
                'project_url' => 'https://ecommerce.example.com',
                'github_url' => 'https://github.com/username/ecommerce',
                'tech_stack' => [
                    'React',
                    'Node.js',
                    'Express',
                    'MongoDB',
                    'Stripe'
                ],
                'features' => [
                    'Carrito de compras',
                    'Sistema de pagos',
                    'Gestión de productos',
                    'Panel admin'
                ],
                'status' => 'active',
                'is_featured' => true,
                'order' => 2
            ],
            [
                'title' => 'Task Management App',
                'description' => 'Aplicación de gestión de tareas con funcionalidades de drag & drop, filtros avanzados y notificaciones en tiempo real.',
                'short_description' => 'App de gestión de tareas con drag & drop',
                'image_url' => "{$baseUrl}/taskmanagementapp.svg",
                'project_url' => 'https://tasks.example.com',
                'github_url' => 'https://github.com/username/taskapp',
                'tech_stack' => [
                    'Vue.js',
                    'Laravel',
                    'WebSockets',
                    'MySQL',
                    'Redis'
                ],
                'features' => [
                    'Drag & Drop',
                    'Filtros avanzados',
                    'Notificaciones real-time',
                    'Colaboración'
                ],
                'status' => 'active',
                'is_featured' => false,
                'order' => 3
            ],
            [
                'title' => 'Weather Dashboard',
                'description' => 'Dashboard del clima con gráficos interactivos, pronósticos detallados y múltiples ubicaciones usando APIs externas.',
                'short_description' => 'Dashboard del clima con gráficos interactivos',
                'image_url' => "{$baseUrl}/weatherdashboard.svg",
                'project_url' => 'https://weather.example.com',
                'github_url' => 'https://github.com/username/weather',
                'tech_stack' => [
                    'Angular',
                    'Chart.js',
                    'OpenWeather API',
                    'Bootstrap'
                ],
                'features' => [
                    'Gráficos interactivos',
                    'Pronósticos detallados',
                    'Múltiples ubicaciones',
                    'API externa'
                ],
                'status' => 'active',
                'is_featured' => false,
                'order' => 4
            ]
        ];

        try {
            foreach ($projects as $project) {
                Project::create($project);
            }
            $this->command->info('Projects seeded successfully!');
        } catch (\Exception $e) {
            $this->command->error('Error seeding projects: ' . $e->getMessage());
            throw $e;
        }
    }
}