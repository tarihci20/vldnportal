<?php
/**
 * Admin Controller
 * Vildan Portal - Admin Yönetim Sayfası
 */

namespace App\Controllers;

use Core\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Role;
use App\Models\EtutFormSettings;

class AdminController extends Controller
{
    private $userModel;
    private $studentModel;
    private $roleModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
        $this->studentModel = new Student();
        $this->roleModel = new Role();
        
        // API endpoints için login kontrol'ünü BYPASS ET
        // (deleteUser, getRolePermissions vb. JSON işlemleri)
        $isApiCall = $this->isApiCall();
        
        if (!$isApiCall) {
            // Kullanıcı giriş kontrolü
            if (!isLoggedIn()) {
                redirect('/login');
            }
            
            // Admin kontrolü
            $user = currentUser();
            if ($user['role'] !== 'admin') {
                setFlashMessage('Bu sayfaya erişim yetkiniz yok.', 'error');
                redirect('/dashboard');
            }
        }
    }
    
    /**
     * Check if this is an API call (JSON response endpoints)
     */
    private function isApiCall() {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        
        // Check common API patterns
        $apiPatterns = [
            '/admin/users/*/delete',      // POST /admin/users/{id}/delete
            '/admin/users/delete',         // POST /admin/users/delete
            '/admin/roles/permissions',    // GET/POST role permissions
            '/api/',                       // Any /api/ endpoint
        ];
        
        foreach ($apiPatterns as $pattern) {
            $pattern = str_replace('*', '[0-9]+', preg_quote($pattern, '#'));
            if (preg_match('#' . $pattern . '#', $uri)) {
                error_log("AdminController: API call detected - {$uri}");
                return true;
            }
        }
        
        error_log("AdminController: Normal call - {$uri}");
        return false;
    }
    
    /**
     * Admin Ayarlar Sayfası
     */
    public function settings() {
        // Tüm kullanıcıları getir
        $users = $this->userModel->getAll();
        
        // Öğrenci sayısını getir
        $studentCount = $this->studentModel->countAll();
        
        $this->view('admin/settings', [
            'title' => 'Admin Ayarlar',
            'users' => $users,
            'studentCount' => $studentCount
        ]);
    }
    
    /**
     * Admin şifre değiştirme
     */
    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/settings');
        }
        
        // CSRF kontrolü
        if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
            setFlashMessage('Geçersiz form token.', 'error');
            redirect('/admin/settings');
        }
        
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validasyon
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            setFlashMessage('Tüm alanları doldurun.', 'error');
            redirect('/admin/settings');
        }
        
        if ($newPassword !== $confirmPassword) {
            setFlashMessage('Yeni şifreler eşleşmiyor.', 'error');
            redirect('/admin/settings');
        }
        
        if (strlen($newPassword) < 6) {
            setFlashMessage('Şifre en az 6 karakter olmalıdır.', 'error');
            redirect('/admin/settings');
        }
        
        // Mevcut şifreyi kontrol et
        $userId = getCurrentUserId();
        $user = $this->userModel->findById($userId);
        
        if (!password_verify($currentPassword, $user['password'])) {
            setFlashMessage('Mevcut şifre yanlış.', 'error');
            redirect('/admin/settings');
        }
        
        // Şifreyi güncelle
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        if ($this->userModel->update($userId, ['password' => $hashedPassword])) {
            // logActivity('password_changed', 'users', $userId, null, null);
            setFlashMessage('Şifreniz başarıyla değiştirildi.', 'success');
        } else {
            setFlashMessage('Şifre değiştirme başarısız.', 'error');
        }
        
        redirect('/admin/settings');
    }
    
    /**
     * Yeni kullanıcı ekleme
     */
    public function addUser() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/settings');
        }
        
        // CSRF kontrolü
        if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
            setFlashMessage('Geçersiz form token.', 'error');
            redirect('/admin/settings');
        }
        
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'teacher';
        
        // Validasyon
        if (empty($username) || empty($email) || empty($password)) {
            setFlashMessage('Tüm alanları doldurun.', 'error');
            redirect('/admin/settings');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            setFlashMessage('Geçersiz e-posta adresi.', 'error');
            redirect('/admin/settings');
        }
        
        if (strlen($password) < 6) {
            setFlashMessage('Şifre en az 6 karakter olmalıdır.', 'error');
            redirect('/admin/settings');
        }
        
        // Kullanıcı adı kontrolü
        if ($this->userModel->findWhere(['username' => $username])) {
            setFlashMessage('Bu kullanıcı adı zaten kullanılıyor.', 'error');
            redirect('/admin/settings');
        }
        
        // E-posta kontrolü
        if ($this->userModel->findWhere(['email' => $email])) {
            setFlashMessage('Bu e-posta adresi zaten kullanılıyor.', 'error');
            redirect('/admin/settings');
        }
        
        // Kullanıcı oluştur
        $data = [
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role,
            'is_active' => 1
        ];
        
        $userId = $this->userModel->create($data);
        
        if ($userId) {
            // logActivity('user_created', 'users', $userId, null, ['username' => $username, 'role' => $role]);
            setFlashMessage('Kullanıcı başarıyla oluşturuldu.', 'success');
        } else {
            setFlashMessage('Kullanıcı oluşturma başarısız.', 'error');
        }
        
        redirect('/admin/settings');
    }
    
    /**
     * Kullanıcılar Listesi
     */
    public function users() {
        $page = $_GET['page'] ?? 1;
        $result = $this->userModel->getAllWithRoles($page, 20);
        
        $this->view('admin/users/index', [
            'title' => 'Kullanıcı Yönetimi',
            'users' => $result['data'],
            'pagination' => $result['pagination']
        ]);
    }
    
    /**
     * Kullanıcı Ekleme Formu
     */
    public function createUser() {
        $roles = $this->userModel->getAllRoles();
        $pages = $this->userModel->getAllPages();
        
        $this->view('admin/users/create', [
            'title' => 'Yeni Kullanıcı Ekle',
            'roles' => $roles,
            'pages' => $pages
        ]);
    }
    
    /**
     * Kullanıcı Kaydetme
     */
    public function storeUser() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/users');
        }
        
        if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
            setFlashMessage('Geçersiz form token.', 'error');
            redirect('/admin/users/create');
        }
        
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $fullName = trim($_POST['full_name'] ?? '');
        $password = $_POST['password'] ?? '';
        $roleId = $_POST['role_id'] ?? null;
        
        // Validasyon
        if (empty($username) || empty($email) || empty($password) || empty($roleId)) {
            setFlashMessage('Tüm zorunlu alanları doldurun.', 'error');
            redirect('/admin/users/create');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            setFlashMessage('Geçersiz e-posta adresi.', 'error');
            redirect('/admin/users/create');
        }
        
        if (strlen($password) < 6) {
            setFlashMessage('Şifre en az 6 karakter olmalıdır.', 'error');
            redirect('/admin/users/create');
        }
        
        if ($this->userModel->usernameExists($username)) {
            setFlashMessage('Bu kullanıcı adı zaten kullanılıyor.', 'error');
            redirect('/admin/users/create');
        }
        
        if ($this->userModel->emailExists($email)) {
            setFlashMessage('Bu e-posta adresi zaten kullanılıyor.', 'error');
            redirect('/admin/users/create');
        }
        
        // Kullanıcı oluştur
        $data = [
            'username' => $username,
            'email' => $email,
            'full_name' => $fullName,
            'password' => $password,
            'role_id' => $roleId,
            'is_active' => 1
        ];
        
        // Müdür yardımcısı ise etut_type ekle
        $role = $this->userModel->getRoleById($roleId);
        if ($role && $role['role_name'] === 'vice_principal') {
            $etutType = trim($_POST['etut_type'] ?? '');
            if ($etutType && in_array($etutType, ['ortaokul', 'lise'])) {
                $data['etut_type'] = $etutType;
            }
        }
        
        try {
            $userId = $this->userModel->create($data);
            
            if ($userId) {
                // İzinleri kaydet
                $permissions = $_POST['permissions'] ?? [];
                $this->saveUserPermissions($roleId, $permissions);
                
                // logActivity('user_created', 'users', $userId, null, ['username' => $username]);
                setFlashMessage('Kullanıcı başarıyla oluşturuldu.', 'success');
                redirect('/admin/users');
            } else {
                error_log("User creation failed - no ID returned");
                setFlashMessage('Kullanıcı oluşturma başarısız (Database error).', 'error');
                redirect('/admin/users/create');
            }
        } catch (\Exception $e) {
            error_log("User creation exception: " . $e->getMessage());
            setFlashMessage('Kullanıcı oluşturma hatası: ' . $e->getMessage(), 'error');
            redirect('/admin/users/create');
        }
    }
    
    /**
     * Kullanıcı Düzenleme Formu
     */
    public function editUser($id) {
        $user = $this->userModel->findWithRole($id);
        
        if (!$user) {
            setFlashMessage('Kullanıcı bulunamadı.', 'error');
            redirect('/admin/users');
        }
        
        $roles = $this->userModel->getAllRoles();
        $pages = $this->userModel->getAllPages();
        $rolePermissions = $this->userModel->getRolePermissions($user['role_id']);
        
        $this->view('admin/users/edit', [
            'title' => 'Kullanıcı Düzenle',
            'user' => $user,
            'roles' => $roles,
            'pages' => $pages,
            'rolePermissions' => $rolePermissions
        ]);
    }
    
    /**
     * Kullanıcı Güncelleme
     */
    public function updateUser($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/users');
        }
        
        if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
            setFlashMessage('Geçersiz form token.', 'error');
            redirect('/admin/users/' . $id . '/edit');
        }
        
        $user = $this->userModel->findById($id);
        if (!$user) {
            setFlashMessage('Kullanıcı bulunamadı.', 'error');
            redirect('/admin/users');
        }
        
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $fullName = trim($_POST['full_name'] ?? '');
        $roleId = $_POST['role_id'] ?? null;
        $password = $_POST['password'] ?? '';
        
        // Validasyon
        if (empty($username) || empty($email) || empty($roleId)) {
            setFlashMessage('Tüm zorunlu alanları doldurun.', 'error');
            redirect('/admin/users/' . $id . '/edit');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            setFlashMessage('Geçersiz e-posta adresi.', 'error');
            redirect('/admin/users/' . $id . '/edit');
        }
        
        if ($this->userModel->usernameExists($username, $id)) {
            setFlashMessage('Bu kullanıcı adı zaten kullanılıyor.', 'error');
            redirect('/admin/users/' . $id . '/edit');
        }
        
        if ($this->userModel->emailExists($email, $id)) {
            setFlashMessage('Bu e-posta adresi zaten kullanılıyor.', 'error');
            redirect('/admin/users/' . $id . '/edit');
        }
        
        // Güncelleme verileri
        $data = [
            'username' => $username,
            'email' => $email,
            'full_name' => $fullName,
            'role_id' => $roleId,
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'can_change_password' => isset($_POST['can_change_password']) ? 1 : 0
        ];
        
        // Etüt tipi - Vice Principal rol için
        $role = $this->userModel->getRoleById($roleId);
        if ($role && $role['role_name'] === 'vice_principal') {
            $etutType = trim($_POST['etut_type'] ?? '');
            if ($etutType && in_array($etutType, ['ortaokul', 'lise'])) {
                $data['etut_type'] = $etutType;
            }
        } else {
            // Diğer roller için etut_type'ı NULL yap
            $data['etut_type'] = null;
        }
        
        // Şifre değiştirilecekse
        if (!empty($password)) {
            if (strlen($password) < 6) {
                setFlashMessage('Şifre en az 6 karakter olmalıdır.', 'error');
                redirect('/admin/users/' . $id . '/edit');
            }
            $data['password_hash'] = password_hash($password, PASSWORD_BCRYPT);
        }
        
        if ($this->userModel->update($id, $data)) {
            // İzinleri güncelle
            $permissions = $_POST['permissions'] ?? [];
            $this->saveUserPermissions($roleId, $permissions);
            
            // logActivity('user_updated', 'users', $id, null, ['username' => $username]);
            setFlashMessage('Kullanıcı başarıyla güncellendi.', 'success');
        } else {
            setFlashMessage('Kullanıcı güncellenirken bir hata oluştu.', 'error');
        }
        
        redirect('/admin/users');
    }
    
    /**
     * Kullanıcı Silme
     */
    public function deleteUser($id = null) {
        header('Content-Type: application/json');
        
        try {
            error_log("=== DELETE USER START ===");
            error_log("Route parameter ID: $id");
            
            // API İçin Login Kontrol
            if (!isLoggedIn()) {
                error_log("Not logged in");
                echo json_encode(['success' => false, 'message' => 'Giriş yapmalısınız']);
                exit;
            }
            
            // Admin Kontrolü
            $user = currentUser();
            if ($user['role'] !== 'admin') {
                error_log("Not admin - role: " . $user['role']);
                echo json_encode(['success' => false, 'message' => 'Admin yetkiniz yok']);
                exit;
            }
            
            error_log("Session CSRF Token: " . (isset($_SESSION['csrf_token']) ? substr($_SESSION['csrf_token'], 0, 20) . '...' : 'NOT SET'));
            error_log("Session CSRF Token Time: " . ($_SESSION['csrf_token_time'] ?? 'NOT SET'));
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                error_log("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
                echo json_encode(['success' => false, 'message' => 'Geçersiz istek']);
                exit;
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            error_log("Delete User Input: " . json_encode($input));
            
            // ID'yi route parametresinden veya body'den al
            $id = $id ?? ($input['id'] ?? null);
            error_log("Using ID: $id");
            
            $csrfToken = $input['csrf_token'] ?? '';
            error_log("Received CSRF Token: " . (empty($csrfToken) ? 'EMPTY' : substr($csrfToken, 0, 20) . '...'));
            
            if (!$csrfToken) {
                error_log("CSRF Token is empty - this is the issue!");
                echo json_encode(['success' => false, 'message' => 'CSRF token bulunamadı - sayfayı yenileyin']);
                exit;
            }
            
            if (!validateCsrfToken($csrfToken)) {
                error_log("CSRF token validation failed");
                echo json_encode(['success' => false, 'message' => 'Geçersiz token - lütfen sayfayı yenileyin']);
                exit;
            }
            
            $user = $this->userModel->findById($id);
            error_log("User found: " . json_encode($user));
            
            if (!$user) {
                error_log("User not found: $id");
                echo json_encode(['success' => false, 'message' => 'Kullanıcı bulunamadı']);
                exit;
            }
            
            // Kendi hesabını silmeye çalışıyor mu?
            if ($id == getCurrentUserId()) {
                error_log("User trying to delete own account");
                echo json_encode(['success' => false, 'message' => 'Kendi hesabınızı silemezsiniz']);
                exit;
            }
            
            // Önce user'ın session kayıtlarını sil (FK constraint olmaması durumunda)
            error_log("Deleting user sessions for user_id: $id");
            $this->deleteUserSessions($id);
            
            error_log("Attempting to delete user: $id");
            $deleteResult = $this->userModel->delete($id);
            error_log("Delete result: " . var_export($deleteResult, true));
            
            if ($deleteResult) {
                // Log aktivitesi kaydı (hata olsa da devam et)
                try {
                    // logActivity('user_deleted', 'users', $id, ['username' => $user['username']], null);
                } catch (\Exception $logError) {
                    error_log("LogActivity Error: " . $logError->getMessage());
                }
                error_log("User deleted successfully: $id");
                echo json_encode(['success' => true, 'message' => 'Kullanıcı başarıyla silindi']);
            } else {
                error_log("Failed to delete user: $id");
                echo json_encode(['success' => false, 'message' => 'Kullanıcı silinemedi']);
            }
        } catch (\Exception $e) {
            error_log("DeleteUser Exception: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            echo json_encode(['success' => false, 'message' => 'Hata: ' . $e->getMessage()]);
        }
        
        error_log("=== DELETE USER END ===");
        exit;
    }
    
    /**
     * Kullanıcının session kayıtlarını sil
     * (vp_user_sessions tablosundan o user'a ait tüm session'ları sil)
     */
    private function deleteUserSessions($userId) {
        try {
            error_log("Calling userModel->deleteUserSessions for user_id: $userId");
            $result = $this->userModel->deleteUserSessions($userId);
            error_log("User sessions deleted for user_id $userId: " . var_export($result, true));
            return $result;
        } catch (\Exception $e) {
            error_log("Error deleting user sessions: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * FAZA 2 REFACTOR: Kullanıcı izinlerini kaydet (Basitleştirilmiş)
     * 
     * Artık:
     * - Filtreleme yapılmıyor (veritabanda yapılıyor)
     * - TÜM form girdileri kaydedilıyor
     * - Controller nur giriş/çıkış doğrulama yapıyor
     */
    private function saveUserPermissions($roleId, $permissions) {
        error_log("=== FAZA 2: saveUserPermissions (Simplified) ===");
        error_log("RoleID: $roleId");
        error_log("Permissions received: " . count($permissions) . " pages");
        
        // 1. Giriş Doğrulaması
        if (empty($roleId) || $roleId <= 0) {
            error_log("❌ Invalid roleId: $roleId");
            return false;
        }
        
        if (empty($permissions)) {
            error_log("⚠️  No permissions provided for roleId: $roleId");
            $permissions = [];
        }
        
        // 2. Rol Kontrol Et
        $role = $this->roleModel->getRoleById($roleId);
        if (!$role) {
            error_log("❌ Role not found: $roleId");
            return false;
        }
        
        error_log("✅ Role found: {$role['display_name']}");
        
        // 3. İzin Verisi Hazırla
        // FAZA 2 LOGIC: Tüm form girdilerini direkt olarak kaydet
        // Filtreleme veritabanında vp_role_page_permissions'da zaten yapılmış
        $permissionData = [];
        
        foreach ($permissions as $pageId => $perms) {
            // Çıkış Doğrulaması: page_id numeric olmalı
            if (!is_numeric($pageId) || $pageId <= 0) {
                error_log("⚠️  Skipping invalid pageId: $pageId");
                continue;
            }
            
            $permissionData[] = [
                'page_id' => (int)$pageId,
                'can_view' => isset($perms['can_view']) ? 1 : 0,
                'can_create' => isset($perms['can_create']) ? 1 : 0,
                'can_edit' => isset($perms['can_edit']) ? 1 : 0,
                'can_delete' => isset($perms['can_delete']) ? 1 : 0
            ];
        }
        
        error_log("Permission data prepared: " . count($permissionData) . " pages");
        
        // 4. Veritabanına Kaydet
        try {
            $result = $this->userModel->updateRolePermissions($roleId, $permissionData);
            
            if ($result) {
                error_log("✅ Permissions saved successfully for roleId: $roleId");
                return true;
            } else {
                error_log("❌ Failed to save permissions for roleId: $roleId");
                return false;
            }
        } catch (\Exception $e) {
            error_log("❌ Exception saving permissions: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Tüm öğrencileri sil
     */
    public function deleteAllStudents() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Geçersiz istek']);
            exit;
        }
        
        // JSON body'yi oku
        $input = json_decode(file_get_contents('php://input'), true);
        $csrfToken = $input['csrf_token'] ?? '';
        $confirmation = $input['confirmation'] ?? '';
        
        // CSRF kontrolü
        if (!validateCsrfToken($csrfToken)) {
            echo json_encode(['success' => false, 'message' => 'Geçersiz token']);
            exit;
        }
        
        // Onay kontrolü
        if (strtolower(trim($confirmation)) !== 'eminim') {
            echo json_encode(['success' => false, 'message' => 'Onay metni hatalı. "eminim" yazmalısınız.']);
            exit;
        }
        
        $count = $this->studentModel->countAll();
        
        if ($this->studentModel->deleteAll()) {
            // logActivity('all_students_deleted', 'students', null, ['count' => $count], null);
            echo json_encode(['success' => true, 'message' => "{$count} öğrenci başarıyla silindi."]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Silme işlemi başarısız.']);
        }
        
        exit;
    }
    
    /**
     * Rol İzinleri Sayfası
     */
    public function roles() {
        $roles = $this->roleModel->getAllRoles();
        $pages = $this->roleModel->getAllPages();
        
        $this->view('admin/roles/index', [
            'title' => 'Rol İzinleri',
            'roles' => $roles,
            'pages' => $pages
        ]);
    }
    
    /**
     * Rol izinlerini getir (AJAX)
     */
    public function getRolePermissions() {
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $roleId = $_GET['role_id'] ?? null;
            
            if (!$roleId) {
                echo json_encode(['success' => false, 'message' => 'Rol ID gerekli'], JSON_UNESCAPED_UNICODE);
                exit;
            }
            
            $role = $this->roleModel->getRoleById($roleId);
            if (!$role) {
                echo json_encode(['success' => false, 'message' => 'Rol bulunamadı'], JSON_UNESCAPED_UNICODE);
                exit;
            }
            
            $permissions = $this->roleModel->getPermissionsByRoleId($roleId);
            $pages = $this->roleModel->getAllPages();
            
            // Rol türüne göre sayfaları filtrele
            $pages = array_filter($pages, function($page) use ($role) {
                // Sadece aktif sayfaları göster
                if ((!isset($page['is_active']) || !$page['is_active'])) {
                    return false;
                }
                
                $etutType = $page['etut_type'] ?? 'all';
                
                // 'all' sayfaları herkese göster
                if ($etutType === 'all') {
                    return true;
                }
                
                // ortaokul ve lise sayfaları için - sadece sistem yöneticileri ve öğretmenlere göster
                if (in_array($role['role_name'], ['admin', 'teacher', 'secretary', 'principal'])) {
                    return true;
                }
                
                // Müdür yardımcısı için - tüm sayfaları göster
                if ($role['role_name'] === 'vice_principal') {
                    return true;
                }
                
                return false;
            });
            
            // Her sayfa için izin bilgisini hazırla
            $permissionData = [];
            foreach ($pages as $page) {
                $perm = null;
                foreach ($permissions as $p) {
                    if (isset($p['page_id']) && $p['page_id'] == $page['id']) {
                        $perm = $p;
                        break;
                    }
                }
                
                // Sayfa adını UTF-8 encode et
                $pageName = $page['page_name'] ?? '';
                // Eğer yanlış encode edilmişse düzelt
                if (!mb_check_encoding($pageName, 'UTF-8')) {
                    $pageName = mb_convert_encoding($pageName, 'UTF-8', 'ISO-8859-9,UTF-8');
                }
                
                $permissionData[] = [
                    'page_id' => $page['id'],
                    'page_name' => $pageName,
                    'page_key' => $page['page_key'] ?? '',
                    'page_url' => $page['page_url'] ?? '',
                    'can_view' => $perm ? ($perm['can_view'] ?? 0) : 0,
                    'can_create' => $perm ? ($perm['can_create'] ?? 0) : 0,
                    'can_edit' => $perm ? ($perm['can_edit'] ?? 0) : 0,
                    'can_delete' => $perm ? ($perm['can_delete'] ?? 0) : 0
                ];
            }
            
            echo json_encode([
                'success' => true,
                'permissions' => $permissionData,
                'role_name' => $role['display_name'] ?? $role['role_name'] ?? 'Bilinmeyen Rol'
            ], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            error_log("getRolePermissions error: " . $e->getMessage());
            echo json_encode([
                'success' => false, 
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
        exit;
    }
    
    /**
     * Rol izinlerini güncelle
     */
    public function updateRolePermissions() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Geçersiz istek']);
            exit;
        }
        
        // JSON veya POST data'yı al
        $input = json_decode(file_get_contents('php://input'), true);
        if ($input === null) {
            // JSON değilse POST data'yı kullan
            $input = $_POST;
        }
        
        if (!validateCsrfToken($input['csrf_token'] ?? '')) {
            echo json_encode(['success' => false, 'message' => 'Geçersiz CSRF token']);
            exit;
        }
        
        $roleId = $input['role_id'] ?? null;
        $permissions = $input['permissions'] ?? [];
        
        if (!$roleId) {
            echo json_encode(['success' => false, 'message' => 'Rol ID gerekli']);
            exit;
        }
        
        if (empty($permissions)) {
            echo json_encode(['success' => false, 'message' => 'İzin verisi bulunamadı']);
            exit;
        }
        
        // İzinleri formatla
        $permissionsArray = [];
        foreach ($permissions as $pageId => $perms) {
            $permissionsArray[$pageId] = [
                'can_view' => (isset($perms['view']) && $perms['view'] == '1') ? 1 : 0,
                'can_create' => (isset($perms['create']) && $perms['create'] == '1') ? 1 : 0,
                'can_edit' => (isset($perms['edit']) && $perms['edit'] == '1') ? 1 : 0,
                'can_delete' => (isset($perms['delete']) && $perms['delete'] == '1') ? 1 : 0
            ];
        }
        
        if ($this->roleModel->updateRolePermissions($roleId, $permissionsArray)) {
            // logActivity('role_permissions_updated', 'roles', $roleId, null, ['permissions_count' => count($permissionsArray)]);
            echo json_encode(['success' => true, 'message' => 'İzinler başarıyla güncellendi']);
        } else {
            echo json_encode(['success' => false, 'message' => 'İzinler güncellenirken bir hata oluştu']);
        }
        
        exit;
    }
    
    /**
     * Etüt Form Ayarları Sayfası
     */
    public function etutSettings()
    {
        $formType = $_GET['form_type'] ?? null;
        // Some routers pass as parameter
        if (!$formType && func_num_args() > 0) {
            $args = func_get_args();
            $formType = $args[0] ?? null;
        }

        $settingsModel = new EtutFormSettings();

        if ($formType && in_array($formType, ['ortaokul', 'lise'])) {
            $settings = [$formType => $settingsModel->getByFormType($formType)];
        } else {
            $settings = $settingsModel->getAllSettings();
        }

        $this->view('admin/etut-settings', [
            'title' => 'Etüt Form Ayarları',
            'settings' => $settings,
            'selected_form' => $formType
        ], 'main');
    }
    
    /**
     * Etüt Form Toggle
     */
    public function toggleEtutForm()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Geçersiz istek']);
            return;
        }
        
        $formType = $_POST['form_type'] ?? null;
        
        if (!in_array($formType, ['ortaokul', 'lise'])) {
            $this->json(['success' => false, 'message' => 'Geçersiz form tipi']);
            return;
        }
        
        $settingsModel = new EtutFormSettings();
        $result = $settingsModel->toggleFormStatus($formType);
        
        if ($result) {
            $this->json(['success' => true, 'message' => 'Form durumu güncellendi']);
        } else {
            $this->json(['success' => false, 'message' => 'Form durumu güncellenirken hata oluştu']);
        }
    }
    
    /**
     * Etüt Form Ayarlarını Güncelle
     */
    public function updateEtutFormSettings()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Geçersiz istek']);
            return;
        }
        
        $formType = $_POST['form_type'] ?? null;
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $closedMessage = trim($_POST['closed_message'] ?? '');
    // max_applications_per_student removed — unlimited applications allowed
        
        if (!in_array($formType, ['ortaokul', 'lise'])) {
            $this->json(['success' => false, 'message' => 'Geçersiz form tipi']);
            return;
        }
        
        if (empty($title)) {
            $this->json(['success' => false, 'message' => 'Form başlığı boş olamaz']);
            return;
        }
        
        $data = [
            'title' => $title,
            'description' => $description,
            'closed_message' => $closedMessage
        ];
        
        $settingsModel = new EtutFormSettings();
        $result = $settingsModel->updateSettings($formType, $data);
        
        if ($result) {
            $this->json(['success' => true, 'message' => 'Ayarlar başarıyla güncellendi']);
        } else {
            $this->json(['success' => false, 'message' => 'Ayarlar güncellenirken hata oluştu']);
        }
    }

    /**
     * Rol Oluşturma Sayfası
     */
    public function createRole() {
        $this->view('admin/roles/create', [
            'title' => 'Yeni Rol Oluştur'
        ]);
    }

    /**
     * Rol Kaydet
     */
    public function storeRole() {
        error_log("=== storeRole method called ===");
        error_log("REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD']);
        error_log("POST data: " . json_encode($_POST));
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            error_log("Not a POST request");
            redirect('/admin/roles');
        }

        if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
            error_log("CSRF token validation failed");
            setFlashMessage('Geçersiz form token.', 'error');
            redirect('/admin/roles/create');
        }

        $roleName = trim($_POST['role_name'] ?? '');
        $displayName = trim($_POST['display_name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        error_log("Role data - name: $roleName, displayName: $displayName");

        if (empty($roleName) || empty($displayName)) {
            error_log("Empty role name or display name");
            setFlashMessage('Rol adı ve gösterim adı gerekli.', 'error');
            redirect('/admin/roles/create');
        }

        try {
            $data = [
                'role_name' => $roleName,
                'display_name' => $displayName,
                'description' => $description,
                'is_active' => 1
            ];

            error_log("Calling roleModel->create with data: " . json_encode($data));
            $roleId = $this->roleModel->create($data);
            error_log("roleModel->create returned: " . var_export($roleId, true));
            
            if ($roleId) {
                // TODO: logActivity eklenecek - şimdilik devre dışı
                // // logActivity('role_created', 'roles', $roleId, null, ['role_name' => $roleName]);
                error_log("Role created successfully with ID: $roleId");
                setFlashMessage('Rol başarıyla oluşturuldu.', 'success');
                redirect('/admin/roles/' . $roleId . '/edit');
            } else {
                error_log("Role create returned false or 0. Data: " . json_encode($data));
                setFlashMessage('Rol oluşturma başarısız. Lütfen veritabanı bağlantısını kontrol edin.', 'error');
                redirect('/admin/roles/create');
            }
        } catch (\Exception $e) {
            error_log("Role creation exception: " . $e->getMessage() . " | Trace: " . $e->getTraceAsString());
            setFlashMessage('Hata: ' . $e->getMessage(), 'error');
            redirect('/admin/roles/create');
        }
    }

    /**
     * Rol Düzenleme Sayfası
     * FAZA 2 REFACTOR: Erişilebilir sayfaları veritabanından oku
     */
    public function editRole($id) {
        $role = $this->roleModel->getRoleById($id);
        
        if (!$role) {
            setFlashMessage('Rol bulunamadı.', 'error');
            redirect('/admin/roles');
        }

        // FAZA 2: Veritabanında tanımlı erişilebilir sayfaları kullan
        $permissions = $this->roleModel->getPermissionsByRoleId($id);
        $pages = $this->roleModel->getRoleAccessiblePages($id);
        
        // Artık filtreleme yapmıyoruz - veritabanda zaten yapılmış
        // $pages veya permissioni tekrar filtrelemek gerekirse:
        // vp_role_page_permissions tablosundaki veri güvenilir

        $this->view('admin/roles/edit', [
            'title' => 'Rol Düzenle: ' . $role['display_name'],
            'role' => $role,
            'permissions' => $permissions,
            'pages' => $pages
        ]);
    }

    /**
     * Rol Güncelle
     */
    public function updateRole($id) {
        error_log("=== updateRole() START: id=$id ===");
        error_log("REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD']);
        error_log("POST data keys: " . implode(", ", array_keys($_POST)));
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            error_log("REQUEST_METHOD is not POST, redirecting");
            redirect('/admin/roles');
        }

        if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
            error_log("CSRF token validation failed");
            setFlashMessage('Geçersiz form token.', 'error');
            redirect('/admin/roles/' . $id . '/edit');
        }

        $role = $this->roleModel->getRoleById($id);
        if (!$role) {
            error_log("Role not found: $id");
            setFlashMessage('Rol bulunamadı.', 'error');
            redirect('/admin/roles');
        }

        $displayName = trim($_POST['display_name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if (empty($displayName)) {
            error_log("Display name is empty");
            setFlashMessage('Gösterim adı gerekli.', 'error');
            redirect('/admin/roles/' . $id . '/edit');
        }

        try {
            $data = [
                'display_name' => $displayName,
                'description' => $description
            ];

            if ($this->roleModel->update($id, $data)) {
                // İzinleri güncelle
                $permissions = $_POST['permissions'] ?? [];
                error_log("=== DEBUG updateRole: roleId=$id ===");
                error_log("DEBUG updateRole: permissions count=" . count($permissions));
                error_log("DEBUG updateRole: permissions array keys: " . implode(", ", array_keys($permissions)));
                error_log("DEBUG updateRole: permissions data=" . json_encode($permissions));
                
                $saveResult = $this->saveUserPermissions($id, $permissions);
                error_log("DEBUG updateRole: saveUserPermissions result=" . ($saveResult ? 'true' : 'false'));

                // TODO: logActivity eklenecek
                // // logActivity('role_updated', 'roles', $id, null, ['display_name' => $displayName]);
                setFlashMessage('Rol başarıyla güncellendi.', 'success');
            } else {
                setFlashMessage('Rol güncellenirken hata oluştu.', 'error');
            }
        } catch (\Exception $e) {
            error_log("Role update error: " . $e->getMessage());
            setFlashMessage('Hata: ' . $e->getMessage(), 'error');
        }

        redirect('/admin/roles/' . $id . '/edit');
    }

    /**
     * Rol Sil
     */
    public function deleteRole($id) {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Geçersiz istek']);
            exit;
        }

        if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
            echo json_encode(['success' => false, 'message' => 'Geçersiz token']);
            exit;
        }

        try {
            $role = $this->roleModel->getRoleById($id);
            if (!$role) {
                echo json_encode(['success' => false, 'message' => 'Rol bulunamadı']);
                exit;
            }

            // Default roller silinemesin
            if (in_array($role['role_name'], ['admin', 'teacher', 'student'])) {
                echo json_encode(['success' => false, 'message' => 'Sistem rolleri silinemez']);
                exit;
            }

            if ($this->roleModel->delete($id)) {
                // TODO: logActivity eklenecek
                // // logActivity('role_deleted', 'roles', $id, null, ['role_name' => $role['role_name']]);
                echo json_encode(['success' => true, 'message' => 'Rol silindi']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Rol silinemedi']);
            }
        } catch (\Exception $e) {
            error_log("Role delete error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Hata: ' . $e->getMessage()]);
        }
    }
}
