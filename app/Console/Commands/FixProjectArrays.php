<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class FixProjectArrays extends Command
{
    protected $signature = 'fix:project-arrays';
    protected $description = 'Arregla los arrays de tech_stack y features en la tabla projects';

    public function handle()
    {
        $this->info('Arreglando arrays en la tabla projects...');

        $projects = Project::all();
        $fixed = 0;

        foreach ($projects as $project) {
            $updated = false;

            // Arreglar tech_stack
            if (is_string($project->tech_stack)) {
                try {
                    $techStack = json_decode($project->tech_stack, true);
                    if (is_array($techStack)) {
                        $project->tech_stack = $techStack;
                        $updated = true;
                        $this->line("  - Arreglado tech_stack para: {$project->title}");
                    }
                } catch (\Exception $e) {
                    $this->error("  - Error decodificando tech_stack para: {$project->title}");
                }
            }

            // Arreglar features
            if (is_string($project->features)) {
                try {
                    $features = json_decode($project->features, true);
                    if (is_array($features)) {
                        $project->features = $features;
                        $updated = true;
                        $this->line("  - Arreglado features para: {$project->title}");
                    }
                } catch (\Exception $e) {
                    $this->error("  - Error decodificando features para: {$project->title}");
                }
            }

            if ($updated) {
                $project->save();
                $fixed++;
            }
        }

        $this->info("Proceso completado. {$fixed} proyectos actualizados.");
    }
}
