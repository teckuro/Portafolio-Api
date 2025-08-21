<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Railway Configuration
    |--------------------------------------------------------------------------
    |
    | Configuración específica para el entorno de Railway
    |
    */

    'app' => [
        'name' => env('APP_NAME', 'Portfolio API'),
        'env' => env('APP_ENV', 'production'),
        'debug' => env('APP_DEBUG', false),
        'url' => env('APP_URL', 'https://railway.app'),
    ],

    'database' => [
        'default' => env('DB_CONNECTION', 'pgsql'),
        'connections' => [
            'pgsql' => [
                'driver' => 'pgsql',
                'host' => env('DB_HOST', env('PGHOST')),
                'port' => env('DB_PORT', env('PGPORT', '5432')),
                'database' => env('DB_DATABASE', env('PGDATABASE')),
                'username' => env('DB_USERNAME', env('PGUSER')),
                'password' => env('DB_PASSWORD', env('PGPASSWORD')),
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
