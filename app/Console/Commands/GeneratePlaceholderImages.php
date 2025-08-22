<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GeneratePlaceholderImages extends Command
{
    protected $signature = 'images:generate-placeholders';
    protected $description = 'Generate placeholder images for Railway';

    public function handle()
    {
        $this->info('=== Generating Placeholder Images ===');
        
        $categories = ['projects', 'works', 'temp'];
        $generatedCount = 0;
        
        foreach ($categories as $category) {
            $this->info("Generating images for {$category}...");
            
            $path = 'assets/uploads/' . $category;
            
            // Crear directorio si no existe
            if (!Storage::disk('public')->exists($path)) {
                Storage::disk('public')->makeDirectory($path);
            }
            
            // Generar algunas imágenes placeholder
            $placeholders = [
                'placeholder1.svg' => $this->generateSVG('Placeholder 1', '#4F46E5'),
                'placeholder2.svg' => $this->generateSVG('Placeholder 2', '#059669'),
                'placeholder3.svg' => $this->generateSVG('Placeholder 3', '#DC2626'),
            ];
            
            foreach ($placeholders as $filename => $content) {
                $fullPath = $path . '/' . $filename;
                
                if (!Storage::disk('public')->exists($fullPath)) {
                    Storage::disk('public')->put($fullPath, $content);
                    $this->info("  ✅ Generated: {$filename}");
                    $generatedCount++;
                } else {
                    $this->info("  ⏭️  Already exists: {$filename}");
                }
            }
        }
        
        $this->info('');
        $this->info("Generated {$generatedCount} placeholder images");
        $this->info('=== Generation Complete ===');
        
        return 0;
    }
    
    private function generateSVG($text, $color)
    {
        return <<<SVG
<svg width="200" height="150" xmlns="http://www.w3.org/2000/svg">
    <rect width="200" height="150" fill="{$color}" opacity="0.1"/>
    <rect width="200" height="150" fill="none" stroke="{$color}" stroke-width="2"/>
    <text x="100" y="75" font-family="Arial, sans-serif" font-size="14" fill="{$color}" text-anchor="middle" dominant-baseline="middle">{$text}</text>
</svg>
SVG;
    }
}
