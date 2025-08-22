<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Work;
use Illuminate\Support\Facades\DB;

class FixWorkArrays extends Command
{
    protected $signature = 'fix:work-arrays';
    protected $description = 'Arregla los arrays de tech y achievements en la tabla works';

    public function handle()
    {
        $this->info('Arreglando arrays en la tabla works...');

        $works = Work::all();
        $fixed = 0;

        foreach ($works as $work) {
            $updated = false;

            // Arreglar tech
            if (is_string($work->tech)) {
                try {
                    $tech = json_decode($work->tech, true);
                    if (is_array($tech)) {
                        $work->tech = $tech;
                        $updated = true;
                        $this->line("  - Arreglado tech para: {$work->position} en {$work->company}");
                    }
                } catch (\Exception $e) {
                    $this->error("  - Error decodificando tech para: {$work->position} en {$work->company}");
                }
            }

            // Arreglar achievements
            if (is_string($work->achievements)) {
                try {
                    $achievements = json_decode($work->achievements, true);
                    if (is_array($achievements)) {
                        $work->achievements = $achievements;
                        $updated = true;
                        $this->line("  - Arreglado achievements para: {$work->position} en {$work->company}");
                    }
                } catch (\Exception $e) {
                    $this->error("  - Error decodificando achievements para: {$work->position} en {$work->company}");
                }
            }

            if ($updated) {
                $work->save();
                $fixed++;
            }
        }

        $this->info("Proceso completado. {$fixed} trabajos actualizados.");
    }
}
