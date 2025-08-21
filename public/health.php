<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

echo json_encode([
    'status' => 'healthy',
    'message' => 'Portfolio API is running',
    'timestamp' => date('Y-m-d H:i:s'),
    'version' => '1.0.0'
]);
