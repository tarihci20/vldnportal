<?php
/**
 * Öğrenci Controller
 * Vildan Portal - Okul Yönetim Sistemi
 */

namespace App\Controllers;

use Core\Controller;
use App\Models\Student;
use App\Helpers\ExcelHelper;
use App\Middleware\PermissionMiddleware;

class StudentController extends Controller
{
    private $studentModel;
    
    public function __construct() {
        parent::__construct();
        $this->studentModel = new Student();
        
        // Kullanıcı giriş kontrolü (AJAX istekleri için JSON response)
        if (!isLoggedIn()) {
            // AJAX isteği ise JSON dön
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Oturum süresi doldu']);
                exit;
            }
            
            // Normal istek ise redirect
            redirect('/login');
        }
    }
    
    /**
     * Öğrenci listesi sayfası
     */
    public function index() {
        // Permission kontrolü - öğrenci sayfası yetkisi gerekli
        PermissionMiddleware::canView('students');
        
        // Sayfa numarası
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = STUDENTS_PER_PAGE;
        
        // Arama filtresi
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $class = isset($_GET['class']) ? trim($_GET['class']) : '';
        
        // Öğrencileri getir
        $filters = [];
        if (!empty($search)) {
            $filters['search'] = $search;
        }
        if (!empty($class)) {
            $filters['class'] = $class;
        }
        
        $result = $this->studentModel->getAll($page, $perPage, $filters);
        
        // Sınıf listesi (filtre için)
        $classes = $this->studentModel->getUniqueClasses();
        
        $this->view('students/index', [
            'students' => $result['data'],
            'pagination' => $result['pagination'],
            'classes' => $classes,
            'search' => $search,
            'classFilter' => $class,
            'title' => 'Öğrenci Bilgileri'
        ]);
    }
    
    /**
     * Öğrenci arama sayfası (Ana sayfa)
     */
    public function search() {
        $this->view('students/search', [
            'title' => 'Öğrenci Ara'
        ]);
    }
    
    /**
     * AJAX öğrenci arama (Debounce ile)
     */
    public function ajaxSearch() {
        // DEBUG
    error_log("=== AJAX SEARCH DEBUG ===");
    error_log("GET: " . json_encode($_GET));
    error_log("POST: " . json_encode($_POST));
    
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Geçersiz istek']);
            exit;
        }
        
        // JSON body'yi oku
        $input = json_decode(file_get_contents('php://input'), true);
        
        // POST veya JSON'dan veriyi al
        $query = isset($input['query']) ? trim($input['query']) : (isset($_POST['query']) ? trim($_POST['query']) : '');
        $page = isset($input['page']) ? (int)$input['page'] : (isset($_POST['page']) ? (int)$_POST['page'] : 1);
        $perPage = SEARCH_RESULTS_PER_PAGE;
        
        if (strlen($query) < 2) {
            echo json_encode([
                'success' => true,
                'data' => [],
                'pagination' => [
                    'total' => 0,
                    'page' => 1,
                    'pages' => 1
                ]
            ]);
            exit;
        }
        
        $result = $this->studentModel->search($query, $page, $perPage);
        
        echo json_encode([
            'success' => true,
            'data' => $result['data'],
            'pagination' => $result['pagination']
        ]);
        exit;
    }
    
    /**
     * Öğrenci detay sayfası
     */
    public function detail($id) {
        $student = $this->studentModel->findById($id);
        
        if (!$student) {
            setFlashMessage('Öğrenci bulunamadı.', 'error');
            redirect('/student-search');
        }
        
        $this->view('students/detail', [
            'student' => $student,
            'title' => $student['first_name'] . ' ' . $student['last_name']
        ]);
    }
    
    /**
     * Yeni öğrenci ekleme sayfası
     */
    public function create() {
        $this->view('students/create', [
            'title' => 'Yeni Öğrenci Ekle'
        ]);
    }
    
    /**
     * Öğrenci kaydetme işlemi
     */
    public function store() {
        // CSRF kontrolü
        if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
            setFlashMessage('Geçersiz form token.', 'error');
            redirect('/students/create');
        }
        
        // Veri validasyonu
        $data = [
            'tc_no' => cleanTcNo($_POST['tc_no'] ?? ''),
            'first_name' => cleanName($_POST['first_name'] ?? ''),
            'last_name' => cleanName($_POST['last_name'] ?? ''),
            'class' => cleanText($_POST['class'] ?? ''),
            'birth_date' => cleanText($_POST['birth_date'] ?? ''),
            'father_name' => cleanName($_POST['father_name'] ?? ''),
            'father_phone' => cleanPhone($_POST['father_phone'] ?? ''),
            'mother_name' => cleanName($_POST['mother_name'] ?? ''),
            'mother_phone' => cleanPhone($_POST['mother_phone'] ?? ''),
            'address' => cleanText($_POST['address'] ?? ''),
            'teacher_name' => cleanName($_POST['teacher_name'] ?? ''),
            'teacher_phone' => cleanPhone($_POST['teacher_phone'] ?? ''),
            'notes' => cleanText($_POST['notes'] ?? ''),
            'is_active' => 1,
            'created_by' => getCurrentUserId()
        ];
        
        // Zorunlu alanları kontrol et
        $errors = [];
        if (empty($data['first_name'])) {
            $errors[] = 'İsim alanı zorunludur.';
        }
        if (empty($data['last_name'])) {
            $errors[] = 'Soyisim alanı zorunludur.';
        }
        if (empty($data['tc_no'])) {
            $errors[] = 'TC kimlik numarası zorunludur.';
        } elseif (strlen($data['tc_no']) != 11) {
            $errors[] = 'TC kimlik numarası 11 haneli olmalıdır.';
        } elseif ($this->studentModel->isTcExists($data['tc_no'])) {
            $errors[] = 'Bu TC kimlik numarası ile kayıtlı bir öğrenci zaten var.';
        }
        if (empty($data['birth_date'])) {
            $errors[] = 'Doğum tarihi zorunludur.';
        }
        if (empty($data['class'])) {
            $errors[] = 'Sınıf alanı zorunludur.';
        }

        if (!empty($errors)) {
            setFlashMessage(implode('<br>', $errors), 'error');
            redirect('/students/create');
        }
        
        // Kayıt işlemi
        $studentId = $this->studentModel->create($data);
        
        // Başarılı olup olmadığını kontrol et
        // create() başarılı ise ID döndürür (int veya string olabilir), başarısızsa false döndürür
        if ($studentId !== false) {
            setFlashMessage('✅ Öğrenci başarıyla kaydedildi.', 'success');
            session_write_close(); // Session'ı diske yaz
            redirect('/students/' . $studentId);
        } else {
            $errorMsg = 'Öğrenci eklenirken bir hata oluştu.';
            setFlashMessage($errorMsg, 'error');
            session_write_close(); // Session'ı diske yaz
            redirect('/students/create');
        }
    }
    
    /**
     * Öğrenci düzenleme sayfası
     */
    public function edit($id) {
        $student = $this->studentModel->findById($id);
        
        if (!$student) {
            setFlashMessage('Öğrenci bulunamadı.', 'error');
            redirect('/students');
        }
        
        $this->view('students/edit', [
            'student' => $student,
            'title' => 'Öğrenci Düzenle'
        ]);
    }
    
    /**
     * Öğrenci güncelleme işlemi (POST /students/{id})
     */
    public function update($id) {
        $student = $this->studentModel->findById($id);
        
        if (!$student) {
            setFlashMessage('Öğrenci bulunamadı.', 'error');
            redirect('/students');
        }
        
        $this->handleEdit($id, $student);
    }
    
    /**
     * Öğrenci düzenleme işlemi
     */
    private function handleEdit($id, $oldData) {
        // CSRF kontrolü
        if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
            setFlashMessage('Geçersiz form token.', 'error');
            redirect('/students/' . $id . '/edit');
        }
        
        // Yeni veriler
        $data = [
            'tc_no' => cleanTcNo($_POST['tc_no'] ?? ''),
            'first_name' => cleanName($_POST['first_name'] ?? ''),
            'last_name' => cleanName($_POST['last_name'] ?? ''),
            'class' => cleanText($_POST['class'] ?? ''),
            'birth_date' => cleanText($_POST['birth_date'] ?? ''),
            'father_name' => cleanName($_POST['father_name'] ?? ''),
            'father_phone' => cleanPhone($_POST['father_phone'] ?? ''),
            'mother_name' => cleanName($_POST['mother_name'] ?? ''),
            'mother_phone' => cleanPhone($_POST['mother_phone'] ?? ''),
            'address' => cleanText($_POST['address'] ?? ''),
            'teacher_name' => cleanName($_POST['teacher_name'] ?? ''),
            'teacher_phone' => cleanPhone($_POST['teacher_phone'] ?? ''),
            'notes' => cleanText($_POST['notes'] ?? '')
        ];
        
        // Validasyon
        $errors = [];
        
        if (empty($data['first_name'])) {
            $errors[] = 'İsim alanı zorunludur.';
        }
        
        if (empty($data['last_name'])) {
            $errors[] = 'Soyisim alanı zorunludur.';
        }
        
        // TC kontrolü
        if (!empty($data['tc_no'])) {
            if (strlen($data['tc_no']) != 11) {
                $errors[] = 'TC kimlik numarası 11 haneli olmalıdır.';
            }
            
            // TC unique kontrolü (kendisi hariç)
            if ($this->studentModel->isTcExists($data['tc_no'], $id)) {
                $errors[] = 'Bu TC kimlik numarası ile kayıtlı başka bir öğrenci var.';
            }
        }
        
        if (!empty($errors)) {
            setFlashMessage(implode('<br>', $errors), 'error');
            redirect('/students/' . $id . '/edit');
        }
        
        // Güncelle
        if ($this->studentModel->update($id, $data)) {
            // Log kaydı
            logActivity('student_updated', 'students', $id, $oldData, $data);
            
            setFlashMessage('✅ Öğrenci bilgileri başarıyla güncellendi.', 'success');
            session_write_close(); // Session'ı diske yaz
            redirect('/students/' . $id);
        } else {
            setFlashMessage('Güncelleme sırasında bir hata oluştu.', 'error');
            session_write_close(); // Session'ı diske yaz
            redirect('/students/' . $id . '/edit');
        }
    }
    
    /**
     * Öğrenci silme
     */
    public function delete($id) {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Geçersiz istek']);
            exit;
        }
        
        // JSON body'yi oku
        $input = json_decode(file_get_contents('php://input'), true);
        $csrfToken = $input['csrf_token'] ?? ($_POST['csrf_token'] ?? '');
        
        // CSRF kontrolü
        if (!validateCsrfToken($csrfToken)) {
            echo json_encode(['success' => false, 'message' => 'Geçersiz token']);
            exit;
        }
        
        $student = $this->studentModel->findById($id);
        
        if (!$student) {
            echo json_encode(['success' => false, 'message' => 'Öğrenci bulunamadı']);
            exit;
        }
        
    if ($this->studentModel->hardDelete($id)) {
            // Log kaydı
            logActivity('student_deleted', 'students', $id, $student, null);
            
            echo json_encode(['success' => true, 'message' => 'Öğrenci başarıyla silindi']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Silme işlemi başarısız']);
        }
        exit;
    }
    
    /**
     * Excel'e aktar
     */
    public function exportExcel() {
        // Output buffer'ı temizle
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        // Tüm öğrencileri getir
        $students = $this->studentModel->getAllForExport();
        
        if (empty($students)) {
            setFlashMessage('Aktarılacak öğrenci bulunamadı.', 'warning');
            redirect('/students');
        }
        
        $filePath = exportStudentsToExcel($students);
        
        if ($filePath && file_exists($filePath)) {
            // Dosyayı indir
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
            header('Content-Length: ' . filesize($filePath));
            header('Cache-Control: max-age=0');
            header('Pragma: public');
            
            readfile($filePath);
            
            // Geçici dosyayı sil
            @unlink($filePath);
            exit;
        } else {
            setFlashMessage('Excel dosyası oluşturulamadı.', 'error');
            redirect('/students');
        }
    }
    
    /**
     * Excel şablonu indir
     */
    public function downloadTemplate() {
        // Output buffer'ı temizle
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        $filePath = createStudentExcelTemplate();
        
        if ($filePath && file_exists($filePath)) {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="ogrenci_sablonu.xlsx"');
            header('Content-Length: ' . filesize($filePath));
            header('Cache-Control: max-age=0');
            header('Pragma: public');
            
            readfile($filePath);
            
            // Geçici dosyayı sil
            @unlink($filePath);
            
            exit;
        } else {
            setFlashMessage('Şablon dosyası oluşturulamadı.', 'error');
            redirect('/students');
        }
    }
    
    /**
     * Excel'den içe aktar
     */
    public function importExcel() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                redirect('/students');
            }
            // CSRF kontrolü
            if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
                setFlashMessage('Geçersiz form token.', 'error');
                redirect('/students');
            }
            // Dosya kontrolü
            if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] !== UPLOAD_ERR_OK) {
                setFlashMessage('Dosya yüklenemedi.', 'error');
                redirect('/students');
            }
            $file = $_FILES['excel_file'];
            // Validasyon
            $errors = validateExcelFile($file);
            if (!empty($errors)) {
                setFlashMessage(implode('<br>', $errors), 'error');
                redirect('/students');
            }
            // Geçici dosyayı kaydet
            $tempPath = UPLOAD_PATH . '/excel/temp_' . time() . '.xlsx';
            if (!move_uploaded_file($file['tmp_name'], $tempPath)) {
                setFlashMessage('Dosya kaydedilemedi.', 'error');
                redirect('/students');
            }
            // Excel'i oku
            $result = importStudentsFromExcel($tempPath);
            // Geçici dosyayı sil
            unlink($tempPath);
            if (!$result['success']) {
                setFlashMessage($result['message'], 'error');
                redirect('/students');
            }
            // Veritabanına kaydet
            $imported = 0;
            $updated = 0;
            $skipped = 0;
            $importErrors = [];
            $duplicateTcCount = 0;
            $dbErrorCount = 0;
            // İmport modu: create veya update (POST parametresi)
            $importMode = $_POST['import_mode'] ?? 'skip'; // 'skip' veya 'update'
            foreach ($result['data'] as $studentData) {
                // TC unique kontrolü
                if (!empty($studentData['tc_no'])) {
                    $existingStudent = $this->studentModel->findWhere(['tc_no' => $studentData['tc_no']]);
                    if ($existingStudent) {
                        if ($importMode === 'update') {
                            // Mevcut öğrenciyi güncelle
                            if ($this->studentModel->update($existingStudent['id'], $studentData)) {
                                $updated++;
                            } else {
                                $skipped++;
                                $dbErrorCount++;
                                $importErrors[] = $studentData['first_name'] . ' ' . $studentData['last_name'] . ' (TC: ' . $studentData['tc_no'] . ') - Güncelleme hatası';
                            }
                            continue;
                        } else {
                            // Atla
                            $skipped++;
                            $duplicateTcCount++;
                            $importErrors[] = $studentData['first_name'] . ' ' . $studentData['last_name'] . ' (TC: ' . $studentData['tc_no'] . ') - Zaten kayıtlı';
                            continue;
                        }
                    }
                }
                $studentData['created_by'] = getCurrentUserId();
                try {
                    $inserted = $this->studentModel->create($studentData);
                    if ($inserted !== false && !is_string($inserted)) {
                        // Başarılı insert (ID döndü)
                        $imported++;
                    } else {
                        $skipped++;
                        $dbErrorCount++;
                        $errorMsg = $studentData['first_name'] . ' ' . $studentData['last_name'];
                        if (!empty($studentData['tc_no'])) {
                            $errorMsg .= ' (TC: ' . $studentData['tc_no'] . ')';
                        }
                        $errorMsg .= ' - Kayıt hatası (DB insert failed)';
                        if (is_string($inserted) && !empty($inserted)) {
                            $errorMsg .= ' | DB Error: ' . $inserted;
                        } else {
                            // PDO/Model hata detayını al
                            $pdoError = '';
                            // Lazy-loaded $db'yi access et
                            $db = $this->studentModel->db ?? $this->studentModel->getDb();
                            if ($db && method_exists($db, 'getError')) {
                                $pdoError = $db->getError();
                            }
                            if ($pdoError) {
                                $errorMsg .= ' | DB Error: ' . $pdoError;
                            }
                        }
                        $importErrors[] = $errorMsg;
                        // Log detailed error
                        error_log("Student import error: " . $errorMsg . " | Data: " . json_encode($studentData));
                    }
                } catch (Exception $e) {
                    $skipped++;
                    $dbErrorCount++;
                    $importErrors[] = $studentData['first_name'] . ' ' . $studentData['last_name'] . ' - Exception: ' . $e->getMessage();
                    error_log("Student import exception: " . $e->getMessage() . " | Data: " . json_encode($studentData));
                }
            }
            // Log kaydı
            logActivity('students_imported', 'students', null, null, [
                'imported' => $imported,
                'updated' => $updated,
                'skipped' => $skipped,
                'total' => count($result['data']),
                'duplicate_tc' => $duplicateTcCount,
                'db_errors' => $dbErrorCount,
                'mode' => $importMode
            ]);
            // Sonuç mesajı
            $message = "";
            if ($imported > 0) {
                $message .= "{$imported} yeni öğrenci eklendi.";
            }
            if ($updated > 0) {
                $message .= ($imported > 0 ? " " : "") . "{$updated} öğrenci güncellendi.";
            }
            if ($skipped > 0) {
                $message .= ($imported > 0 || $updated > 0 ? " " : "") . "{$skipped} öğrenci atlandı";
                if ($duplicateTcCount > 0) {
                    $message .= " ({$duplicateTcCount} TC zaten kayıtlı";
                }
                if ($dbErrorCount > 0) {
                    $message .= ($duplicateTcCount > 0 ? ', ' : ' (') . "{$dbErrorCount} kayıt hatası";
                }
                if ($duplicateTcCount > 0 || $dbErrorCount > 0) {
                    $message .= ")";
                }
                $message .= ".";
            }
            if (empty($message)) {
                $message = "Hiçbir öğrenci işlenmedi.";
            }
            if (!empty($result['errors'])) {
                $message .= " Excel okuma uyarıları: " . count($result['errors']);
            }
            // Hata detaylarını log dosyasına yaz
            if (!empty($importErrors)) {
                $logFile = LOG_PATH . '/excel_import_errors_' . date('Ymd_His') . '.log';
                file_put_contents($logFile, implode("\n", $importErrors));
            }
            setFlashMessage($message, ($imported > 0 || $updated > 0) ? 'success' : 'warning');
            // Hata detaylarını session'a kaydet (opsiyonel)
            if (!empty($importErrors) && count($importErrors) < 20) {
                $_SESSION['import_errors'] = $importErrors;
            }
            redirect('/students');
        } catch (Exception $ex) {
            setFlashMessage('Excel yükleme sırasında beklenmeyen bir hata oluştu: ' . $ex->getMessage(), 'error');
            redirect('/students');
        }
    }
    
    /**
     * Tüm öğrencileri sil (Emin misiniz onayı ile)
     */
    public function deleteAll() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/students');
        }
        
        // CSRF kontrolü
        if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
            echo json_encode(['success' => false, 'message' => 'Geçersiz token']);
            exit;
        }
        
        // Onay kontrolü
        $confirmation = $_POST['confirmation'] ?? '';
        if (strtolower(trim($confirmation)) !== 'eminim') {
            echo json_encode(['success' => false, 'message' => 'Onay metni hatalı. "eminim" yazmalısınız.']);
            exit;
        }
        
        $count = $this->studentModel->countAll();
        
        if ($this->studentModel->deleteAll()) {
            // Log kaydı
            logActivity('all_students_deleted', 'students', null, ['count' => $count], null);
            
            echo json_encode(['success' => true, 'message' => "{$count} öğrenci başarıyla silindi"]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Silme işlemi başarısız']);
        }
        exit;
    }
}