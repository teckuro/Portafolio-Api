<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class SetupStorage extends Command
{
    protected $signature = 'storage:setup';
    protected $description = 'Configurar y verificar el storage para archivos';

    public function handle()
    {
        $this->info('Configurando storage...');

        // 1. Verificar que el directorio base existe
        $basePath = 'assets/uploads';
        if (!Storage::disk('public')->exists($basePath)) {
            Storage::disk('public')->makeDirectory($basePath);
            $this->line("✓ Creado directorio base: {$basePath}");
        } else {
            $this->line("✓ Directorio base existe: {$basePath}");
        }

        // 2. Crear subdirectorios
        $categories = ['projects', 'works', 'temp'];
        foreach ($categories as $category) {
            $path = $basePath . '/' . $category;
            if (!Storage::disk('public')->exists($path)) {
                Storage::disk('public')->makeDirectory($path);
                $this->line("✓ Creado directorio: {$path}");
            } else {
                $this->line("✓ Directorio existe: {$path}");
            }
        }

        // 3. Verificar permisos
        $this->info('Verificando permisos...');
        $testPath = $basePath . '/test.txt';
        $testContent = 'Test file created at ' . now();
        
        try {
            Storage::disk('public')->put($testPath, $testContent);
            $this->line("✓ Permisos de escritura OK");
            
            // Leer el archivo de prueba
            $readContent = Storage::disk('public')->get($testPath);
            if ($readContent === $testContent) {
                $this->line("✓ Permisos de lectura OK");
            } else {
                $this->error("✗ Error en permisos de lectura");
            }
            
            // Eliminar archivo de prueba
            Storage::disk('public')->delete($testPath);
            $this->line("✓ Permisos de eliminación OK");
            
        } catch (\Exception $e) {
            $this->error("✗ Error en permisos: " . $e->getMessage());
        }

        // 4. Verificar archivos existentes
        $this->info('Verificando archivos existentes...');
        foreach ($categories as $category) {
            $path = $basePath . '/' . $category;
            $files = Storage::disk('public')->files($path);
            $this->line("  - {$category}: " . count($files) . " archivos");
            
            foreach ($files as $file) {
                $filename = basename($file);
                $size = Storage::disk('public')->size($file);
                $this->line("    * {$filename} ({$size} bytes)");
            }
        }

        // 5. Verificar URL de acceso
        $this->info('Verificando URLs de acceso...');
        $baseUrl = config('app.url');
        if (app()->environment('production')) {
            $baseUrl = 'https://web-production-eeecb.up.railway.app';
        }
        
        $this->line("  - URL base: {$baseUrl}");
        $this->line("  - URL de archivos: {$baseUrl}/api/files/");
        
        // 6. Verificar symlink
        $this->info('Verificando symlink...');
        $symlinkPath = public_path('storage');
        if (is_link($symlinkPath)) {
            $this->line("✓ Symlink existe: {$symlinkPath}");
        } else {
            $this->warn("⚠ Symlink no existe: {$symlinkPath}");
            $this->line("  Ejecuta: php artisan storage:link");
        }

        $this->info('Configuración de storage completada.');
    }
}
