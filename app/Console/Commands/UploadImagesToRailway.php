<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class UploadImagesToRailway extends Command
{
    protected $signature = 'upload:images-to-railway';
    protected $description = 'Upload local images to Railway using the API';

    public function handle()
    {
        $this->info('=== Uploading Images to Railway ===');
        
        $baseUrl = 'https://web-production-eeecb.up.railway.app';
        $categories = ['projects', 'works', 'temp'];
        $uploadedCount = 0;
        $failedCount = 0;
        
        foreach ($categories as $category) {
            $this->info("Processing {$category}...");
            
            $path = 'assets/uploads/' . $category;
            if (Storage::disk('public')->exists($path)) {
                $files = Storage::disk('public')->files($path);
                
                foreach ($files as $file) {
                    $filename = basename($file);
                    $this->info("  Uploading: {$filename}");
                    
                    try {
                        // Leer el archivo
                        $fileContent = Storage::disk('public')->get($file);
                        $mimeType = Storage::disk('public')->mimeType($file);
                        
                        // Crear archivo temporal
                        $tempPath = tempnam(sys_get_temp_dir(), 'railway_upload_');
                        file_put_contents($tempPath, $fileContent);
                        
                        // Subir usando la API
                        $response = Http::attach(
                            'image',
                            $tempPath,
                            $filename,
                            ['Content-Type' => $mimeType]
                        )->post("{$baseUrl}/api/admin/upload", [
                            'category' => $category
                        ]);
                        
                        // Limpiar archivo temporal
                        unlink($tempPath);
                        
                        if ($response->successful()) {
                            $this->info("    ✅ Uploaded successfully");
                            $uploadedCount++;
                        } else {
                            $this->warn("    ❌ Upload failed: " . $response->status());
                            $failedCount++;
                        }
                        
                    } catch (\Exception $e) {
                        $this->error("    ❌ Error: " . $e->getMessage());
                        $failedCount++;
                    }
                }
            } else {
                $this->warn("  No files found in {$category}");
            }
        }
        
        $this->info('');
        $this->info('=== Upload Summary ===');
        $this->info("Uploaded: {$uploadedCount} files");
        $this->info("Failed: {$failedCount} files");
        
        if ($uploadedCount > 0) {
            $this->info('');
            $this->info('Testing uploaded images...');
            $this->call('test:production-urls');
        }
        
        return 0;
    }
}
