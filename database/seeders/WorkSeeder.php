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
                'company' => 'SERVICIOS Y TECNOLOGIA LIMITADA(Desis)',
                'position' => 'Full Stack Developer',
                'description' => 'Desarrollo de aplicaciones web complejas utilizando tecnologías modernas. Lideré un equipo de 5 desarrolladores y implementé mejores prácticas de desarrollo.',
                'start_date' => '2021-09-08',
                'end_date' => null,
                'location' => 'Santiago,chile',
                'tech' => [
                    'Angular',
                    'Laravel',
                    'PHP',
                    'TypeScript',
                    'MySQL',
                    'Docker'
                ],
                'achievements' => [
                    'Reduje el tiempo de carga de la aplicación en un 40%',
                    'Implementé CI/CD con GitHub Actions',
                    'Mentoré a 3 desarrolladores junior'
                ],
                'is_current' => true,
                'company_url' => 'https://www.desis.cl/',
                'status' => 'active'
            ],
            [
                'company' => 'Anidas Latam',
                'position' => 'Junior Developer',
                'description' => 'Primera experiencia profesional desarrollando aplicaciones web desde cero. Aprendí metodologías ágiles y trabajo en equipo.',
                'start_date' => '2020-06-01',
                'end_date' => '2021-02-01',
                'location' => 'Santiago,chile',
                'tech' => [
                    'PHP',
                    'MySQL',
                    'HTML5',
                    'CSS3',
                    'JavaScript',
                    'jQuery'
                ],
                'achievements' => [
                    'Desarrollé 2 aplicaciones web completas',
                    'Implementé sistema de autenticación',
                    'Participé en el diseño de la arquitectura de datos'
                ],
                'is_current' => false,
                'company_url' => 'https://www.anidalatam.com/',
                'status' => 'active'
            ]
        ];

        foreach ($works as $work) {
            Work::create($work);
        }
    }
}