<?php

// Headers básicos
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Información de debug
$debug_info = [
    'status' => 'healthy',
    'message' => 'Portfolio API is running',
    'timestamp' => date('Y-m-d H:i:s'),
    'version' => '1.0.0',
    'debug' => [
        'php_version' => PHP_VERSION,
        'server_time' => date('Y-m-d H:i:s'),
        'request_method' => $_SERVER['REQUEST_METHOD'] ?? 'unknown',
        'request_uri' => $_SERVER['REQUEST_URI'] ?? 'unknown',
        'server_port' => $_SERVER['SERVER_PORT'] ?? 'unknown',
        'remote_addr' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    ]
];

echo json_encode($debug_info, JSON_PRETTY_PRINT);
