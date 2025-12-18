header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['REQUEST_URI'], '/api/enroll') !== false) {
    // Get form data
    $data = $_POST;
    
    // Handle file uploads
    if (!empty($_FILES)) {
        foreach ($_FILES as $key => $file) {
            if ($file['error'] === UPLOAD_ERR_OK) {
                $data['files'][$key] = $file['name'];
            }
        }
    }
    
    // Generate enrollment number
    $enrollment_number = 'ENR-' . date('Ymd-His') . '-' . rand(100, 999);
    
    // Create enrollments directory if it doesn't exist
    if (!file_exists('enrollments')) {
        mkdir('enrollments', 0777, true);
    }
    
    // Save to JSON file
    $save_data = [
        'enrollment_number' => $enrollment_number,
        'timestamp' => date('c'),
        'data' => $data
    ];
    
    file_put_contents('enrollments/' . $enrollment_number . '.json', json_encode($save_data, JSON_PRETTY_PRINT));
    
    echo json_encode([
        'success' => true,
        'message' => 'Enrollment submitted successfully',
        'enrollment_number' => $enrollment_number,
        'next_steps' => 'Our team will contact you within 48 hours'
    ]);
} else {
    echo json_encode(['status' => 'API running', 'endpoint' => 'POST /api/enroll']);
}
?> > public/index.php

# 3. Create public/health.php
echo <?php
header('Content-Type: application/json');
echo json_encode(['status' => 'healthy', 'service' => 'Njerenje API', 'timestamp' => date('c')]);