<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class TestFileRoute extends Command
{
    protected $signature = 'test:file-route {path}';
    protected $description = 'Test the file route logic';

    public function handle()
    {
        $path = $this->argument('path');
        $this->info("=== Testing File Route Logic ===");
        $this->info("Path: {$path}");
        
        // Construir la ruta completa
        $fullPath = 'assets/uploads/' . $path;
        $this->info("Full path: {$fullPath}");
        
        // Verificar si existe
        $exists = Storage::disk('public')->exists($fullPath);
        $this->info("File exists: " . ($exists ? 'Yes' : 'No'));
        
        if ($exists) {
            $size = Storage::disk('public')->size($fullPath);
            $mimeType = Storage::disk('public')->mimeType($fullPath);
            $this->info("File size: {$size} bytes");
            $this->info("MIME type: {$mimeType}");
            
            // Intentar leer el archivo
            try {
                $file = Storage::disk('public')->get($fullPath);
                $this->info("File read: " . ($file ? 'Success' : 'Failed'));
                $this->info("File content length: " . strlen($file));
            } catch (\Exception $e) {
                $this->error("Error reading file: " . $e->getMessage());
            }
        }
        
        return 0;
    }
}
