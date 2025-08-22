<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CheckRailwayFiles extends Command
{
    protected $signature = 'check:railway-files';
    protected $description = 'Check what files are actually in Railway storage';

    public function handle()
    {
        $this->info('=== Checking Railway Storage Files ===');
        
        $categories = ['projects', 'works', 'temp'];
        
        foreach ($categories as $category) {
            $this->info("Checking {$category}...");
            $path = 'assets/uploads/' . $category;
            
            if (Storage::disk('public')->exists($path)) {
                $files = Storage::disk('public')->files($path);
                $this->info("  Found " . count($files) . " files:");
                
                foreach ($files as $file) {
                    $filename = basename($file);
                    $size = Storage::disk('public')->size($file);
                    $mimeType = Storage::disk('public')->mimeType($file);
                    $this->info("    - {$filename} ({$size} bytes, {$mimeType})");
                }
            } else {
                $this->warn("  Directory {$path} does not exist");
            }
        }
        
        $this->info('');
        $this->info('=== Storage Configuration ===');
        $this->info('Default disk: ' . config('filesystems.default'));
        $this->info('Public disk root: ' . config('filesystems.disks.public.root'));
        $this->info('Public disk URL: ' . config('filesystems.disks.public.url'));
        
        return 0;
    }
}
