<?php
/**
 * Student API Controller
 * AJAX endpoints for student operations
 */

namespace App\Controllers\Api;

use Core\Controller;
use Core\Response;
use Core\Auth;
use App\Models\Student;

class StudentApiController extends Controller
{
    private $studentModel;
    
    public function __construct() {
        parent::__construct();
        
        // Check if user is authenticated
        if (!Auth::check()) {
            Response::json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
            exit;
        }
        
        $this->studentModel = new Student();
    }
    
    /**
     * Search students (Debounce search)
     * GET /api/students/search?q=keyword
     */
    public function search() {
        try {
            $query = $_GET['q'] ?? '';
            
            // Minimum 2 characters
            if (strlen($query) < 2) {
                Response::json([
                    'success' => true,
                    'students' => []
                ]);
                return;
            }
            
            // Search students
            $students = $this->studentModel->search($query);
            
            Response::json([
                'success' => true,
                'students' => $students,
                'count' => count($students)
            ]);
            
        } catch (\Exception $e) {
            Response::json([
                'success' => false,
                'message' => 'Arama sırasında bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get student by ID
     * GET /api/students/{id}
     */
    public function show($id) {
        try {
            $student = $this->studentModel->find($id);
            
            if (!$student) {
                Response::json([
                    'success' => false,
                    'message' => 'Öğrenci bulunamadı'
                ], 404);
                return;
            }
            
            Response::json([
                'success' => true,
                'student' => $student
            ]);
            
        } catch (\Exception $e) {
            Response::json([
                'success' => false,
                'message' => 'Hata: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Create new student
     * POST /api/students/create
     */
    public function create() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validation
            $errors = $this->validateStudent($data);
            if (!empty($errors)) {
                Response::json([
                    'success' => false,
                    'message' => 'Doğrulama hatası',
                    'errors' => $errors
                ], 422);
                return;
            }
            
            // Check TC duplicate
            if (!empty($data['tc_no'])) {
                $existing = $this->studentModel->findByTcNo($data['tc_no']);
                if ($existing) {
                    Response::json([
                        'success' => false,
                        'message' => 'Bu TC Kimlik No zaten kayıtlı'
                    ], 422);
                    return;
                }
            }
            
            // Create student
            $studentId = $this->studentModel->create($data);
            
            Response::json([
                'success' => true,
                'message' => 'Öğrenci başarıyla eklendi',
                'student_id' => $studentId
            ], 201);
            
        } catch (\Exception $e) {
            Response::json([
                'success' => false,
                'message' => 'Öğrenci eklenirken hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update student
     * PUT /api/students/{id}
     */
    public function update($id) {
        try {
            // Check if student exists
            $student = $this->studentModel->find($id);
            if (!$student) {
                Response::json([
                    'success' => false,
                    'message' => 'Öğrenci bulunamadı'
                ], 404);
                return;
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validation
            $errors = $this->validateStudent($data, $id);
            if (!empty($errors)) {
                Response::json([
                    'success' => false,
                    'message' => 'Doğrulama hatası',
                    'errors' => $errors
                ], 422);
                return;
            }
            
            // Check TC duplicate (exclude current student)
            if (!empty($data['tc_no'])) {
                $existing = $this->studentModel->findByTcNo($data['tc_no']);
                if ($existing && $existing['id'] != $id) {
                    Response::json([
                        'success' => false,
                        'message' => 'Bu TC Kimlik No başka bir öğrencide kayıtlı'
                    ], 422);
                    return;
                }
            }
            
            // Update student
            $this->studentModel->update($id, $data);
            
            Response::json([
                'success' => true,
                'message' => 'Öğrenci başarıyla güncellendi'
            ]);
            
        } catch (\Exception $e) {
            Response::json([
                'success' => false,
                'message' => 'Güncelleme sırasında hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete student
     * DELETE /api/students/{id}
     */
    public function delete($id) {
        try {
            // Check if student exists
            $student = $this->studentModel->find($id);
            if (!$student) {
                Response::json([
                    'success' => false,
                    'message' => 'Öğrenci bulunamadı'
                ], 404);
                return;
            }
            
            // Check authorization
            $user = Auth::user();
            if (!in_array($user['role_slug'], ['admin', 'mudur'])) {
                Response::json([
                    'success' => false,
                    'message' => 'Bu işlem için yetkiniz yok'
                ], 403);
                return;
            }
            
            // Delete student
            $this->studentModel->delete($id);
            
            Response::json([
                'success' => true,
                'message' => 'Öğrenci başarıyla silindi'
            ]);
            
        } catch (\Exception $e) {
            Response::json([
                'success' => false,
                'message' => 'Silme işlemi sırasında hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Import students from Excel
     * POST /api/students/import
     */
    public function import() {
        try {
            // Check if file was uploaded
            if (!isset($_FILES['file'])) {
                Response::json([
                    'success' => false,
                    'message' => 'Dosya yüklenmedi'
                ], 400);
                return;
            }
            
            $file = $_FILES['file'];
            
            // Check file extension
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($extension, ['xls', 'xlsx'])) {
                Response::json([
                    'success' => false,
                    'message' => 'Sadece Excel dosyaları (.xls, .xlsx) kabul edilir'
                ], 400);
                return;
            }
            
            // Import students
            $result = importStudentsFromExcel($file['tmp_name']);
            
            Response::json([
                'success' => true,
                'message' => 'Excel dosyası başarıyla içe aktarıldı',
                'imported' => $result['imported'],
                'skipped' => $result['skipped'],
                'errors' => $result['errors']
            ]);
            
        } catch (\Exception $e) {
            Response::json([
                'success' => false,
                'message' => 'İçe aktarma sırasında hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Export students to Excel
     * GET /api/students/export
     */
    public function export() {
        try {
            $filters = [
                'class' => $_GET['class'] ?? null,
                'search' => $_GET['search'] ?? null
            ];
            
            // Get students
            $students = $this->studentModel->getAll($filters);
            
            // Generate Excel
            $filename = exportStudentsToExcel($students);
            
            // Return file path
            Response::json([
                'success' => true,
                'message' => 'Excel dosyası oluşturuldu',
                'filename' => $filename,
                'download_url' => url('/api/students/download?file=' . urlencode($filename))
            ]);
            
        } catch (\Exception $e) {
            Response::json([
                'success' => false,
                'message' => 'Dışa aktarma sırasında hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Download Excel file
     * GET /api/students/download?file=filename
     */
    public function download() {
        try {
            $filename = $_GET['file'] ?? '';
            
            if (empty($filename)) {
                Response::json([
                    'success' => false,
                    'message' => 'Dosya adı belirtilmedi'
                ], 400);
                return;
            }
            
            $filepath = ROOT_PATH . '/public/assets/uploads/excel/' . basename($filename);
            
            if (!file_exists($filepath)) {
                Response::json([
                    'success' => false,
                    'message' => 'Dosya bulunamadı'
                ], 404);
                return;
            }
            
            // Download file
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
            header('Content-Length: ' . filesize($filepath));
            readfile($filepath);
            
            // Delete file after download
            unlink($filepath);
            exit;
            
        } catch (\Exception $e) {
            Response::json([
                'success' => false,
                'message' => 'İndirme sırasında hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get student statistics
     * GET /api/students/stats
     */
    public function stats() {
        try {
            $stats = [
                'total' => $this->studentModel->count(),
                'by_class' => $this->studentModel->countByClass(),
                'recent' => $this->studentModel->getRecent(5)
            ];
            
            Response::json([
                'success' => true,
                'stats' => $stats
            ]);
            
        } catch (\Exception $e) {
            Response::json([
                'success' => false,
                'message' => 'İstatistik alınırken hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Validate student data
     */
    private function validateStudent($data, $excludeId = null) {
        $errors = [];
        
        // Required fields
        if (empty($data['first_name'])) {
            $errors['first_name'] = 'İsim zorunludur';
        }
        
        if (empty($data['last_name'])) {
            $errors['last_name'] = 'Soyisim zorunludur';
        }
        
        if (empty($data['class'])) {
            $errors['class'] = 'Sınıf zorunludur';
        }
        
        if (empty($data['birth_date'])) {
            $errors['birth_date'] = 'Doğum tarihi zorunludur';
        }
        
        // TC Kimlik No validation
        if (!empty($data['tc_no'])) {
            if (strlen($data['tc_no']) != 11 || !ctype_digit($data['tc_no'])) {
                $errors['tc_no'] = 'TC Kimlik No 11 haneli olmalıdır';
            }
        }
        
        // Phone validation
        $phoneFields = ['father_phone', 'mother_phone', 'teacher_phone'];
        foreach ($phoneFields as $field) {
            if (!empty($data[$field])) {
                if (strlen($data[$field]) != 11 || !ctype_digit($data[$field])) {
                    $errors[$field] = 'Telefon numarası 11 haneli olmalıdır (0 ile başlayarak)';
                }
            }
        }
        
        // Date validation
        if (!empty($data['birth_date'])) {
            $date = \DateTime::createFromFormat('Y-m-d', $data['birth_date']);
            if (!$date) {
                $errors['birth_date'] = 'Geçersiz tarih formatı (YYYY-MM-DD)';
            }
        }
        
        return $errors;
    }
}