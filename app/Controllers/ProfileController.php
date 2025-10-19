<?php
/**
 * Profile Controller
 * Kullanıcı profil yönetimi (şifre değişikliği vb.)
 */

namespace App\Controllers;

use Core\Controller;
use App\Models\User;

class ProfileController extends Controller
{
    private $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();

        if (!isLoggedIn()) {
            redirect('/login');
        }
    }

    /**
     * Profil ana sayfası
     */
    public function index()
    {
        $user = currentUser();

        $this->view('profile/index', [
            'title' => 'Profilim',
            'user' => $user,
            'canChangePassword' => canChangePassword(),
            'canChangeUsername' => canChangeUsername()
        ]);
    }

    /**
     * Şifre değiştirme işlemi
     */
    public function changePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/profile');
        }
        
        // Şifre değiştirme izni kontrolü
        if (!canChangePassword()) {
            setFlashMessage('Şifrenizi değiştirme yetkiniz bulunmamaktadır.', 'error');
            redirect('/profile');
        }

        if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
            setFlashMessage('Geçersiz form token.', 'error');
            redirect('/profile');
        }

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            setFlashMessage('Lütfen tüm alanları doldurun.', 'error');
            redirect('/profile');
        }

        if ($newPassword !== $confirmPassword) {
            setFlashMessage('Yeni şifreler eşleşmiyor.', 'error');
            redirect('/profile');
        }

        if (strlen($newPassword) < 6) {
            setFlashMessage('Şifre en az 6 karakter olmalıdır.', 'error');
            redirect('/profile');
        }

        $userId = getCurrentUserId();
        $user = $this->userModel->findById($userId);

        if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
            setFlashMessage('Mevcut şifre yanlış.', 'error');
            redirect('/profile');
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        if ($this->userModel->update($userId, ['password_hash' => $hashedPassword])) {
            logActivity('password_changed', 'users', $userId, null, null);
            setFlashMessage('Şifreniz başarıyla güncellendi.', 'success');
        } else {
            setFlashMessage('Şifre değiştirilemedi. Lütfen tekrar deneyin.', 'error');
        }

        redirect('/profile');
    }
}
