<?php
/**
 * CSRF Koruması
 * Vildan Portal - Okul Yönetim Sistemi
 */

namespace Core;

use Core\Session;

class CSRF
{
    private static $instance = null;
    private $session;
    
    private function __construct() {
        $this->session = Session::getInstance();
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * CSRF token oluştur
     * 
     * @return string
     */
    public function generateToken() {
        $token = bin2hex(random_bytes(32));
        
        $this->session->set(CSRF_TOKEN_NAME, $token);
        $this->session->set(CSRF_TOKEN_NAME . '_time', time());
        
        return $token;
    }
    
    /**
     * CSRF token'ı al (yoksa oluştur)
     * 
     * @return string
     */
    public function getToken() {
        if (!$this->session->has(CSRF_TOKEN_NAME)) {
            return $this->generateToken();
        }
        
        // Token süresi dolmuş mu kontrol et
        $tokenTime = $this->session->get(CSRF_TOKEN_NAME . '_time', 0);
        if (time() - $tokenTime > CSRF_TOKEN_LIFETIME) {
            return $this->generateToken();
        }
        
        return $this->session->get(CSRF_TOKEN_NAME);
    }
    
    /**
     * CSRF token'ı doğrula
     * 
     * @param string $token
     * @return bool
     */
    public function validateToken($token) {
        if (empty($token)) {
            return false;
        }
        
        $sessionToken = $this->session->get(CSRF_TOKEN_NAME);
        
        if (empty($sessionToken)) {
            return false;
        }
        
        // Token süresi dolmuş mu?
        $tokenTime = $this->session->get(CSRF_TOKEN_NAME . '_time', 0);
        if (time() - $tokenTime > CSRF_TOKEN_LIFETIME) {
            return false;
        }
        
        // Timing attack koruması için hash_equals kullan
        return hash_equals($sessionToken, $token);
    }
    
    /**
     * CSRF token input field oluştur (HTML)
     * 
     * @return string
     */
    public function field() {
        $token = $this->getToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }
    
    /**
     * CSRF token meta tag oluştur (AJAX için)
     * 
     * @return string
     */
    public function metaTag() {
        $token = $this->getToken();
        return '<meta name="csrf-token" content="' . htmlspecialchars($token) . '">';
    }
    
    /**
     * Request'ten CSRF token'ı kontrol et
     * 
     * @return bool
     */
    public function checkRequest() {
        // GET request için kontrol yapma
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return true;
        }
        
        // Token'ı al (POST, header veya meta tag'den)
        $token = $_POST['csrf_token'] ?? 
                 $_SERVER['HTTP_X_CSRF_TOKEN'] ?? 
                 $_SERVER['HTTP_X_XSRF_TOKEN'] ?? 
                 null;
        
        return $this->validateToken($token);
    }
    
    /**
     * CSRF middleware
     * 
     * @throws \Exception
     */
    public function verify() {
        if (!$this->checkRequest()) {
            http_response_code(419);
            
            if (APP_DEBUG) {
                throw new \Exception('CSRF token doğrulaması başarısız.');
            } else {
                die('Geçersiz istek. Lütfen sayfayı yenileyip tekrar deneyin.');
            }
        }
    }
}