<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestProductionUrls extends Command
{
    protected $signature = 'test:production-urls';
    protected $description = 'Test production URLs for images';

    public function handle()
    {
        $this->info('=== Testing Production URLs ===');
        
        $baseUrl = 'https://web-production-eeecb.up.railway.app';
        $testFiles = [
            'projects/ecommerceplatform.svg',
            'projects/projects_1755807301_tOh1XirG.jpg',
            'works/anidaslatam.svg',
            'temp/temp_1755807078_VFGdTqL8.png'
        ];
        
        foreach ($testFiles as $file) {
            $this->info("Testing: {$file}");
            
            // Probar ruta API
            $apiUrl = "{$baseUrl}/api/files/{$file}";
            $this->info("  API URL: {$apiUrl}");
            
            try {
                $response = Http::timeout(10)->head($apiUrl);
                $status = $response->status();
                $this->info("  API Status: {$status}");
                
                if ($status === 200) {
                    $this->info("  ✅ API route works");
                } else {
                    $this->warn("  ❌ API route failed");
                }
            } catch (\Exception $e) {
                $this->error("  ❌ API route error: " . $e->getMessage());
            }
            
            // Probar ruta web
            $webUrl = "{$baseUrl}/storage/{$file}";
            $this->info("  Web URL: {$webUrl}");
            
            try {
                $response = Http::timeout(10)->head($webUrl);
                $status = $response->status();
                $this->info("  Web Status: {$status}");
                
                if ($status === 200) {
                    $this->info("  ✅ Web route works");
                } else {
                    $this->warn("  ❌ Web route failed");
                }
            } catch (\Exception $e) {
                $this->error("  ❌ Web route error: " . $e->getMessage());
            }
            
            $this->info('');
        }
        
        $this->info('=== Test Complete ===');
    }
}
