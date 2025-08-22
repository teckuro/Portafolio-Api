<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;

class TestImageRoutes extends Command
{
    protected $signature = 'test:image-routes';
    protected $description = 'Test image routes and storage configuration';

    public function handle()
    {
        $this->info('=== Testing Image Routes ===');
        
        // Verificar configuración de storage
        $this->info('1. Storage Configuration:');
        $this->info('   Default disk: ' . config('filesystems.default'));
        $this->info('   Public disk root: ' . config('filesystems.disks.public.root'));
        $this->info('   Public disk URL: ' . config('filesystems.disks.public.url'));
        
        // Verificar si existen archivos
        $this->info('2. Checking files:');
        $categories = ['projects', 'works', 'temp'];
        
        foreach ($categories as $category) {
            $path = 'assets/uploads/' . $category;
            if (Storage::disk('public')->exists($path)) {
                $files = Storage::disk('public')->files($path);
                $this->info("   {$category}: " . count($files) . " files");
                
                if (count($files) > 0) {
                    $sampleFile = basename($files[0]);
                    $this->info("   Sample file: {$sampleFile}");
                    
                    // Generar URLs de prueba
                    $webUrl = url("/storage/{$category}/{$sampleFile}");
                    $apiUrl = url("/api/files/{$category}/{$sampleFile}");
                    
                    $this->info("   Web URL: {$webUrl}");
                    $this->info("   API URL: {$apiUrl}");
                }
            } else {
                $this->warn("   {$category}: Directory not found");
            }
        }
        
        // Verificar rutas registradas
        $this->info('3. Registered routes:');
        $routes = Route::getRoutes();
        foreach ($routes as $route) {
            if (str_contains($route->uri(), 'storage') || str_contains($route->uri(), 'files')) {
                $this->info("   {$route->methods()[0]} {$route->uri()}");
            }
        }
        
        $this->info('=== Test Complete ===');
    }
}
