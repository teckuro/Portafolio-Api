<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class SetupRailwayStorage extends Command
{
    protected $signature = 'railway:setup-storage';
    protected $description = 'Configurar storage específicamente para Railway';

    public function handle()
    {
        $this->info('Configurando storage para Railway...');

        // 1. Crear directorios base
        $this->createDirectories();

        // 2. Verificar permisos
        $this->checkPermissions();

        // 3. Crear archivos de prueba
        $this->createTestFiles();

        // 4. Verificar configuración
        $this->verifyConfiguration();

        $this->info('Configuración de Railway completada.');
    }

    private function createDirectories()
    {
        $this->info('1. Creando directorios...');

        // Directorio base de storage
        $storagePath = storage_path('app/public');
        if (!File::exists($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
            $this->line("✓ Creado: {$storagePath}");
        } else {
            $this->line("✓ Existe: {$storagePath}");
        }

        // Directorio de uploads
        $uploadsPath = $storagePath . '/assets/uploads';
        if (!File::exists($uploadsPath)) {
            File::makeDirectory($uploadsPath, 0755, true);
            $this->line("✓ Creado: {$uploadsPath}");
        } else {
            $this->line("✓ Existe: {$uploadsPath}");
        }

        // Subdirectorios por categoría
        $categories = ['projects', 'works', 'temp'];
        foreach ($categories as $category) {
            $categoryPath = $uploadsPath . '/' . $category;
            if (!File::exists($categoryPath)) {
                File::makeDirectory($categoryPath, 0755, true);
                $this->line("✓ Creado: {$categoryPath}");
            } else {
                $this->line("✓ Existe: {$categoryPath}");
            }
        }
    }

    private function checkPermissions()
    {
        $this->info('2. Verificando permisos...');

        $storagePath = storage_path('app/public');
        $testFile = $storagePath . '/test-permissions.txt';
        $testContent = 'Test file created at ' . now();

        try {
            // Probar escritura
            File::put($testFile, $testContent);
            $this->line("✓ Permisos de escritura OK");

            // Probar lectura
            $readContent = File::get($testFile);
            if ($readContent === $testContent) {
                $this->line("✓ Permisos de lectura OK");
            } else {
                $this->error("✗ Error en permisos de lectura");
            }

            // Limpiar archivo de prueba
            File::delete($testFile);
            $this->line("✓ Permisos de eliminación OK");

        } catch (\Exception $e) {
            $this->error("✗ Error en permisos: " . $e->getMessage());
        }
    }

    private function createTestFiles()
    {
        $this->info('3. Creando archivos de prueba...');

        $categories = ['projects', 'works', 'temp'];
        foreach ($categories as $category) {
            $testFile = "assets/uploads/{$category}/test-{$category}.txt";
            $content = "Test file for {$category} created at " . now();

            try {
                Storage::disk('public')->put($testFile, $content);
                $this->line("✓ Creado archivo de prueba: {$testFile}");
            } catch (\Exception $e) {
                $this->error("✗ Error creando archivo de prueba para {$category}: " . $e->getMessage());
            }
        }
    }

    private function verifyConfiguration()
    {
        $this->info('4. Verificando configuración...');

        // Verificar configuración de storage
        $defaultDisk = config('filesystems.default');
        $publicDisk = config('filesystems.disks.public');
        
        $this->line("  - Disco por defecto: {$defaultDisk}");
        $this->line("  - Disco público configurado: " . ($publicDisk ? 'Sí' : 'No'));

        if ($publicDisk) {
            $this->line("  - Driver: " . $publicDisk['driver']);
            $this->line("  - Root: " . $publicDisk['root']);
            $this->line("  - URL: " . $publicDisk['url']);
        }

        // Verificar archivos existentes
        $this->info('5. Archivos existentes:');
        $categories = ['projects', 'works', 'temp'];
        foreach ($categories as $category) {
            $files = Storage::disk('public')->files("assets/uploads/{$category}");
            $this->line("  - {$category}: " . count($files) . " archivos");
            
            foreach ($files as $file) {
                $filename = basename($file);
                $size = Storage::disk('public')->size($file);
                $this->line("    * {$filename} ({$size} bytes)");
            }
        }
    }
}
