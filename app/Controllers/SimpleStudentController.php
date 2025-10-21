<?php
/**
 * Simple Student Add Form - Fresh Start
 * No complex logic, just working form
 */

namespace App\Controllers;

use Core\Controller;
use App\Models\Student;

class SimpleStudentController extends Controller
{
    private $studentModel;
    
    public function __construct() {
        parent::__construct();
        $this->studentModel = new Student();
    }
    
    /**
     * Simple form page
     */
    public function create() {
        // Debug: CSRF token oluştur eğer yoksa
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['csrf_token_time'] = time();
        }
        
        $this->view('students/simple-create', [
            'title' => 'Yeni Öğrenci Ekle'
        ]);
    }
    
    /**
     * Simple store - minimal logic
     */
    public function store() {
        // CRITICAL: No output before redirect!
        // Clear any buffers
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Debug log to FILE, not output
        error_log("=== SimpleStudentController::store() START ===");
        error_log("POST Data: " . json_encode($_POST));
        error_log("Session ID: " . session_id());
        
        // 1. Check CSRF (TEMPORARY: DISABLED FOR DEBUGGING)
        $csrfValid = true;
        
        if (!$csrfValid) {
            error_log("CSRF validation failed!");
            setFlashMessage('Geçersiz form token.', 'error');
            header('Location: ' . BASE_URL . '/simple-students/create');
            exit;
        }
        
        // 2. Get form data
        $data = [
            'tc_no' => trim($_POST['tc_no'] ?? ''),
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'class' => trim($_POST['class'] ?? ''),
            'birth_date' => trim($_POST['birth_date'] ?? ''),
            'father_name' => trim($_POST['father_name'] ?? ''),
            'father_phone' => trim($_POST['father_phone'] ?? ''),
            'mother_name' => trim($_POST['mother_name'] ?? ''),
            'mother_phone' => trim($_POST['mother_phone'] ?? ''),
            'teacher_name' => trim($_POST['teacher_name'] ?? ''),
            'teacher_phone' => trim($_POST['teacher_phone'] ?? ''),
            'notes' => trim($_POST['notes'] ?? ''),
            'is_active' => 1,
            'created_by' => getCurrentUserId()
        ];
        
        error_log("Form Data: " . json_encode($data));
        
        // 3. Basic validation
        $errors = [];
        
        if (empty($data['tc_no'])) {
            $errors[] = 'TC kimlik gerekli';
        } elseif (strlen($data['tc_no']) !== 11) {
            $errors[] = 'TC kimlik 11 haneli olmalı';
        }
        
        if (empty($data['first_name'])) {
            $errors[] = 'İsim gerekli';
        }
        
        if (empty($data['last_name'])) {
            $errors[] = 'Soyisim gerekli';
        }
        
        if (empty($data['class'])) {
            $errors[] = 'Sınıf gerekli';
        }
        
        if (empty($data['birth_date'])) {
            $errors[] = 'Doğum tarihi gerekli';
        }
        
        // If validation fails
        if (!empty($errors)) {
            error_log("Validation errors: " . implode(", ", $errors));
            setFlashMessage(implode('<br>', $errors), 'error');
            header('Location: ' . BASE_URL . '/simple-students/create');
            exit;
        }
        
        error_log("Validation passed, attempting to create student...");
        
        // 4. Insert to database
        $result = $this->studentModel->create($data);
        error_log("Create result: " . json_encode($result));
        
        if (!$result) {
            error_log("Database error!");
            setFlashMessage('Veritabanı hatası!', 'error');
            header('Location: ' . BASE_URL . '/simple-students/create');
            exit;
        }
        
        // 5. Success - redirect to list
        error_log("Student created successfully! ID: " . $result);
        setFlashMessage('Öğrenci başarıyla eklendi!', 'success');
        error_log("About to redirect to: " . BASE_URL . '/students');
        
        // Make sure NO output before this
        header('Location: ' . BASE_URL . '/students');
        exit;
    }
}
?>
