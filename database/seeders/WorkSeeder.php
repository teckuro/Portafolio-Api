<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Work;

class WorkSeeder extends Seeder
{
    public function run(): void
    {
        $works = [
            [
                'company' => 'Tech Solutions Inc.',
                'position' => 'Senior Full Stack Developer',
                'description' => 'Desarrollo de aplicaciones web complejas utilizando tecnologías modernas. Lideré un equipo de 5 desarrolladores y implementé mejores prácticas de desarrollo.',
                'start_date' => '2023-01',
                'end_date' => null,
                'location' => 'Madrid, España',
                'tech' => ['Angular', 'Laravel', 'PHP', 'TypeScript', 'MySQL', 'Docker'],
                'achievements' => [
                    'Reduje el tiempo de carga de la aplicación en un 40%',
                    'Implementé CI/CD con GitHub Actions',
                    'Mentoré a 3 desarrolladores junior'
                ],
                'is_current' => true,
                'company_url' => 'https://techsolutions.com',
                'status' => 'active'
            ],
            [
                'company' => 'Digital Innovations',
                'position' => 'Frontend Developer',
                'description' => 'Desarrollo de interfaces de usuario responsivas y accesibles. Trabajé en proyectos para clientes internacionales.',
                'start_date' => '2021-03',
                'end_date' => '2022-12',
                'location' => 'Barcelona, España',
                'tech' => ['React', 'Vue.js', 'JavaScript', 'CSS3', 'SASS', 'Webpack'],
                'achievements' => [
                    'Desarrollé 15+ componentes reutilizables',
                    'Mejoré la accesibilidad web (WCAG 2.1)',
                    'Optimicé el rendimiento de 3 aplicaciones principales'
                ],
                'is_current' => false,
                'company_url' => 'https://digitalinnovations.com',
                'status' => 'active'
            ],
            [
                'company' => 'StartUp Ventures',
                'position' => 'Junior Developer',
                'description' => 'Primera experiencia profesional desarrollando aplicaciones web desde cero. Aprendí metodologías ágiles y trabajo en equipo.',
                'start_date' => '2020-06',
                'end_date' => '2021-02',
                'location' => 'Valencia, España',
                'tech' => ['PHP', 'MySQL', 'HTML5', 'CSS3', 'JavaScript', 'jQuery'],
                'achievements' => [
                    'Desarrollé 2 aplicaciones web completas',
                    'Implementé sistema de autenticación',
                    'Participé en el diseño de la arquitectura de datos'
                ],
                'is_current' => false,
                'company_url' => 'https://startupventures.com',
                'status' => 'active'
            ]
        ];

        foreach ($works as $work) {
            Work::create($work);
        }
    }
}
