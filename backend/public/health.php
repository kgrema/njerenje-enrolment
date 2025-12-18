<?php
// Save as backend/public/health.php
header('Content-Type: application/json');

$status = [
    'status' => 'healthy',
    'service' => 'Njerenje Enrollment API',
    'timestamp' => date('c'),
    'checks' => []
];

// Check PHP version
$status['checks']['php_version'] = PHP_VERSION;

// Check database connection
try {
    require_once '../config/database.php';
    $database = new Database();
    $conn = $database->getConnection();
    $status['checks']['database'] = 'connected';
} catch (Exception $e) {
    $status['checks']['database'] = 'disconnected';
    $status['status'] = 'degraded';
}

// Check disk space
$free_space = disk_free_space('/');
$total_space = disk_total_space('/');
$used_percent = round(($total_space - $free_space) / $total_space * 100, 2);
$status['checks']['disk_space'] = "{$used_percent}% used";

echo json_encode($status);
?>