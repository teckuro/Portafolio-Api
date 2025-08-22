<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Work;
use App\Models\Project;

class TestDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test database connection and operations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Database Test Command ===');
        $this->newLine();

        try {
            // Test database connection
            $this->info('1. Testing database connection...');
            $pdo = DB::connection()->getPdo();
            $this->info('✓ Database connection successful');
            $this->line('   Driver: ' . $pdo->getAttribute(\PDO::ATTR_DRIVER_NAME));
            $this->line('   Database: ' . DB::connection()->getDatabaseName());
            $this->newLine();

            // Test table existence
            $this->info('2. Checking table existence...');
            $tables = ['works', 'projects', 'users', 'migrations'];
            
            foreach ($tables as $table) {
                if (Schema::hasTable($table)) {
                    $this->info("✓ Table '$table' exists");
                } else {
                    $this->error("✗ Table '$table' does not exist");
                }
            }
            $this->newLine();

            // Test model operations
            $this->info('3. Testing model operations...');
            
            // Test Work model
            $workCount = Work::count();
            $this->line("   Works count: $workCount");
            
            if ($workCount > 0) {
                $work = Work::first();
                $this->line("   First work: " . $work->company . " - " . $work->position);
                $this->line("   Tech stack: " . json_encode($work->tech));
            }
            
            // Test Project model
            $projectCount = Project::count();
            $this->line("   Projects count: $projectCount");
            
            if ($projectCount > 0) {
                $project = Project::first();
                $this->line("   First project: " . $project->title);
                $this->line("   Tech stack: " . json_encode($project->tech_stack));
            }
            
            $this->newLine();
            $this->info('4. Testing JSON field operations...');
            
            // Test creating a work with JSON fields
            try {
                $testWork = Work::create([
                    'company' => 'Test Company',
                    'position' => 'Test Position',
                    'description' => 'Test description',
                    'start_date' => '2023-01-01',
                    'end_date' => null,
                    'location' => 'Test Location',
                    'tech' => json_encode(['PHP', 'Laravel', 'PostgreSQL']),
                    'achievements' => json_encode(['Test achievement 1', 'Test achievement 2']),
                    'is_current' => false,
                    'company_url' => 'https://test.com',
                    'status' => 'active'
                ]);
                $this->info('✓ Successfully created test work with JSON fields');
                
                // Clean up test data
                $testWork->delete();
                $this->info('✓ Test work deleted');
            } catch (\Exception $e) {
                $this->error('✗ Error creating test work: ' . $e->getMessage());
            }
            
            $this->newLine();
            $this->info('=== Test completed successfully! ===');
            
        } catch (\Exception $e) {
            $this->error('✗ Error: ' . $e->getMessage());
            $this->error('Stack trace:');
            $this->error($e->getTraceAsString());
            return 1;
        }

        return 0;
    }
}
