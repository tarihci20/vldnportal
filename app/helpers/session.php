<?php
/**
 * Vildan Portal - Session Helper Functions
 * Path: /home/vildacgg/vldn.in/portalv2/app/helpers/session.php
 * * Session yönetimi ve Yetkilendirme ile ilgili fonksiyonlar
 * (TÜM FONKSİYONLAR ÇAKIŞMA HATASINI ÖNLEMEK İÇİN if (!function_exists()) İLE KORUNDU)
 */

/**
 * Session başlat
 * * @return void
 */
if (!function_exists('startSession')) {
    function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            // Sabitlerin tanımlı olup olmadığını kontrol et
            $lifetime = defined('SESSION_LIFETIME') ? SESSION_LIFETIME : 7200;
            $basePath = defined('BASE_PATH') ? BASE_PATH : '/';
            $sessionName = defined('SESSION_NAME') ? SESSION_NAME : 'app_session';

            session_set_cookie_params([
                'lifetime' => $lifetime,
                'path' => $basePath,
                'domain' => '',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
            
            session_name($sessionName);
            session_start();
        }
    }
}

/**
 * Session değeri set et
 * * @param string $key Anahtar
 * @param mixed $value Değer
 * @return void
 */
if (!function_exists('setSession')) {
    function setSession($key, $value) {
        $_SESSION[$key] = $value;
    }
}

/**
 * Session değeri al
 * * @param string $key Anahtar
 * @param mixed $default Varsayılan değer
 * @return mixed Değer
 */
if (!function_exists('getSession')) {
    function getSession($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }
}

/**
 * Session değeri var mı kontrol et
 * * @param string $key Anahtar
 * @return bool Var mı?
 */
if (!function_exists('hasSession')) {
    function hasSession($key) {
        return isset($_SESSION[$key]);
    }
}

/**
 * Session değeri sil
 * * @param string $key Anahtar
 * @return void
 */
if (!function_exists('unsetSession')) {
    function unsetSession($key) {
        unset($_SESSION[$key]);
    }
}

/**
 * Tüm session'ı temizle
 * * @return void
 */
if (!function_exists('clearSession')) {
    function clearSession() {
        $_SESSION = [];
    }
}

/**
 * Session'ı yok et
 * * @return void
 */
if (!function_exists('destroySession')) {
    function destroySession() {
        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
    }
}

/**
 * Flash message set et (bir sonraki request için)
 * * @param string $key Anahtar
 * @param mixed $value Değer
 * @return void
 */
if (!function_exists('flash')) {
    function flash($key, $value) {
        $_SESSION['flash'][$key] = $value;
    }
}

/**
 * Flash message al ve sil
 * * @param string $key Anahtar
 * @param mixed $default Varsayılan değer
 * @return mixed Değer
 */
if (!function_exists('getFlash')) {
    function getFlash($key, $default = null) {
        $value = $_SESSION['flash'][$key] ?? $default;
        unset($_SESSION['flash'][$key]);
        return $value;
    }
}

/**
 * Flash message var mı kontrol et
 * * @param string $key Anahtar
 * @return bool Var mı?
 */
if (!function_exists('hasFlash')) {
    function hasFlash($key) {
        return isset($_SESSION['flash'][$key]);
    }
}

/**
 * Success flash message
 * * @param string $message Mesaj
 * @return void
 */
if (!function_exists('flashSuccess')) {
    function flashSuccess($message) {
        flash('success', $message);
    }
}

/**
 * Error flash message
 * * @param string $message Mesaj
 * @return void
 */
if (!function_exists('flashError')) {
    function flashError($message) {
        flash('error', $message);
    }
}

/**
 * Warning flash message
 * * @param string $message Mesaj
 * @return void
 */
if (!function_exists('flashWarning')) {
    function flashWarning($message) {
        flash('warning', $message);
    }
}

/**
 * Info flash message
 * * @param string $message Mesaj
 * @return void
 */
if (!function_exists('flashInfo')) {
    function flashInfo($message) {
        flash('info', $message);
    }
}

/**
 * Flash message al (tüm tipleri kontrol eder - view için)
 * GÜNCELLEME: Artık flash_message/flash_type session key'lerini kullanıyor
 * * @return array|null ['type' => 'success|error|warning|info', 'message' => '...']
 */
if (!function_exists('getFlashMessage')) {
    function getFlashMessage() {
        if (isset($_SESSION['flash_message']) && !empty($_SESSION['flash_message'])) {
            $flash = [
                'message' => $_SESSION['flash_message'],
                'type' => $_SESSION['flash_type'] ?? 'info'
            ];
            
            // Mesajı gösterdikten sonra temizle
            unset($_SESSION['flash_message']);
            unset($_SESSION['flash_type']);
            
            return $flash;
        }
        
        return null;
    }
}

/**
 * Flash message set et (message, type formatında)
 * * @param string $message Mesaj
 * @param string $type Tip (success, error, warning, info)
 * @return void
 */
if (!function_exists('setFlashMessage')) {
    function setFlashMessage($message, $type = 'info') {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }
}

/**
 * Flash mesajı var mı kontrol et
 * 
 * @return bool
 */
if (!function_exists('hasFlashMessage')) {
    function hasFlashMessage() {
        return isset($_SESSION['flash_message']) && !empty($_SESSION['flash_message']);
    }
}

/**
 * Flash mesaj tipini getir
 * 
 * @return string
 */
if (!function_exists('getFlashType')) {
    function getFlashType() {
        return $_SESSION['flash_type'] ?? 'info';
    }
}

/**
 * Flash mesajları temizle
 * 
 * @return void
 */
if (!function_exists('clearFlashMessage')) {
    function clearFlashMessage() {
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
    }
}

/**
 * Old input değeri set et (form hatası sonrası)
 * * @param array $data Form verileri
 * @return void
 */
if (!function_exists('setOldInput')) {
    function setOldInput($data) {
        $_SESSION['old'] = $data;
    }
}

/**
 * Old input değeri al
 * * @param string $key Input adı
 * @param mixed $default Varsayılan değer
 * @return mixed Değer
 */
if (!function_exists('getOldInput')) {
    function getOldInput($key = null, $default = '') {
        if ($key === null) {
            $old = $_SESSION['old'] ?? [];
            unset($_SESSION['old']);
            return $old;
        }
        
        $value = $_SESSION['old'][$key] ?? $default;
        return $value;
    }
}

/**
 * Old input'u temizle
 * * @return void
 */
if (!function_exists('clearOldInput')) {
    function clearOldInput() {
        unset($_SESSION['old']);
    }
}

/**
 * Validation errors set et
 * * @param array $errors Hatalar
 * @return void
 */
if (!function_exists('setErrors')) {
    function setErrors($errors) {
        $_SESSION['errors'] = $errors;
    }
}

/**
 * Validation errors al
 * * @param string $key Hata anahtarı (opsiyonel)
 * @return mixed Hatalar veya belirli bir hata
 */
if (!function_exists('getErrors')) {
    function getErrors($key = null) {
        if ($key === null) {
            $errors = $_SESSION['errors'] ?? [];
            unset($_SESSION['errors']);
            return $errors;
        }
        
        return $_SESSION['errors'][$key] ?? null;
    }
}

/**
 * Validation error var mı kontrol et
 * * @param string $key Hata anahtarı (opsiyonel)
 * @return bool Var mı?
 */
if (!function_exists('hasError')) {
    function hasError($key = null) {
        if ($key === null) {
            return !empty($_SESSION['errors']);
        }
        
        return isset($_SESSION['errors'][$key]);
    }
}

/**
 * İlk validation error'u al
 * * @param string $key Hata anahtarı
 * @return string|null İlk hata
 */
if (!function_exists('firstError')) {
    function firstError($key) {
        $errors = $_SESSION['errors'][$key] ?? null;
        
        if (is_array($errors)) {
            return $errors[0] ?? null;
        }
        
        return $errors;
    }
}

/**
 * Errors'u temizle
 * * @return void
 */
if (!function_exists('clearErrors')) {
    function clearErrors() {
        unset($_SESSION['errors']);
    }
}

/**
 * User session'ı set et (login)
 * * @param array $user Kullanıcı bilgileri
 * @return void
 */
if (!function_exists('setUserSession')) {
    function setUserSession($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = $user;
        $_SESSION['logged_in'] = true;
        $_SESSION['login_time'] = time();
        
        // Session regenerate (güvenlik)
        session_regenerate_id(true);
    }
}

/**
 * User session'ı al
 * * @param string $key Bilgi anahtarı (opsiyonel)
 * @return mixed User bilgisi
 */
if (!function_exists('getUserSession')) {
    function getUserSession($key = null) {
        if ($key === null) {
            return $_SESSION['user'] ?? null;
        }
        
        return $_SESSION['user'][$key] ?? null;
    }
}

/**
 * User session'ı temizle (logout)
 * * @return void
 */
if (!function_exists('clearUserSession')) {
    function clearUserSession() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user']);
        unset($_SESSION['logged_in']);
        unset($_SESSION['login_time']);
    }
}

/**
 * Login check
 * * @return bool Giriş yapılmış mı?
 */
if (!function_exists('checkLogin')) {
    function checkLogin() {
        return isset($_SESSION['user_id']) && isset($_SESSION['logged_in']);
    }
}

/**
 * Session timeout kontrolü
 * * @param int $maxLifetime Maksimum süre (saniye)
 * @return bool Timeout olmuş mu?
 */
if (!function_exists('checkSessionTimeout')) {
    function checkSessionTimeout($maxLifetime = null) {
        // Sabit tanımlı değilse varsayılanı kullan
        $maxLifetime = $maxLifetime ?? (defined('SESSION_LIFETIME') ? SESSION_LIFETIME : 7200);

        if (!isset($_SESSION['login_time'])) {
            return false;
        }
        
        return (time() - $_SESSION['login_time']) > $maxLifetime;
    }
}

/**
 * Session'ı yenile (activity sonrası)
 * * @return void
 */
if (!function_exists('refreshSession')) {
    function refreshSession() {
        $_SESSION['login_time'] = time();
    }
}

/**
 * Remember me token set et
 * * @param string $token Token
 * @param int $userId User ID
 * @return void
 */
if (!function_exists('setRememberToken')) {
    function setRememberToken($token, $userId) {
        $expire = time() + (30 * 24 * 60 * 60); // 30 gün
        $basePath = defined('BASE_PATH') ? BASE_PATH : '/';

        setcookie('remember_token', $token, $expire, $basePath, '', true, true);
        setcookie('remember_user', $userId, $expire, $basePath, '', true, true);
    }
}

/**
 * Remember me token al
 * * @return array|null Token ve User ID
 */
if (!function_exists('getRememberToken')) {
    function getRememberToken() {
        if (isset($_COOKIE['remember_token']) && isset($_COOKIE['remember_user'])) {
            return [
                'token' => $_COOKIE['remember_token'],
                'user_id' => $_COOKIE['remember_user']
            ];
        }
        
        return null;
    }
}

/**
 * Remember me token sil
 * * @return void
 */
if (!function_exists('clearRememberToken')) {
    function clearRememberToken() {
        $basePath = defined('BASE_PATH') ? BASE_PATH : '/';
        setcookie('remember_token', '', time() - 3600, $basePath, '', true, true);
        setcookie('remember_user', '', time() - 3600, $basePath, '', true, true);
    }
}

/**
 * Kullanıcı giriş yapmış mı?
 * * @return bool Giriş yapılmış mı?
 */
if (!function_exists('isLoggedIn')) {
    function isLoggedIn() {
        // Yeni Auth sistemi: $_SESSION['user'] array kontrolü
        if (isset($_SESSION['user']) && is_array($_SESSION['user']) && isset($_SESSION['user']['id'])) {
            return true;
        }
        // Eski sistem için geriye dönük uyumluluk
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
}

/**
 * Auth user bilgisi al
 * * @param string $key Bilgi anahtarı (opsiyonel)
 * @return mixed User bilgisi
 */
if (!function_exists('auth')) {
    function auth($key = null) {
        if (!isLoggedIn()) {
            return null;
        }
        
        if ($key === null) {
            return $_SESSION['user'] ?? null;
        }
        
        return $_SESSION['user'][$key] ?? null;
    }
}

/**
 * Mevcut kullanıcı bilgisini al (auth() fonksiyonunun alias'ı)
 * 
 * @return array|null Kullanıcı bilgisi
 */
if (!function_exists('currentUser')) {
    function currentUser() {
        return auth();
    }
}

/**
 * Mevcut kullanıcı ID'sini al
 * 
 * @return int|null Kullanıcı ID'si
 */
if (!function_exists('getCurrentUserId')) {
    function getCurrentUserId() {
        return $_SESSION['user_id'] ?? null;
    }
}

/**
 * Yetki kontrolü (kullanıcı rolü)
 * * @param int|array $roleIds Rol ID(leri)
 * @return bool Yetkili mi?
 */
if (!function_exists('hasRole')) {
    function hasRole($roleIds) {
        if (!isLoggedIn()) {
            return false;
        }
        
        $userRoleId = auth('role_id');
        
        if ($userRoleId === null) {
            return false;
        }
        
        $roleIds = (array)$roleIds;
        return in_array($userRoleId, $roleIds);
    }
}

/**
 * Admin kontrolü
 * * @return bool Admin mi?
 */
if (!function_exists('isAdmin')) {
    function isAdmin() {
        $roleAdmin = defined('ROLE_ADMIN') ? ROLE_ADMIN : 1;
        return hasRole($roleAdmin);
    }
}

/**
 * Öğretmen kontrolü
 * * @return bool Öğretmen mi?
 */
if (!function_exists('isTeacher')) {
    function isTeacher() {
        $roleTeacher = defined('ROLE_TEACHER') ? ROLE_TEACHER : 2;
        return hasRole($roleTeacher);
    }
}

/**
 * Öğrenci kontrolü
 * * @return bool Öğrenci mi?
 */
if (!function_exists('isStudent')) {
    function isStudent() {
        $roleStudent = defined('ROLE_STUDENT') ? ROLE_STUDENT : 3;
        return hasRole($roleStudent);
    }
}

/**
 * Kullanıcının sayfa izni var mı kontrol et
 * 
 * @param string $pageSlug Sayfa slug'ı
 * @param string $permissionType İzin tipi: can_view, can_create, can_edit, can_delete
 * @return bool
 */
if (!function_exists('hasPermission')) {
    function hasPermission($pageSlug, $permissionType = 'can_view') {
        if (!isLoggedIn()) {
            return false;
        }
        
        // Admin her zaman tüm izinlere sahiptir
        if (isAdmin()) {
            return true;
        }
        
        require_once __DIR__ . '/../middleware/PermissionMiddleware.php';
        return \App\Middleware\PermissionMiddleware::check($pageSlug, $permissionType);
    }
}

/**
 * Kullanıcı şifre değiştirebilir mi?
 * 
 * @return bool
 */
if (!function_exists('canChangePassword')) {
    function canChangePassword() {
        if (!isLoggedIn()) {
            return false;
        }
        
        // Admin her zaman şifre değiştirebilir
        if (isAdmin()) {
            return true;
        }
        
        // Diğer kullanıcılar için can_change_password kontrolü
        $canChange = auth('can_change_password');
        return $canChange == 1;
    }
}

/**
 * Kullanıcı kullanıcı adını değiştirebilir mi?
 * Öğretmenler kullanıcı adı değiştiremez
 * 
 * @return bool
 */
if (!function_exists('canChangeUsername')) {
    function canChangeUsername() {
        if (!isLoggedIn()) {
            return false;
        }
        
        // Öğretmenler kullanıcı adı değiştiremez
        if (isTeacher()) {
            return false;
        }
        
        // Admin ve diğerleri değiştirebilir
        return true;
    }
}
