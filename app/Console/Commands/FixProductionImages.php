<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class FixProductionImages extends Command
{
    protected $signature = 'fix:production-images';
    protected $description = 'Fix image routes and storage for production';

    public function handle()
    {
        $this->info('=== Fixing Production Images ===');
        
        try {
            // 1. Verificar que el directorio storage existe
            $this->info('1. Checking storage directory...');
            $storagePath = storage_path('app/public');
            if (!File::exists($storagePath)) {
                File::makeDirectory($storagePath, 0755, true);
                $this->info('   Created storage directory');
            } else {
                $this->info('   Storage directory exists');
            }
            
            // 2. Verificar que el directorio assets/uploads existe
            $this->info('2. Checking assets/uploads directory...');
            $uploadsPath = $storagePath . '/assets/uploads';
            if (!File::exists($uploadsPath)) {
                File::makeDirectory($uploadsPath, 0755, true);
                $this->info('   Created assets/uploads directory');
            } else {
                $this->info('   Assets/uploads directory exists');
            }
            
            // 3. Crear subdirectorios si no existen
            $this->info('3. Creating subdirectories...');
            $categories = ['projects', 'works', 'temp'];
            foreach ($categories as $category) {
                $categoryPath = $uploadsPath . '/' . $category;
                if (!File::exists($categoryPath)) {
                    File::makeDirectory($categoryPath, 0755, true);
                    $this->info("   Created {$category} directory");
                } else {
                    $this->info("   {$category} directory exists");
                }
            }
            
            // 4. Verificar archivos existentes
            $this->info('4. Checking existing files...');
            foreach ($categories as $category) {
                $path = 'assets/uploads/' . $category;
                if (Storage::disk('public')->exists($path)) {
                    $files = Storage::disk('public')->files($path);
                    $this->info("   {$category}: " . count($files) . " files");
                } else {
                    $this->warn("   {$category}: No files found");
                }
            }
            
            // 5. Generar URLs de prueba
            $this->info('5. Generating test URLs...');
            $baseUrl = config('app.url');
            $this->info("   Base URL: {$baseUrl}");
            
            // Buscar un archivo de ejemplo
            $sampleFile = null;
            foreach ($categories as $category) {
                $path = 'assets/uploads/' . $category;
                if (Storage::disk('public')->exists($path)) {
                    $files = Storage::disk('public')->files($path);
                    if (count($files) > 0) {
                        $sampleFile = basename($files[0]);
                        $category = $category;
                        break;
                    }
                }
            }
            
            if ($sampleFile) {
                $webUrl = rtrim($baseUrl, '/') . "/storage/{$category}/{$sampleFile}";
                $apiUrl = rtrim($baseUrl, '/') . "/api/files/{$category}/{$sampleFile}";
                
                $this->info("   Sample file: {$sampleFile}");
                $this->info("   Web URL: {$webUrl}");
                $this->info("   API URL: {$apiUrl}");
            }
            
            // 6. Verificar permisos
            $this->info('6. Checking permissions...');
            $this->info("   Storage path: {$storagePath}");
            $this->info("   Storage writable: " . (is_writable($storagePath) ? 'Yes' : 'No'));
            
            $this->info('=== Fix Complete ===');
            $this->info('Try accessing the API URL to test if images are working.');
            
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
