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
            logActivity('password_changed', 'users', $userId, null, null);
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
            logActivity('user_created', 'users', $userId, null, ['username' => $username, 'role' => $role]);
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
        
        $userId = $this->userModel->create($data);
        
        if ($userId) {
            // İzinleri kaydet
            $permissions = $_POST['permissions'] ?? [];
            $this->saveUserPermissions($roleId, $permissions);
            
            logActivity('user_created', 'users', $userId, null, ['username' => $username]);
            setFlashMessage('Kullanıcı başarıyla oluşturuldu.', 'success');
            redirect('/admin/users');
        } else {
            setFlashMessage('Kullanıcı oluşturma başarısız.', 'error');
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
            
            logActivity('user_updated', 'users', $id, null, ['username' => $username]);
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
            
            error_log("Attempting to delete user: $id");
            $deleteResult = $this->userModel->delete($id);
            error_log("Delete result: " . var_export($deleteResult, true));
            
            if ($deleteResult) {
                // Log aktivitesi kaydı (hata olsa da devam et)
                try {
                    logActivity('user_deleted', 'users', $id, ['username' => $user['username']], null);
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
     * Kullanıcı izinlerini kaydet
     */
    private function saveUserPermissions($roleId, $permissions) {
        $permissionData = [];
        
        foreach ($permissions as $pageId => $perms) {
            $permissionData[] = [
                'page_id' => $pageId,
                'can_view' => isset($perms['view']) ? 1 : 0,
                'can_create' => isset($perms['create']) ? 1 : 0,
                'can_edit' => isset($perms['edit']) ? 1 : 0,
                'can_delete' => isset($perms['delete']) ? 1 : 0
            ];
        }
        
        return $this->userModel->updateRolePermissions($roleId, $permissionData);
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
            logActivity('all_students_deleted', 'students', null, ['count' => $count], null);
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
        header('Content-Type: application/json');
        
        try {
            $roleId = $_GET['role_id'] ?? null;
            
            if (!$roleId) {
                echo json_encode(['success' => false, 'message' => 'Rol ID gerekli']);
                exit;
            }
            
            $role = $this->roleModel->getRoleById($roleId);
            if (!$role) {
                echo json_encode(['success' => false, 'message' => 'Rol bulunamadı']);
                exit;
            }
            
            $permissions = $this->roleModel->getPermissionsByRoleId($roleId);
            $pages = $this->roleModel->getAllPages();
            
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
                
                $permissionData[] = [
                    'page_id' => $page['id'],
                    'page_name' => $page['page_name'] ?? '',
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
            ]);
        } catch (\Exception $e) {
            error_log("getRolePermissions error: " . $e->getMessage());
            echo json_encode([
                'success' => false, 
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ]);
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
            logActivity('role_permissions_updated', 'roles', $roleId, null, ['permissions_count' => count($permissionsArray)]);
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
}
