# Create the API router
echo "<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if (\$_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if (\$_SERVER['REQUEST_METHOD'] === 'POST' && \$_SERVER['REQUEST_URI'] === '/api/enroll') {
    require_once '../api/enroll.php';
} elseif (\$_SERVER['REQUEST_URI'] === '/health.php') {
    echo json_encode(['status' => 'healthy', 'service' => 'Njerenje API']);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Endpoint not found']);
}
?>" > public/index.php