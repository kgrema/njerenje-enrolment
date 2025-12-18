<?php
// Save as backend/api/enroll.php
require_once '../config/database.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Handle enrollment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Generate enrollment number
        $enrollment_number = 'ENR-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(4)));
        
        // Process form data
        $data = [
            'enrollment_number' => $enrollment_number,
            'language' => $_POST['language'] ?? 'en',
            'surname' => $_POST['surname'] ?? '',
            'forenames' => $_POST['forenames'] ?? '',
            'dob' => $_POST['dob'] ?? '',
            'gender' => $_POST['gender'] ?? '',
            'nationality' => $_POST['nationality'] ?? '',
            'passport_number' => $_POST['passport'] ?? '',
            'home_language' => $_POST['home_language'] ?? '',
            'english_level' => $_POST['english_level'] ?? '',
            'guardian_name' => $_POST['guardian_name'] ?? '',
            'guardian_relationship' => $_POST['guardian_relationship'] ?? '',
            'address' => $_POST['address'] ?? '',
            'mother_name' => $_POST['mother_name'] ?? '',
            'mother_mobile' => $_POST['mother_mobile'] ?? '',
            'mother_email' => $_POST['mother_email'] ?? '',
            'father_name' => $_POST['father_name'] ?? '',
            'father_mobile' => $_POST['father_mobile'] ?? '',
            'father_email' => $_POST['father_email'] ?? '',
            'emergency_name' => $_POST['emergency_name'] ?? '',
            'emergency_relationship' => $_POST['emergency_relationship'] ?? '',
            'emergency_mobile1' => $_POST['emergency_mobile1'] ?? '',
            'emergency_mobile2' => $_POST['emergency_mobile2'] ?? '',
            'academic_stage' => $_POST['academic_stage'] ?? '',
            'grade' => $_POST['grade'] ?? '',
            'subjects_interests' => $_POST['subjects_interests'] ?? '',
            'medical_condition' => $_POST['medical_condition'] ?? 'no',
            'medical_details' => $_POST['medical_details'] ?? '',
            'medications' => $_POST['medications'] ?? '',
            'immunization' => $_POST['immunization'] ?? '',
            'doctor_name' => $_POST['doctor_name'] ?? '',
            'doctor_contact' => $_POST['doctor_contact'] ?? '',
            'parent_signature' => $_POST['parent_signature'] ?? '',
            'parent_declaration_date' => $_POST['parent_declaration_date'] ?? date('Y-m-d'),
            'status' => 'pending'
        ];
        
        // Handle file uploads
        $upload_dir = '../uploads/' . date('Y/m/');
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Process each uploaded file
        $file_fields = [
            'birth_certificate', 'passport_copy', 'photo', 
            'school_report', 'immunization_card', 'payment_proof',
            'sen_report', 'transfer_certificate'
        ];
        
        foreach ($file_fields as $field) {
            if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
                $file_ext = pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
                $new_filename = $enrollment_number . '-' . $field . '.' . $file_ext;
                $destination = $upload_dir . $new_filename;
                
                if (move_uploaded_file($_FILES[$field]['tmp_name'], $destination)) {
                    $data[$field . '_path'] = $destination;
                }
            }
        }
        
        // Insert into database
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO enrollments ($columns) VALUES ($placeholders)";
        $stmt = $db->prepare($sql);
        
        if ($stmt->execute($data)) {
            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'Enrollment submitted successfully',
                'enrollment_number' => $enrollment_number,
                'next_steps' => 'Our team will contact you within 48 hours'
            ]);
        } else {
            throw new Exception('Database insertion failed');
        }
        
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Submission failed: ' . $e->getMessage()
        ]);
    }
}
?>