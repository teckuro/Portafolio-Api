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

            if ($project->image_url && str_contains($project->image_url, '127.0.0.1:8000')) {
                $project->image_url = str_replace('http://127.0.0.1:8000', $baseUrl, $project->image_url);
                $updated = true;
                $this->line("✓ Corregida URL de imagen para proyecto: {$project->title}");
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

            if ($work->image_url && str_contains($work->image_url, '127.0.0.1:8000')) {
                $work->image_url = str_replace('http://127.0.0.1:8000', $baseUrl, $work->image_url);
                $updated = true;
                $this->line("✓ Corregida URL de imagen para trabajo: {$work->company}");
            }

            if ($updated) {
                $work->save();
                $fixedCount++;
            }
        }

        $this->info("✅ Corrección completada. {$fixedCount} registros actualizados.");
    }
}
