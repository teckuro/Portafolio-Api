<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Production Configuration
    |--------------------------------------------------------------------------
    |
    | Configuración específica para el entorno de producción
    |
    */

    'app_url' => env('APP_URL', 'https://your-api-domain.com'),
    'app_env' => 'production',
    'app_debug' => false,
    
    'database' => [
        'default' => env('DB_CONNECTION', 'pgsql'),
        'connections' => [
            'pgsql' => [
                'driver' => 'pgsql',
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '5432'),
                'database' => env('DB_DATABASE', 'forge'),
                'username' => env('DB_USERNAME', 'forge'),
                'password' => env('DB_PASSWORD', ''),
                'charset' => 'utf8',
                'prefix' => '',
                'prefix_indexes' => true,
                'schema' => 'public',
                'sslmode' => 'prefer',
            ],
        ],
    ],

    'filesystems' => [
        'default' => env('FILESYSTEM_DISK', 'public'),
        'disks' => [
            'public' => [
                'driver' => 'local',
                'root' => storage_path('app/public'),
                'url' => env('APP_URL').'/storage',
                'visibility' => 'public',
            ],
        ],
    ],
];
