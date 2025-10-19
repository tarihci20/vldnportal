<?php
/**
 * User Controller - Admin Kullanıcı Yönetimi
 * Vildan Portal - Okul Yönetim Sistemi
 */

namespace App\Controllers;

use Core\Controller;
use App\Models\User;

class UserController extends Controller
{
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
        
        // Kullanıcı giriş kontrolü
        if (!isLoggedIn()) {
            redirect('/login');
        }
        
        // Admin kontrolü - Sadece adminler kullanıcı yönetebilir
        $user = currentUser();
        if ($user['role'] !== 'admin') {
            setFlashMessage('Bu sayfaya erişim yetkiniz yok.', 'error');
            redirect('/dashboard');
        }
    }
    
    /**
     * Kullanıcı listesi
     */
    public function index() {
        $page = $_GET['page'] ?? 1;
        $result = $this->userModel->getAllWithRoles($page);
        
        $this->view('admin/users/index', [
            'title' => 'Kullanıcı Yönetimi',
            'users' => $result['data'],
            'pagination' => $result['pagination']
        ]);
    }
    
    /**
     * Yeni kullanıcı ekleme formu
     */
    public function create() {
        $roles = $this->userModel->getAllRoles();
        $pages = $this->userModel->getAllPages();
        
        $this->view('admin/users/create', [
            'title' => 'Yeni Kullanıcı Ekle',
            'roles' => $roles,
            'pages' => $pages
        ]);
    }
    
    /**
     * Kullanıcı kaydetme
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/users');
        }
        
        // CSRF kontrolü
        if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
            setFlashMessage('Geçersiz form token.', 'error');
            redirect('/admin/users/create');
        }
        
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $fullName = trim($_POST['full_name'] ?? '');
        $roleId = $_POST['role_id'] ?? '';
        $phone = trim($_POST['phone'] ?? '');
        $canChangePassword = isset($_POST['can_change_password']) ? 1 : 0;
        
        // Validasyon
        if (empty($username) || empty($email) || empty($password) || empty($fullName) || empty($roleId)) {
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
        
        // Kullanıcı adı kontrolü
        if ($this->userModel->usernameExists($username)) {
            setFlashMessage('Bu kullanıcı adı zaten kullanılıyor.', 'error');
            redirect('/admin/users/create');
        }
        
        // E-posta kontrolü
        if ($this->userModel->emailExists($email)) {
            setFlashMessage('Bu e-posta adresi zaten kullanılıyor.', 'error');
            redirect('/admin/users/create');
        }
        
        // Kullanıcı oluştur
        $data = [
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'full_name' => $fullName,
            'role_id' => $roleId,
            'phone' => $phone,
            'can_change_password' => $canChangePassword,
            'is_active' => 1
        ];
        
        $userId = $this->userModel->create($data);
        
        if ($userId) {
            logActivity('user_created', 'users', $userId, null, ['username' => $username]);
            setFlashMessage('Kullanıcı başarıyla oluşturuldu.', 'success');
            redirect('/admin/users');
        } else {
            setFlashMessage('Kullanıcı oluşturma başarısız.', 'error');
            redirect('/admin/users/create');
        }
    }
    
    /**
     * Kullanıcı düzenleme formu
     */
    public function edit($id) {
        $user = $this->userModel->findWithRole($id);
        
        if (!$user) {
            setFlashMessage('Kullanıcı bulunamadı.', 'error');
            redirect('/admin/users');
        }
        
        $roles = $this->userModel->getAllRoles();
        $pages = $this->userModel->getAllPages();
        $permissions = $this->userModel->getRolePermissions($user['role_id']);
        
        $this->view('admin/users/edit', [
            'title' => 'Kullanıcı Düzenle',
            'user' => $user,
            'roles' => $roles,
            'pages' => $pages,
            'permissions' => $permissions
        ]);
    }
    
    /**
     * Kullanıcı güncelleme
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/users');
        }
        
        // CSRF kontrolü
        if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
            setFlashMessage('Geçersiz form token.', 'error');
            redirect("/admin/users/{$id}/edit");
        }
        
        $user = $this->userModel->findById($id);
        
        if (!$user) {
            setFlashMessage('Kullanıcı bulunamadı.', 'error');
            redirect('/admin/users');
        }
        
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $fullName = trim($_POST['full_name'] ?? '');
        $roleId = $_POST['role_id'] ?? '';
        $phone = trim($_POST['phone'] ?? '');
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $canChangePassword = isset($_POST['can_change_password']) ? 1 : 0;
        
        // Validasyon
        if (empty($username) || empty($email) || empty($fullName) || empty($roleId)) {
            setFlashMessage('Tüm zorunlu alanları doldurun.', 'error');
            redirect("/admin/users/{$id}/edit");
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            setFlashMessage('Geçersiz e-posta adresi.', 'error');
            redirect("/admin/users/{$id}/edit");
        }
        
        // Kullanıcı adı kontrolü
        if ($this->userModel->usernameExists($username, $id)) {
            setFlashMessage('Bu kullanıcı adı zaten kullanılıyor.', 'error');
            redirect("/admin/users/{$id}/edit");
        }
        
        // E-posta kontrolü
        if ($this->userModel->emailExists($email, $id)) {
            setFlashMessage('Bu e-posta adresi zaten kullanılıyor.', 'error');
            redirect("/admin/users/{$id}/edit");
        }
        
        // Güncelleme verisi
        $data = [
            'username' => $username,
            'email' => $email,
            'full_name' => $fullName,
            'role_id' => $roleId,
            'phone' => $phone,
            'is_active' => $isActive,
            'can_change_password' => $canChangePassword
        ];
        
        // Şifre değişikliği varsa
        $password = $_POST['password'] ?? '';
        if (!empty($password)) {
            if (strlen($password) < 6) {
                setFlashMessage('Şifre en az 6 karakter olmalıdır.', 'error');
                redirect("/admin/users/{$id}/edit");
            }
            $data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }
        
        if ($this->userModel->update($id, $data)) {
            logActivity('user_updated', 'users', $id, null, ['username' => $username]);
            setFlashMessage('Kullanıcı başarıyla güncellendi.', 'success');
        } else {
            setFlashMessage('Kullanıcı güncelleme başarısız.', 'error');
        }
        
        redirect('/admin/users');
    }
    
    /**
     * Kullanıcı silme
     */
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/users');
        }
        
        // CSRF kontrolü
        if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
            setFlashMessage('Geçersiz form token.', 'error');
            redirect('/admin/users');
        }
        
        $user = $this->userModel->findById($id);
        
        if (!$user) {
            setFlashMessage('Kullanıcı bulunamadı.', 'error');
            redirect('/admin/users');
        }
        
        // Kendi hesabını silemez
        if ($id == getCurrentUserId()) {
            setFlashMessage('Kendi hesabınızı silemezsiniz.', 'error');
            redirect('/admin/users');
        }
        
        if ($this->userModel->delete($id)) {
            logActivity('user_deleted', 'users', $id, ['username' => $user['username']], null);
            setFlashMessage('Kullanıcı başarıyla silindi.', 'success');
        } else {
            setFlashMessage('Kullanıcı silme başarısız.', 'error');
        }
        
        redirect('/admin/users');
    }
    
    /**
     * Rol izinlerini getir (AJAX)
     */
    public function getRolePermissions() {
        header('Content-Type: application/json');
        
        $roleId = $_GET['role_id'] ?? '';
        
        if (empty($roleId)) {
            echo json_encode(['success' => false, 'message' => 'Rol ID gerekli']);
            exit;
        }
        
        $permissions = $this->userModel->getRolePermissions($roleId);
        
        echo json_encode(['success' => true, 'permissions' => $permissions]);
        exit;
    }
    
    /**
     * Rol izinlerini güncelle
     */
    public function updatePermissions($roleId) {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Geçersiz istek']);
            exit;
        }
        
        // JSON body'yi oku
        $input = json_decode(file_get_contents('php://input'), true);
        $csrfToken = $input['csrf_token'] ?? '';
        $permissions = $input['permissions'] ?? [];
        
        // CSRF kontrolü
        if (!validateCsrfToken($csrfToken)) {
            echo json_encode(['success' => false, 'message' => 'Geçersiz token']);
            exit;
        }
        
        if ($this->userModel->updateRolePermissions($roleId, $permissions)) {
            logActivity('role_permissions_updated', 'roles', $roleId, null, null);
            echo json_encode(['success' => true, 'message' => 'İzinler güncellendi']);
        } else {
            echo json_encode(['success' => false, 'message' => 'İzinler güncellenemedi']);
        }
        
        exit;
    }
}
