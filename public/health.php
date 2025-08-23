<?php

// Headers básicos
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Información básica de salud
$health_info = [
    'status' => 'healthy',
    'message' => 'Portfolio API is running',
    'timestamp' => date('Y-m-d H:i:s'),
    'version' => '1.0.0'
];

echo json_encode($health_info, JSON_PRETTY_PRINT);
