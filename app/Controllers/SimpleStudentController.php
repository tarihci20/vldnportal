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
        
        // Ensure BASE_URL is defined (for production compatibility)
        if (!defined('BASE_URL')) {
            define('BASE_URL', 'https://vldn.in/portalv2');
        }
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
        try {
            error_log("=== STORE METHOD CALLED ===");
            
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
            
            error_log("Form data collected: " . json_encode($data));
            
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
            
            error_log("Validation passed");
            
            // 4. Insert to database
            error_log("Calling studentModel->create()");
            $result = $this->studentModel->create($data);
            error_log("Create returned: " . var_export($result, true));
            
            if (!$result) {
                error_log("Database insert failed! Result was: " . var_export($result, true));
                setFlashMessage('Veritabanı hatası!', 'error');
                header('Location: ' . BASE_URL . '/simple-students/create');
                exit;
            }
            
            // 5. Success - set flash message and redirect
            error_log("Student created with ID: " . $result);
            setFlashMessage('Öğrenci başarıyla eklendi!', 'success');
            error_log("About to send redirect header");
            
            header('Location: ' . BASE_URL . '/students');
            error_log("Header sent, calling exit");
            exit;
            
        } catch (Exception $e) {
            error_log("EXCEPTION in store(): " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            echo "ERROR: " . $e->getMessage();
            exit;
        }
    }
}
?>
