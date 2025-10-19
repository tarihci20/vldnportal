<?php
namespace Core;
/**
 * Session Sınıfı
 * Vildan Portal - Okul Yönetim Sistemi
 */
class Session
{
    private static $instance = null;
    
    private function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            $this->start();
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Session'ı başlat
     */
    public function start() {
        // Session ayarları
        ini_set('session.cookie_httponly', SESSION_COOKIE_HTTPONLY ? 1 : 0);
        ini_set('session.cookie_secure', SESSION_COOKIE_SECURE ? 1 : 0);
        ini_set('session.cookie_samesite', SESSION_COOKIE_SAMESITE);
        ini_set('session.use_strict_mode', 1);
        ini_set('session.gc_maxlifetime', SESSION_LIFETIME);
        
        session_name(SESSION_NAME);
        session_start();
        
        // Session fixation koruması
        if (!$this->has('_initiated')) {
            $this->regenerate();
            $this->set('_initiated', true);
        }
        
        // Session timeout kontrolü
        if ($this->has('_last_activity')) {
            $elapsed = time() - $this->get('_last_activity');
            if ($elapsed > SESSION_LIFETIME) {
                $this->destroy();
                return;
            }
        }
        
        $this->set('_last_activity', time());
    }
    
    /**
     * Session değeri ayarla
     * 
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }
    
    /**
     * Session değeri al
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }
    
    /**
     * Session değeri var mı?
     * 
     * @param string $key
     * @return bool
     */
    public function has($key) {
        return isset($_SESSION[$key]);
    }
    
    /**
     * Session değerini sil
     * 
     * @param string $key
     */
    public function remove($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    
    /**
     * Session'ı yenile (session fixation koruması)
     */
    public function regenerate() {
        session_regenerate_id(true);
        $this->set('_initiated', true);
    }
    
    /**
     * Session'ı tamamen temizle ve yok et
     */
    public function destroy() {
        $_SESSION = [];
        
        // Session cookie'yi sil
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        
        session_destroy();
    }
    
    /**
     * Flash mesaj ayarla
     * 
     * @param string $key
     * @param mixed $value
     */
    public function flash($key, $value) {
        if (!isset($_SESSION['_flash'])) {
            $_SESSION['_flash'] = [];
        }
        $_SESSION['_flash'][$key] = $value;
    }
    
    /**
     * Flash mesajı al ve sil
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getFlash($key, $default = null) {
        $value = $_SESSION['_flash'][$key] ?? $default;
        
        if (isset($_SESSION['_flash'][$key])) {
            unset($_SESSION['_flash'][$key]);
        }
        
        return $value;
    }
    
    /**
     * Flash mesaj var mı?
     * 
     * @param string $key
     * @return bool
     */
    public function hasFlash($key) {
        return isset($_SESSION['_flash'][$key]);
    }
    
    /**
     * Tüm flash mesajları al
     * 
     * @return array
     */
    public function getAllFlash() {
        $flash = $_SESSION['_flash'] ?? [];
        $_SESSION['_flash'] = [];
        return $flash;
    }
    
    /**
     * Tüm session verilerini al
     * 
     * @return array
     */
    public function all() {
        return $_SESSION;
    }
    
    /**
     * Session ID'yi al
     * 
     * @return string
     */
    public function id() {
        return session_id();
    }
}