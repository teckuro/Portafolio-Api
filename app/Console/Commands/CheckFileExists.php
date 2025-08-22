<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CheckFileExists extends Command
{
    protected $signature = 'file:check {filename}';
    protected $description = 'Verificar si un archivo específico existe en storage';

    public function handle()
    {
        $filename = $this->argument('filename');
        
        $this->info("Verificando archivo: {$filename}");
        
        // Buscar en todas las categorías
        $categories = ['projects', 'works', 'temp'];
        $found = false;
        
        foreach ($categories as $category) {
            $path = "assets/uploads/{$category}/{$filename}";
            
            if (Storage::disk('public')->exists($path)) {
                $size = Storage::disk('public')->size($path);
                $this->line("✓ Encontrado en: {$path} ({$size} bytes)");
                $found = true;
            }
        }
        
        if (!$found) {
            $this->error("✗ Archivo no encontrado en ninguna categoría");
            
            // Mostrar archivos similares
            $this->info("Archivos similares encontrados:");
            foreach ($categories as $category) {
                $files = Storage::disk('public')->files("assets/uploads/{$category}");
                foreach ($files as $file) {
                    $basename = basename($file);
                    if (strpos($basename, 'projects_') !== false) {
                        $this->line("  - {$category}/{$basename}");
                    }
                }
            }
        }
    }
}
