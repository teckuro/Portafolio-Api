<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Work;
use App\Models\Project;

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Database Test Script ===\n\n";

try {
    // Test database connection
    echo "1. Testing database connection...\n";
    $pdo = DB::connection()->getPdo();
    echo "✓ Database connection successful\n";
    echo "   Driver: " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . "\n";
    echo "   Database: " . DB::connection()->getDatabaseName() . "\n\n";

    // Test table existence
    echo "2. Checking table existence...\n";
    $tables = ['works', 'projects', 'users', 'migrations'];
    
    foreach ($tables as $table) {
        if (Schema::hasTable($table)) {
            echo "✓ Table '$table' exists\n";
        } else {
            echo "✗ Table '$table' does not exist\n";
        }
    }
    echo "\n";

    // Test model operations
    echo "3. Testing model operations...\n";
    
    // Test Work model
    $workCount = Work::count();
    echo "   Works count: $workCount\n";
    
    if ($workCount > 0) {
        $work = Work::first();
        echo "   First work: " . $work->company . " - " . $work->position . "\n";
        echo "   Tech stack: " . json_encode($work->tech) . "\n";
    }
    
    // Test Project model
    $projectCount = Project::count();
    echo "   Projects count: $projectCount\n";
    
    if ($projectCount > 0) {
        $project = Project::first();
        echo "   First project: " . $project->title . "\n";
        echo "   Tech stack: " . json_encode($project->tech_stack) . "\n";
    }
    
    echo "\n4. Testing JSON field operations...\n";
    
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
        echo "✓ Successfully created test work with JSON fields\n";
        
        // Clean up test data
        $testWork->delete();
        echo "✓ Test work deleted\n";
    } catch (Exception $e) {
        echo "✗ Error creating test work: " . $e->getMessage() . "\n";
    }
    
    echo "\n=== Test completed successfully! ===\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
