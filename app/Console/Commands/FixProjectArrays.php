<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;

class FixProjectArrays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'projects:fix-arrays';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corregir tech_stack y features que estén almacenados como strings JSON';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando corrección de arrays en proyectos...');

        $projects = Project::all();
        $fixedCount = 0;

        foreach ($projects as $project) {
            $updated = false;

            // Corregir tech_stack
            if (is_string($project->tech_stack)) {
                try {
                    $decoded = json_decode($project->tech_stack, true);
                    if (is_array($decoded)) {
                        $project->tech_stack = $decoded;
                        $updated = true;
                        $this->line("✓ Corregido tech_stack para proyecto: {$project->title}");
                    }
                } catch (\Exception $e) {
                    $this->error("✗ Error decodificando tech_stack para proyecto: {$project->title}");
                }
            }

            // Corregir features
            if (is_string($project->features)) {
                try {
                    $decoded = json_decode($project->features, true);
                    if (is_array($decoded)) {
                        $project->features = $decoded;
                        $updated = true;
                        $this->line("✓ Corregido features para proyecto: {$project->title}");
                    }
                } catch (\Exception $e) {
                    $this->error("✗ Error decodificando features para proyecto: {$project->title}");
                }
            }

            if ($updated) {
                $project->save();
                $fixedCount++;
            }
        }

        $this->info("✅ Corrección completada. {$fixedCount} proyectos actualizados.");
    }
}
