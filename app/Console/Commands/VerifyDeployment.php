<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class VerifyDeployment extends Command
{
    protected $signature = 'verify:deployment';
    protected $description = 'Verify that the deployment is working correctly';

    public function handle()
    {
        $this->info('=== Verifying Deployment ===');
        
        $baseUrl = config('app.url');
        $this->info("Base URL: {$baseUrl}");
        
        // 1. Verificar configuración básica
        $this->info('1. Basic Configuration:');
        $this->info("   Environment: " . config('app.env'));
        $this->info("   Debug: " . (config('app.debug') ? 'Yes' : 'No'));
        $this->info("   Storage disk: " . config('filesystems.default'));
        
        // 2. Verificar archivos de storage
        $this->info('2. Storage Files:');
        $categories = ['projects', 'works', 'temp'];
        foreach ($categories as $category) {
            $path = 'assets/uploads/' . $category;
            if (Storage::disk('public')->exists($path)) {
                $files = Storage::disk('public')->files($path);
                $this->info("   {$category}: " . count($files) . " files");
            } else {
                $this->warn("   {$category}: No files found");
            }
        }
        
        // 3. Probar URLs locales
        $this->info('3. Testing Local URLs:');
        $testFiles = [
            'projects/ecommerceplatform.svg',
            'works/anidaslatam.svg'
        ];
        
        foreach ($testFiles as $file) {
            $apiUrl = url("/api/files/{$file}");
            $webUrl = url("/storage/{$file}");
            
            $this->info("   Testing: {$file}");
            $this->info("     API: {$apiUrl}");
            $this->info("     Web: {$webUrl}");
            
            // Verificar si el archivo existe en storage
            $storagePath = 'assets/uploads/' . $file;
            if (Storage::disk('public')->exists($storagePath)) {
                $this->info("     ✅ File exists in storage");
            } else {
                $this->warn("     ❌ File not found in storage");
            }
        }
        
        // 4. Verificar rutas registradas
        $this->info('4. Registered Routes:');
        $routes = \Route::getRoutes();
        $fileRoutes = [];
        foreach ($routes as $route) {
            if (str_contains($route->uri(), 'files') || str_contains($route->uri(), 'storage')) {
                $fileRoutes[] = $route->methods()[0] . ' ' . $route->uri();
            }
        }
        
        if (empty($fileRoutes)) {
            $this->warn("   No file routes found");
        } else {
            foreach ($fileRoutes as $route) {
                $this->info("   {$route}");
            }
        }
        
        // 5. Verificar permisos de directorios
        $this->info('5. Directory Permissions:');
        $storagePath = storage_path('app/public');
        $this->info("   Storage path: {$storagePath}");
        $this->info("   Writable: " . (is_writable($storagePath) ? 'Yes' : 'No'));
        $this->info("   Readable: " . (is_readable($storagePath) ? 'Yes' : 'No'));
        
        $this->info('=== Verification Complete ===');
        
        return 0;
    }
}
