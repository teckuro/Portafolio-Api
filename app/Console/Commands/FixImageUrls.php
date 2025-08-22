<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use App\Models\Work;

class FixImageUrls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:fix-urls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corregir URLs de imágenes para usar la URL de producción';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando corrección de URLs de imágenes...');

        $baseUrl = 'https://web-production-eeecb.up.railway.app';
        $fixedCount = 0;

        // Corregir URLs en proyectos
        $projects = Project::all();
        foreach ($projects as $project) {
            $updated = false;

            // Corregir URLs de placeholder
            if ($project->image_url && str_contains($project->image_url, 'via.placeholder.com')) {
                // Reemplazar con URLs locales basadas en el título del proyecto
                $project->image_url = $this->generateLocalImageUrl($project->title);
                $updated = true;
                $this->line("✓ Corregida URL de placeholder para proyecto: {$project->title}");
            }

            // Corregir URLs de storage
            if ($project->image_url && str_contains($project->image_url, '/storage/')) {
                // Convertir de /storage/ a /api/files/
                $project->image_url = str_replace('/storage/', '/api/files/', $project->image_url);
                $updated = true;
                $this->line("✓ Corregida URL de storage para proyecto: {$project->title}");
            }

            if ($updated) {
                $project->save();
                $fixedCount++;
            }
        }

        // Corregir URLs en experiencia laboral (si tienen imágenes)
        $works = Work::all();
        foreach ($works as $work) {
            $updated = false;

            // Corregir URLs de placeholder
            if ($work->image_url && str_contains($work->image_url, 'via.placeholder.com')) {
                // Reemplazar con URLs locales basadas en la empresa
                $work->image_url = $this->generateLocalImageUrl($work->company);
                $updated = true;
                $this->line("✓ Corregida URL de placeholder para trabajo: {$work->company}");
            }

            // Corregir URLs de storage
            if ($work->image_url && str_contains($work->image_url, '/storage/')) {
                // Convertir de /storage/ a /api/files/
                $work->image_url = str_replace('/storage/', '/api/files/', $work->image_url);
                $updated = true;
                $this->line("✓ Corregida URL de storage para trabajo: {$work->company}");
            }

            if ($updated) {
                $work->save();
                $fixedCount++;
            }
        }

        $this->info("✅ Corrección completada. {$fixedCount} registros actualizados.");
    }

    /**
     * Generar URL de imagen local basada en el título
     */
    private function generateLocalImageUrl($title): string
    {
        $baseUrl = 'https://web-production-eeecb.up.railway.app/api/files';
        
        // Crear un nombre de archivo basado en el título
        $filename = strtolower(str_replace([' ', '-', '_'], '', $title)) . '.jpg';
        
        return "{$baseUrl}/projects/{$filename}";
    }
}
