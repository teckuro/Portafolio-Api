<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestUpload extends Command
{
    protected $signature = 'test:upload';
    protected $description = 'Test upload endpoint';

    public function handle()
    {
        $this->info('=== Testing Upload Endpoint ===');
        
        $baseUrl = 'https://web-production-eeecb.up.railway.app';
        
        // Crear una imagen de prueba simple
        $testImageContent = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
        $tempPath = tempnam(sys_get_temp_dir(), 'test_upload_');
        file_put_contents($tempPath, $testImageContent);
        
        $this->info('Uploading test image...');
        
        try {
            $response = Http::attach(
                'image',
                $tempPath,
                'test.png',
                ['Content-Type' => 'image/png']
            )->post("{$baseUrl}/api/admin/upload", [
                'category' => 'temp'
            ]);
            
            $this->info("Status: " . $response->status());
            $this->info("Response: " . $response->body());
            
            if ($response->successful()) {
                $this->info('✅ Upload successful');
            } else {
                $this->warn('❌ Upload failed');
            }
            
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        } finally {
            unlink($tempPath);
        }
        
        return 0;
    }
}
