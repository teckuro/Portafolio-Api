<?php

require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;

echo "=== Database Diagnosis Script ===\n\n";

// 1. Check environment variables
echo "1. Environment Variables:\n";
$envVars = [
    'DB_CONNECTION',
    'DB_HOST',
    'DB_PORT',
    'DB_DATABASE',
    'DB_USERNAME',
    'DB_PASSWORD'
];

foreach ($envVars as $var) {
    $value = env($var);
    if ($var === 'DB_PASSWORD') {
        $value = $value ? '***HIDDEN***' : 'NOT SET';
    }
    echo "   $var: " . ($value ?: 'NOT SET') . "\n";
}
echo "\n";

// 2. Check database configuration
echo "2. Database Configuration:\n";
$config = Config::get('database.connections.' . Config::get('database.default'));
echo "   Default connection: " . Config::get('database.default') . "\n";
echo "   Driver: " . ($config['driver'] ?? 'NOT SET') . "\n";
echo "   Host: " . ($config['host'] ?? 'NOT SET') . "\n";
echo "   Port: " . ($config['port'] ?? 'NOT SET') . "\n";
echo "   Database: " . ($config['database'] ?? 'NOT SET') . "\n";
echo "   Username: " . ($config['username'] ?? 'NOT SET') . "\n";
echo "\n";

// 3. Test database connection
echo "3. Database Connection Test:\n";
try {
    $pdo = DB::connection()->getPdo();
    echo "   ✓ Connection successful\n";
    echo "   Driver: " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . "\n";
    echo "   Server version: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "\n";
    echo "   Database: " . DB::connection()->getDatabaseName() . "\n";
} catch (Exception $e) {
    echo "   ✗ Connection failed: " . $e->getMessage() . "\n";
    echo "   Error code: " . $e->getCode() . "\n";
}
echo "\n";

// 4. Check if migrations table exists
echo "4. Migrations Table Check:\n";
try {
    if (Schema::hasTable('migrations')) {
        echo "   ✓ Migrations table exists\n";
        $migrationCount = DB::table('migrations')->count();
        echo "   Migration count: $migrationCount\n";
        
        if ($migrationCount > 0) {
            echo "   Recent migrations:\n";
            $recentMigrations = DB::table('migrations')
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get();
            
            foreach ($recentMigrations as $migration) {
                echo "     - " . $migration->migration . "\n";
            }
        }
    } else {
        echo "   ✗ Migrations table does not exist\n";
    }
} catch (Exception $e) {
    echo "   ✗ Error checking migrations: " . $e->getMessage() . "\n";
}
echo "\n";

// 5. Check specific tables
echo "5. Table Existence Check:\n";
$tables = ['works', 'projects', 'users', 'admin_users'];
foreach ($tables as $table) {
    try {
        if (Schema::hasTable($table)) {
            echo "   ✓ Table '$table' exists\n";
            $count = DB::table($table)->count();
            echo "     Records: $count\n";
        } else {
            echo "   ✗ Table '$table' does not exist\n";
        }
    } catch (Exception $e) {
        echo "   ✗ Error checking table '$table': " . $e->getMessage() . "\n";
    }
}
echo "\n";

// 6. Test JSON operations
echo "6. JSON Field Operations Test:\n";
try {
    if (Schema::hasTable('works')) {
        // Test JSON insert
        $testData = [
            'company' => 'Test Company',
            'position' => 'Test Position',
            'description' => 'Test description',
            'start_date' => '2023-01-01',
            'end_date' => null,
            'location' => 'Test Location',
            'tech' => json_encode(['PHP', 'Laravel', 'PostgreSQL']),
            'achievements' => json_encode(['Test achievement']),
            'is_current' => false,
            'company_url' => 'https://test.com',
            'status' => 'active'
        ];
        
        $id = DB::table('works')->insertGetId($testData);
        echo "   ✓ Successfully inserted test record with ID: $id\n";
        
        // Test JSON retrieval
        $record = DB::table('works')->where('id', $id)->first();
        if ($record) {
            $tech = json_decode($record->tech, true);
            echo "   ✓ Successfully retrieved JSON data: " . json_encode($tech) . "\n";
        }
        
        // Clean up
        DB::table('works')->where('id', $id)->delete();
        echo "   ✓ Test record deleted\n";
    } else {
        echo "   ⚠ Works table does not exist, skipping JSON test\n";
    }
} catch (Exception $e) {
    echo "   ✗ JSON operations failed: " . $e->getMessage() . "\n";
}
echo "\n";

// 7. Check PostgreSQL specific settings
if (Config::get('database.default') === 'pgsql') {
    echo "7. PostgreSQL Specific Checks:\n";
    try {
        // Check PostgreSQL version
        $version = DB::select('SELECT version()')[0]->version;
        echo "   PostgreSQL version: " . $version . "\n";
        
        // Check if JSON functions are available
        $jsonTest = DB::select("SELECT '{\"test\": \"value\"}'::jsonb as test_json");
        echo "   ✓ JSONB operations supported\n";
        
        // Check search_path
        $searchPath = DB::select("SHOW search_path")[0]->search_path;
        echo "   Search path: " . $searchPath . "\n";
        
    } catch (Exception $e) {
        echo "   ✗ PostgreSQL specific check failed: " . $e->getMessage() . "\n";
    }
    echo "\n";
}

echo "=== Diagnosis Complete ===\n";
