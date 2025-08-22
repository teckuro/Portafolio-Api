<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExecuteRailwayCommand extends Command
{
    protected $signature = 'execute:railway-command {command}';
    protected $description = 'Execute a specific command and return the result';

    public function handle()
    {
        $command = $this->argument('command');
        
        $this->info("=== Executing Command: {$command} ===");
        
        try {
            $exitCode = \Artisan::call($command);
            $output = \Artisan::output();
            
            $this->info("Exit Code: {$exitCode}");
            $this->info("Output:");
            $this->line($output);
            
            return $exitCode;
        } catch (\Exception $e) {
            $this->error("Error executing command: " . $e->getMessage());
            return 1;
        }
    }
}
