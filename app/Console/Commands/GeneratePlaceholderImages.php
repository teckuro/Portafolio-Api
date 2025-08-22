<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use App\Models\Work;
use Illuminate\Support\Facades\Storage;

class GeneratePlaceholderImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:generate-placeholders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generar imágenes placeholder locales para proyectos y trabajos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generando imágenes placeholder locales...');

        // Crear directorio si no existe
        $projectsDir = 'assets/uploads/projects';
        $worksDir = 'assets/uploads/works';
        
        if (!Storage::disk('public')->exists($projectsDir)) {
            Storage::disk('public')->makeDirectory($projectsDir);
        }
        
        if (!Storage::disk('public')->exists($worksDir)) {
            Storage::disk('public')->makeDirectory($worksDir);
        }

        $generatedCount = 0;

        // Generar imágenes para proyectos
        $projects = Project::all();
        foreach ($projects as $project) {
            $filename = $this->generateFilename($project->title);
            $imagePath = "{$projectsDir}/{$filename}";
            
            if (!Storage::disk('public')->exists($imagePath)) {
                $this->generatePlaceholderImage($imagePath, $project->title, '#2563eb');
                $this->line("✓ Generada imagen para proyecto: {$project->title}");
                $generatedCount++;
            }
        }

        // Generar imágenes para trabajos
        $works = Work::all();
        foreach ($works as $work) {
            $filename = $this->generateFilename($work->company);
            $imagePath = "{$worksDir}/{$filename}";
            
            if (!Storage::disk('public')->exists($imagePath)) {
                $this->generatePlaceholderImage($imagePath, $work->company, '#059669');
                $this->line("✓ Generada imagen para trabajo: {$work->company}");
                $generatedCount++;
            }
        }

        $this->info("✅ Generación completada. {$generatedCount} imágenes creadas.");
    }

    /**
     * Generar nombre de archivo basado en el título
     */
    private function generateFilename($title): string
    {
        return strtolower(str_replace([' ', '-', '_'], '', $title)) . '.jpg';
    }

    /**
     * Generar imagen placeholder usando GD
     */
    private function generatePlaceholderImage($path, $text, $backgroundColor): void
    {
        $width = 600;
        $height = 400;
        
        // Crear imagen
        $image = imagecreate($width, $height);
        
        // Definir colores
        $bgColor = $this->hexToRgb($backgroundColor);
        $textColor = imagecolorallocate($image, 255, 255, 255); // Blanco
        $bgAllocated = imagecolorallocate($image, $bgColor['r'], $bgColor['g'], $bgColor['b']);
        
        // Rellenar fondo
        imagefill($image, 0, 0, $bgAllocated);
        
        // Configurar fuente
        $fontSize = 24;
        $fontPath = storage_path('app/fonts/arial.ttf');
        
        // Si no existe la fuente, usar la predeterminada
        if (!file_exists($fontPath)) {
            $fontPath = 5; // Fuente predeterminada de GD
        }
        
        // Calcular posición del texto
        $bbox = imagettfbbox($fontSize, 0, $fontPath, $text);
        $textWidth = $bbox[2] - $bbox[0];
        $textHeight = $bbox[1] - $bbox[7];
        
        $x = ($width - $textWidth) / 2;
        $y = ($height + $textHeight) / 2;
        
        // Dibujar texto
        if (is_numeric($fontPath)) {
            imagestring($image, $fontPath, $x, $y, $text, $textColor);
        } else {
            imagettftext($image, $fontSize, 0, $x, $y, $textColor, $fontPath, $text);
        }
        
        // Guardar imagen
        ob_start();
        imagejpeg($image, null, 90);
        $imageData = ob_get_clean();
        
        Storage::disk('public')->put($path, $imageData);
        
        // Liberar memoria
        imagedestroy($image);
    }

    /**
     * Convertir color hexadecimal a RGB
     */
    private function hexToRgb($hex): array
    {
        $hex = str_replace('#', '', $hex);
        
        if (strlen($hex) == 3) {
            $r = hexdec(str_repeat(substr($hex, 0, 1), 2));
            $g = hexdec(str_repeat(substr($hex, 1, 1), 2));
            $b = hexdec(str_repeat(substr($hex, 2, 1), 2));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        
        return ['r' => $r, 'g' => $g, 'b' => $b];
    }
}
