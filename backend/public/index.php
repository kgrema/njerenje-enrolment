<?php
// Save as backend/public/index.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Get request path
$request_uri = $_SERVER['REQUEST_URI'];
$api_path = str_replace('/index.php', '', $request_uri);

// Simple routing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($api_path, '/api/enroll') !== false) {
    // Handle enrollment submission
    require_once '../api/enroll.php';
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && $api_path === '/health') {
    // Health check endpoint
    echo json_encode([
        'status' => 'healthy',
        'timestamp' => date('c'),
        'service' => 'Njerenje Enrollment API'
    ]);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Endpoint not found']);
}
?>