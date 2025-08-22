<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class SetupStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configurar storage para Railway';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Configurando storage para Railway...');

        // Crear directorio public/storage si no existe
        $publicStoragePath = public_path('storage');
        if (!File::exists($publicStoragePath)) {
            File::makeDirectory($publicStoragePath, 0755, true);
            $this->line("✓ Creado directorio: {$publicStoragePath}");
        }

        // Crear directorio storage/app/public si no existe
        $storagePath = storage_path('app/public');
        if (!File::exists($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
            $this->line("✓ Creado directorio: {$storagePath}");
        }

        // Crear directorios de categorías
        $categories = ['projects', 'works', 'temp'];
        foreach ($categories as $category) {
            $categoryPath = $storagePath . '/assets/uploads/' . $category;
            if (!File::exists($categoryPath)) {
                File::makeDirectory($categoryPath, 0755, true);
                $this->line("✓ Creado directorio: {$categoryPath}");
            }
        }

        // Crear archivo .gitkeep en storage/app/public para mantener el directorio
        $gitkeepFile = $storagePath . '/.gitkeep';
        if (!File::exists($gitkeepFile)) {
            File::put($gitkeepFile, '');
            $this->line("✓ Creado archivo .gitkeep en storage/app/public");
        }

        // Crear archivo .gitkeep en public/storage para mantener el directorio
        $publicGitkeepFile = $publicStoragePath . '/.gitkeep';
        if (!File::exists($publicGitkeepFile)) {
            File::put($publicGitkeepFile, '');
            $this->line("✓ Creado archivo .gitkeep en public/storage");
        }

        // Verificar que el enlace simbólico existe
        $this->info('Verificando enlace simbólico...');
        $this->call('storage:link');

        $this->info('✅ Configuración de storage completada.');
    }
}
