<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class TestImageAccess extends Command
{
    protected $signature = 'images:test-access';
    protected $description = 'Probar acceso a las imágenes subidas';

    public function handle()
    {
        $this->info('Probando acceso a imágenes...');

        $baseUrl = config('app.url');
        if (app()->environment('production')) {
            $baseUrl = 'https://web-production-eeecb.up.railway.app';
        }

        // Buscar archivos de imagen
        $categories = ['projects', 'works', 'temp'];
        $testFiles = [];

        foreach ($categories as $category) {
            $path = 'assets/uploads/' . $category;
            $files = Storage::disk('public')->files($path);
            
            foreach ($files as $file) {
                $filename = basename($file);
                $testFiles[] = [
                    'path' => $file,
                    'filename' => $filename,
                    'category' => $category,
                    'url' => $baseUrl . '/api/files/' . $file
                ];
            }
        }

        if (empty($testFiles)) {
            $this->warn('No se encontraron archivos para probar');
            return;
        }

        $this->line('Archivos encontrados: ' . count($testFiles));

        // Probar acceso a cada archivo
        foreach ($testFiles as $file) {
            $this->line("\nProbando: {$file['filename']}");
            $this->line("  - Categoría: {$file['category']}");
            $this->line("  - URL: {$file['url']}");
            
            // Verificar que el archivo existe en storage
            if (Storage::disk('public')->exists($file['path'])) {
                $size = Storage::disk('public')->size($file['path']);
                $this->line("  - Existe en storage: Sí ({$size} bytes)");
                
                // Probar acceso HTTP
                $this->testHttpAccess($file['url'], $file['filename']);
            } else {
                $this->error("  - Existe en storage: No");
            }
        }

        $this->info('Prueba de acceso completada.');
    }

    private function testHttpAccess($url, $filename)
    {
        try {
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'timeout' => 10,
                    'user_agent' => 'Test-Script/1.0'
                ]
            ]);

            $response = file_get_contents($url, false, $context);
            
            if ($response !== false) {
                $this->line("  - Acceso HTTP: ✓ OK (" . strlen($response) . " bytes)");
            } else {
                $this->error("  - Acceso HTTP: ✗ Error");
            }
        } catch (\Exception $e) {
            $this->error("  - Acceso HTTP: ✗ Error - " . $e->getMessage());
        }
    }
}
