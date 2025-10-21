<?php
/**
 * Authentication Controller
 * Vildan Portal - Okul Yönetim Sistemi
 */

namespace App\Controllers;

use Core\Controller;
use Core\Auth;

class AuthController extends Controller
{
    private Auth $auth;

    public function __construct()
    {
        parent::__construct();
        $this->auth = Auth::getInstance();
    }

    /**
     * Login sayfası göster
     */
    public function loginPage()
    {
        // Zaten giriş yapmışsa dashboard'a yönlendir
        if ($this->auth->check()) {
            redirect('/dashboard');
        }

        // Sadece login view'ı, layout olmadan göster
        $this->view('auth/login', [
            'title' => 'Giriş Yap',
            'pageTitle' => 'Vildan Portal - Giriş'
        ], null);
    }

    /**
     * Login işlemi
     */
    public function login()
    {
        // CSRF kontrolü
        if (!$this->validateCSRF()) {
            setFlashMessage('error', 'Güvenlik hatası! Lütfen sayfayı yenileyip tekrar deneyin.');
            redirect('/login');
        }

        // Form verilerini al
        $email = trim($_POST['email'] ?? $_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);

        // Validasyon
        if (empty($email) || empty($password)) {
            setFlashMessage('error', 'Kullanıcı adı ve şifre gereklidir!');
            redirect('/login');
        }

        // Login deneme
        try {
            // Auth::login() array döndürür: ['success' => bool, 'message' => string]
            $result = $this->auth->login($email, $password, $remember);

            if ($result['success']) {
                // Başarılı login - mesaj göstermeye gerek yok
                redirect('/dashboard');
            } else {
                $message = $result['message'] ?? 'Kullanıcı adı veya şifre hatalı!';
                setFlashMessage('error', $message);
                redirect('/login');
            }
        } catch (\Exception $e) {
            setFlashMessage('error', 'Giriş yapılırken bir hata oluştu. Lütfen tekrar deneyin.');
            redirect('/login');
        }
    }

    /**
     * Şifremi unuttum sayfası
     */
    public function forgotPasswordPage()
    {
        $this->view('auth/forgot-password', [
            'title' => 'Şifremi Unuttum',
            'pageTitle' => 'Şifre Sıfırlama'
        ]);
    }

    /**
     * Şifre sıfırlama isteği
     */
    public function forgotPassword()
    {
        if (!$this->validateCSRF()) {
            setFlashMessage('error', 'Geçersiz form gönderimi!');
            redirect('/forgot-password');
        }

        $email = trim($_POST['email'] ?? '');

        if (empty($email)) {
            setFlashMessage('error', 'Email adresi gereklidir!');
            redirect('/forgot-password');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            setFlashMessage('error', 'Geçerli bir email adresi giriniz!');
            redirect('/forgot-password');
        }

        try {
            $result = $this->auth->sendPasswordResetLink($email);
            
            if ($result) {
                setFlashMessage('success', 'Şifre sıfırlama bağlantısı email adresinize gönderildi.');
            } else {
                setFlashMessage('info', 'Email adresinize şifre sıfırlama talimatları gönderildi.');
            }
            
            redirect('/login');
        } catch (\Exception $e) {
            setFlashMessage('error', 'Bir hata oluştu: ' . $e->getMessage());
            redirect('/forgot-password');
        }
    }

    /**
     * Şifre sıfırlama sayfası
     */
    public function resetPasswordPage($token)
    {
        // Token geçerli mi kontrol et
        $valid = $this->auth->validateResetToken($token);

        if (!$valid) {
            setFlashMessage('error', 'Geçersiz veya süresi dolmuş token!');
            redirect('/forgot-password');
        }

        $this->view('auth/reset-password', [
            'title' => 'Şifre Sıfırla',
            'token' => $token
        ]);
    }

    /**
     * Şifre sıfırlama işlemi
     */
    public function resetPassword()
    {
        if (!$this->validateCSRF()) {
            setFlashMessage('error', 'Geçersiz form gönderimi!');
            redirect('/forgot-password');
        }

        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        // Validasyon
        if (empty($token) || empty($password) || empty($passwordConfirm)) {
            setFlashMessage('error', 'Tüm alanları doldurunuz!');
            redirect('/reset-password/' . $token);
        }

        if ($password !== $passwordConfirm) {
            setFlashMessage('error', 'Şifreler eşleşmiyor!');
            redirect('/reset-password/' . $token);
        }

        if (strlen($password) < 8) {
            setFlashMessage('error', 'Şifre en az 8 karakter olmalıdır!');
            redirect('/reset-password/' . $token);
        }

        try {
            $result = $this->auth->resetPassword($token, $password);

            if ($result) {
                setFlashMessage('success', 'Şifreniz başarıyla değiştirildi. Giriş yapabilirsiniz.');
                redirect('/login');
            } else {
                setFlashMessage('error', 'Şifre sıfırlama başarısız!');
                redirect('/reset-password/' . $token);
            }
        } catch (\Exception $e) {
            setFlashMessage('error', 'Bir hata oluştu: ' . $e->getMessage());
            redirect('/reset-password/' . $token);
        }
    }

    /**
     * Google OAuth yönlendirme
     */
    public function googleRedirect()
    {
        try {
            $authUrl = $this->auth->getGoogleAuthUrl();
            redirect($authUrl, true);
        } catch (\Exception $e) {
            setFlashMessage('error', 'Google ile giriş yapılamadı: ' . $e->getMessage());
            redirect('/login');
        }
    }

    /**
     * Google OAuth callback
     */
    public function googleCallback()
    {
        $code = $_GET['code'] ?? '';

        if (empty($code)) {
            setFlashMessage('error', 'Google yetkilendirme başarısız!');
            redirect('/login');
        }

        try {
            $result = $this->auth->handleGoogleCallback($code);

            if ($result) {
                setFlashMessage('success', 'Google ile giriş başarılı!');
                redirect('/dashboard');
            } else {
                setFlashMessage('error', 'Google ile giriş yapılamadı!');
                redirect('/login');
            }
        } catch (\Exception $e) {
            setFlashMessage('error', 'Bir hata oluştu: ' . $e->getMessage());
            redirect('/login');
        }
    }

    /**
     * Logout
     */
    public function logout()
    {
        $this->auth->logout();
        redirect('/login');
    }
}