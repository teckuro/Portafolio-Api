<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:4200', 
        'http://localhost:4201', 
        'http://127.0.0.1:4200', 
        'http://127.0.0.1:4201', 
        'https://angular-portafolio.vercel.app',
        'https://angular-portafolio-git-master-teckuro.vercel.app',
        'https://angular-portafolio-teckuro.vercel.app',
        'https://*.vercel.app', 
        'https://*.netlify.app', 
        'https://*.github.io', 
        'https://*.railway.app', 
        'https://*.render.com'
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
