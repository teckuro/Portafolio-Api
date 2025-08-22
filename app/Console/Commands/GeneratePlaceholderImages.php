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

        // Generar imágenes SVG para proyectos
        $projects = Project::all();
        foreach ($projects as $project) {
            $filename = $this->generateFilename($project->title);
            $imagePath = "{$projectsDir}/{$filename}";
            
            if (!Storage::disk('public')->exists($imagePath)) {
                $this->generateSvgPlaceholder($imagePath, $project->title, '#2563eb');
                $this->line("✓ Generada imagen SVG para proyecto: {$project->title}");
                $generatedCount++;
            }
        }

        // Generar imágenes SVG para trabajos
        $works = Work::all();
        foreach ($works as $work) {
            $filename = $this->generateFilename($work->company);
            $imagePath = "{$worksDir}/{$filename}";
            
            if (!Storage::disk('public')->exists($imagePath)) {
                $this->generateSvgPlaceholder($imagePath, $work->company, '#059669');
                $this->line("✓ Generada imagen SVG para trabajo: {$work->company}");
                $generatedCount++;
            }
        }

        $this->info("✅ Generación completada. {$generatedCount} imágenes SVG creadas.");
    }

    /**
     * Generar nombre de archivo basado en el título
     */
    private function generateFilename($title): string
    {
        return strtolower(str_replace([' ', '-', '_'], '', $title)) . '.svg';
    }

    /**
     * Generar imagen SVG placeholder
     */
    private function generateSvgPlaceholder($path, $text, $backgroundColor): void
    {
        $width = 600;
        $height = 400;
        
        // Crear SVG placeholder
        $svg = $this->createSvgPlaceholder($width, $height, $text, $backgroundColor);
        
        Storage::disk('public')->put($path, $svg);
    }

    /**
     * Crear SVG placeholder
     */
    private function createSvgPlaceholder($width, $height, $text, $backgroundColor): string
    {
        $textColor = '#ffffff';
        $fontSize = 24;
        $fontFamily = 'Arial, sans-serif';
        
        // Calcular posición del texto (centrado)
        $textX = $width / 2;
        $textY = $height / 2 + $fontSize / 3;
        
        return <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg width="{$width}" height="{$height}" viewBox="0 0 {$width} {$height}" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:{$backgroundColor};stop-opacity:1" />
            <stop offset="100%" style="stop-color:{$backgroundColor};stop-opacity:0.8" />
        </linearGradient>
    </defs>
    
    <!-- Fondo -->
    <rect width="{$width}" height="{$height}" fill="url(#gradient)"/>
    
    <!-- Texto -->
    <text x="{$textX}" y="{$textY}" 
          font-family="{$fontFamily}" 
          font-size="{$fontSize}" 
          font-weight="bold" 
          fill="{$textColor}" 
          text-anchor="middle" 
          dominant-baseline="middle">
        {$text}
    </text>
    
    <!-- Icono de imagen -->
    <g transform="translate(" . ($textX - 30) . ", " . ($textY + 50) . ")" fill="{$textColor}" opacity="0.3">
        <rect x="0" y="0" width="60" height="40" rx="4" fill="none" stroke="currentColor" stroke-width="2"/>
        <circle cx="15" cy="12" r="3" fill="currentColor"/>
        <polygon points="0,32 20,20 40,25 60,15 60,40 0,40" fill="currentColor"/>
    </g>
</svg>
SVG;
    }
}
