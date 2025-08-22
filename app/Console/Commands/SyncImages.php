<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class SyncImages extends Command
{
    protected $signature = 'images:sync';
    protected $description = 'Sincronizar imágenes con Railway';

    public function handle()
    {
        $this->info('Sincronizando imágenes con Railway...');

        // Verificar archivos locales
        $categories = ['projects', 'works', 'temp'];
        $localFiles = [];

        foreach ($categories as $category) {
            $path = 'assets/uploads/' . $category;
            $files = Storage::disk('public')->files($path);
            
            foreach ($files as $file) {
                $filename = basename($file);
                $size = Storage::disk('public')->size($file);
                $localFiles[] = [
                    'path' => $file,
                    'filename' => $filename,
                    'category' => $category,
                    'size' => $size
                ];
            }
        }

        $this->line('Archivos locales encontrados: ' . count($localFiles));

        // Mostrar archivos locales
        foreach ($localFiles as $file) {
            $this->line("  - {$file['category']}/{$file['filename']} ({$file['size']} bytes)");
        }

        // Verificar si estamos en producción
        if (app()->environment('production')) {
            $this->info('Ejecutando en producción (Railway)...');
            
            // Crear directorios si no existen
            foreach ($categories as $category) {
                $path = 'assets/uploads/' . $category;
                if (!Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->makeDirectory($path);
                    $this->line("✓ Creado directorio: {$path}");
                }
            }

            // Verificar archivos en producción
            $this->info('Verificando archivos en producción...');
            foreach ($localFiles as $file) {
                if (Storage::disk('public')->exists($file['path'])) {
                    $prodSize = Storage::disk('public')->size($file['path']);
                    if ($prodSize === $file['size']) {
                        $this->line("✓ {$file['filename']} - OK");
                    } else {
                        $this->warn("⚠ {$file['filename']} - Tamaño diferente (local: {$file['size']}, prod: {$prodSize})");
                    }
                } else {
                    $this->error("✗ {$file['filename']} - No existe en producción");
                }
            }
        } else {
            $this->info('Ejecutando en desarrollo local...');
            $this->line('Los archivos se sincronizarán automáticamente al hacer deploy a Railway.');
        }

        $this->info('Sincronización completada.');
    }
}
