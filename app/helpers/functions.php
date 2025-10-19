<?php
/**
 * Vildan Portal - Global Helper Functions (DÜZELTİLMİŞ)
 * Path: /home/vildacgg/vldn.in/portalv2/app/helpers/functions.php
 * * NOT: JSON/HTTP Response fonksiyonları response.php'de tanımlı.
 * NOT: Session fonksiyonları session.php'de tanımlı
 */

/**
 * Debug için dump ve die
 */
if (!function_exists('dd')) {
    function dd(...$vars) {
        echo '<pre style="background: #1e1e1e; color: #dcdcdc; padding: 20px; margin: 10px; border-radius: 5px; font-family: Consolas, monospace; font-size: 14px;">';
        foreach ($vars as $var) {
            var_dump($var);
            echo "\n---\n";
        }
        echo '</pre>';
        die();
    }
}

/**
 * Debug için dump (die olmadan)
 */
if (!function_exists('dump')) {
    function dump(...$vars) {
        echo '<pre style="background: #1e1e1e; color: #dcdcdc; padding: 20px; margin: 10px; border-radius: 5px; font-family: Consolas, monospace; font-size: 14px;">';
        foreach ($vars as $var) {
            var_dump($var);
            echo "\n---\n";
        }
        echo '</pre>';
    }
}

/**
 * URL oluşturur
 */
if (!function_exists('url')) {
    function url($path = '') {
        // BASE_URL sabitinin tanımlı olduğundan emin olun (constants.php'den gelir)
        if (!defined('BASE_URL')) {
            // Hata kontrolü için loglama yapılabilir veya istisna fırlatılabilir
            return $path;
        }
        $baseUrl = rtrim(BASE_URL, '/');
        $path = ltrim($path, '/');
        return $baseUrl . ($path ? '/' . $path : '');
    }
}

/**
 * Base path ile URL oluşturur
 */
if (!function_exists('urlPath')) {
    function urlPath($path = '') {
        // BASE_PATH sabitinin tanımlı olduğundan emin olun
        if (!defined('BASE_PATH')) {
             // Hata kontrolü
            return $path;
        }
        $basePath = rtrim(BASE_PATH, '/');
        $path = ltrim($path, '/');
        return $basePath . ($path ? '/' . $path : '');
    }
}

/**
 * Asset URL oluşturur
 */
if (!function_exists('asset')) {
    function asset($path) {
        return url('assets/' . ltrim($path, '/'));
    }
}

/**
 * Upload URL oluşturur
 */
if (!function_exists('upload')) {
    function upload($path) {
        return url('assets/uploads/' . ltrim($path, '/'));
    }
}

/**
 * Config değeri getirir
 * NOT: Bu fonksiyon, config dosyalarını yeniden yüklemeyi denediği için dikkatli kullanılmalıdır.
 * Gelişmiş Framework'lerde bu sadece App nesnesi üzerinden yapılır.
 */
if (!function_exists('config')) {
    function config($key, $default = null) {
        static $config = null;
        
        // Sadece bir kez yükleme yap
        if ($config === null) {
            $config = [];
            
            // CONFIG_PATH sabitinin tanımlı olduğundan emin olun
            if (!defined('CONFIG_PATH')) return $default;

            $configFiles = [
                'config' => CONFIG_PATH . '/config.php',
                'database' => CONFIG_PATH . '/database.php'
            ];
            
            foreach ($configFiles as $name => $file) {
                if (file_exists($file)) {
                    $data = require $file;
                    if (is_array($data)) {
                        // Sadece config ve database dosyalarını yükle (constants zaten yüklü)
                        $config[$name] = $data;
                    }
                }
            }
        }
        
        $keys = explode('.', $key);
        $value = $config;
        
        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }
        
        return $value;
    }
}

/**
 * View render eder
 */
if (!function_exists('view')) {
    function view($view, $data = []) {
        // VIEW_PATH sabitinin tanımlı olduğundan emin olun
        if (!defined('VIEW_PATH')) {
            throw new Exception("VIEW_PATH sabiti tanımlanmadı.");
        }

        extract($data);
        $viewFile = VIEW_PATH . '/' . str_replace('.', '/', $view) . '.php';
        
        if (!file_exists($viewFile)) {
            throw new Exception("View bulunamadı: $viewFile");
        }
        
        // output buffer başlat
        ob_start();
        require $viewFile;
        return ob_get_clean(); // Buffer içeriğini döndür ve temizle
    }
}

/**
 * Partial view include eder
 */
if (!function_exists('partial')) {
    function partial($partial, $data = []) {
        // VIEW_PATH sabitinin tanımlı olduğundan emin olun
        if (!defined('VIEW_PATH')) return;

        extract($data);
        $partialFile = VIEW_PATH . '/partials/' . $partial . '.php';
        
        if (file_exists($partialFile)) {
            require $partialFile;
        }
    }
}

/**
 * Component include eder
 */
if (!function_exists('component')) {
    function component($component, $data = []) {
        // VIEW_PATH sabitinin tanımlı olduğundan emin olun
        if (!defined('VIEW_PATH')) return;

        extract($data);
        $componentFile = VIEW_PATH . '/components/' . $component . '.php';
        
        if (file_exists($componentFile)) {
            require $componentFile;
        }
    }
}

/**
 * HTML escape eder (XSS koruması)
 */
if (!function_exists('e')) {
    function e($string) {
        return htmlspecialchars((string)($string ?? ''), ENT_QUOTES, 'UTF-8');
    }
}

/**
 * HTML escape eder - alias (XSS koruması)
 */
if (!function_exists('esc')) {
    function esc($string) {
        return e($string);
    }
}

/**
 * Old input değeri getirir (form hatası sonrası)
 * NOT: session.php'deki getOldInput fonksiyonunu kullanır
 */
if (!function_exists('old')) {
    function old($key, $default = '') {
        // getOldInput() fonksiyonunun session.php'de tanımlı olduğunu varsayarız
        if (function_exists('getOldInput')) {
            return getOldInput($key, $default);
        }
        // Fallback olarak doğrudan $_SESSION'dan çekilir
        return $_SESSION['old'][$key] ?? $default; 
    }
}

// ... Diğer tüm fonksiyonlar (formatDate, timeAgo, formatFileSize, vb.) if (!function_exists()) kontrolü ile korunmuştur ve doğrudur.
// Bu kısım, dosyanın orijinal içeriğiyle aynıdır.

/**
 * Tarih formatlar (Türkçe)
 */
if (!function_exists('formatDate')) {
    function formatDate($date, $format = null) {
        if ($format === null) {
            $format = defined('DATE_FORMAT') ? DATE_FORMAT : 'd.m.Y';
        }
        
        if (empty($date) || $date === '0000-00-00' || $date === '0000-00-00 00:00:00') {
            return '-';
        }
        
        try {
            $dt = new DateTime($date);
            return $dt->format($format);
        } catch (Exception $e) {
            return $date;
        }
    }
}

/**
 * Tarih formatlar (Türkçe ay adlarıyla)
 */
if (!function_exists('formatDateTurkish')) {
    function formatDateTurkish($date) {
        if (empty($date) || $date === '0000-00-00' || $date === '0000-00-00 00:00:00') {
            return '-';
        }
        
        $months = defined('TR_MONTHS') ? TR_MONTHS : [
            1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan',
            5 => 'Mayıs', 6 => 'Haziran', 7 => 'Temmuz', 8 => 'Ağustos',
            9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık'
        ];
        
        $days = defined('TR_DAYS') ? TR_DAYS : [
            'Monday' => 'Pazartesi', 'Tuesday' => 'Salı', 'Wednesday' => 'Çarşamba',
            'Thursday' => 'Perşembe', 'Friday' => 'Cuma', 'Saturday' => 'Cumartesi',
            'Sunday' => 'Pazar'
        ];
        
        try {
            $dt = new DateTime($date);
            $day = $dt->format('d');
            $month = $months[(int)$dt->format('m')];
            $year = $dt->format('Y');
            $dayName = $days[$dt->format('l')] ?? $dt->format('l');
            
            return "$day $month $year, $dayName";
        } catch (Exception $e) {
            return $date;
        }
    }
}

/**
 * Datetime formatlar
 */
if (!function_exists('formatDateTime')) {
    function formatDateTime($datetime, $format = null) {
        if ($format === null) {
            $format = defined('DATETIME_FORMAT') ? DATETIME_FORMAT : 'd.m.Y H:i';
        }
        return formatDate($datetime, $format);
    }
}

/**
 * Zaman farkını hesaplar
 */
if (!function_exists('timeAgo')) {
    function timeAgo($datetime) {
        if (empty($datetime)) {
            return '-';
        }
        
        try {
            $now = new DateTime();
            $ago = new DateTime($datetime);
            $diff = $now->diff($ago);
            
            if ($diff->y > 0) return $diff->y . ' yıl önce';
            if ($diff->m > 0) return $diff->m . ' ay önce';
            if ($diff->d > 0) return ($diff->d == 1) ? 'Dün' : $diff->d . ' gün önce';
            if ($diff->h > 0) return $diff->h . ' saat önce';
            if ($diff->i > 0) return $diff->i . ' dakika önce';
            return 'Az önce';
        } catch (Exception $e) {
            return $datetime;
        }
    }
}

/**
 * Dosya boyutunu okunabilir formata çevirir
 */
if (!function_exists('formatFileSize')) {
    function formatFileSize($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}

/**
 * String'i belirli uzunlukta keser
 */
if (!function_exists('truncate')) {
    function truncate($string, $length = 100, $append = '...') {
        if (mb_strlen($string) <= $length) {
            return $string;
        }
        
        return mb_substr($string, 0, $length) . $append;
    }
}

/**
 * String'i slug'a çevirir
 */
if (!function_exists('slugify')) {
    function slugify($string) {
        $turkish = ['ı', 'İ', 'ş', 'Ş', 'ğ', 'Ğ', 'ü', 'Ü', 'ö', 'Ö', 'ç', 'Ç'];
        $english = ['i', 'i', 's', 's', 'g', 'g', 'u', 'u', 'o', 'o', 'c', 'c'];
        $string = str_replace($turkish, $english, $string);
        $string = strtolower($string);
        $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
        $string = preg_replace('/[\s-]+/', '-', $string);
        return trim($string, '-');
    }
}

/**
 * Para formatlar (TL)
 */
if (!function_exists('formatMoney')) {
    function formatMoney($amount, $symbol = true) {
        $formatted = number_format($amount, 2, ',', '.');
        return $symbol ? $formatted . ' ₺' : $formatted;
    }
}

/**
 * Sayıyı formatlar (Türkçe)
 */
if (!function_exists('formatNumber')) {
    function formatNumber($number, $decimals = 0) {
        return number_format($number, $decimals, ',', '.');
    }
}

/**
 * Rastgele string oluşturur
 */
if (!function_exists('randomString')) {
    function randomString($length = 16, $characters = null) {
        if ($characters === null) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        
        $string = '';
        $max = strlen($characters) - 1;
        
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[random_int(0, $max)];
        }
        
        return $string;
    }
}

/**
 * Log yazar
 */
if (!function_exists('logger')) {
    function logger($message, $level = 'info', $context = []) {
        $logPath = defined('LOG_PATH') ? LOG_PATH : (defined('ROOT_PATH') ? ROOT_PATH . '/storage/logs' : __DIR__ . '/../../storage/logs');
        $logFile = $logPath . '/app.log';
        $logDir = dirname($logFile);
        
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0755, true);
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? ' ' . json_encode($context, JSON_UNESCAPED_UNICODE) : '';
        $logMessage = "[$timestamp] $level: $message$contextStr" . PHP_EOL;
        
        @file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
}

/**
 * Error log yazar
 */
if (!function_exists('logError')) {
    function logError($message, $context = []) {
        logger($message, 'error', $context);
    }
}

/**
 * Security log yazar
 */
if (!function_exists('logSecurity')) {
    function logSecurity($message, $context = []) {
        $logPath = defined('LOG_PATH') ? LOG_PATH : (defined('ROOT_PATH') ? ROOT_PATH . '/storage/logs' : __DIR__ . '/../../storage/logs');
        $logFile = $logPath . '/security.log';
        $logDir = dirname($logFile);
        
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0755, true);
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $ip = getClientIp();
        $contextStr = !empty($context) ? ' ' . json_encode($context, JSON_UNESCAPED_UNICODE) : '';
        $logMessage = "[$timestamp] [IP: $ip] $message$contextStr" . PHP_EOL;
        
        @file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
}

/**
 * IP adresini getirir
 */
if (!function_exists('getClientIp')) {
    function getClientIp() {
        $keys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 
                  'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];
        
        foreach ($keys as $key) {
            if (!empty($_SERVER[$key])) {
                $ips = explode(',', $_SERVER[$key]);
                $ip = trim($ips[0]);
                
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    }
}

/**
 * User agent getirir
 */
if (!function_exists('getUserAgent')) {
    function getUserAgent() {
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }
}

/**
 * Mobil cihaz kontrolü
 */
if (!function_exists('isMobile')) {
    function isMobile() {
        $userAgent = getUserAgent();
        return preg_match('/(android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini)/i', $userAgent);
    }
}

/**
 * Aktif menü item kontrolü
 */
if (!function_exists('activeMenu')) {
    function activeMenu($routes, $class = 'active') {
        $currentRoute = $_SERVER['REQUEST_URI'] ?? '/';
        $routes = (array)$routes;
        
        foreach ($routes as $route) {
            // urlPath fonksiyonunun session.php'de tanımlı olduğunu varsayıyoruz
            if (function_exists('urlPath')) {
                $route = urlPath($route);
            }
            if (strpos($currentRoute, $route) !== false) {
                return $class;
            }
        }
        
        return '';
    }
}

/**
 * CSRF token oluştur/al
 */
if (!function_exists('csrf_token')) {
    function csrf_token() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['csrf_token_time'] = time();
        }
        return $_SESSION['csrf_token'];
    }
}

/**
 * CSRF token al - kısa alias
 */
if (!function_exists('csrf')) {
    function csrf() {
        return csrf_token();
    }
}

/**
 * CSRF token al (alias)
 */
if (!function_exists('getCsrfToken')) {
    function getCsrfToken() {
        return csrf_token();
    }
}

/**
 * CSRF hidden input field
 */
if (!function_exists('csrfField')) {
    function csrfField() {
        $token = csrf_token();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }
}

/**
 * CSRF meta tag
 */
if (!function_exists('csrfMeta')) {
    function csrfMeta() {
        $token = csrf_token();
        return '<meta name="csrf-token" content="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }
}

/**
 * CSRF token doğrula
 */
if (!function_exists('verifyCsrfToken')) {
    function verifyCsrfToken($token) {
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        
        // Token süre kontrolü (1 saat)
        if (isset($_SESSION['csrf_token_time'])) {
            $lifetime = defined('CSRF_TOKEN_LIFETIME') ? CSRF_TOKEN_LIFETIME : 3600;
            if (time() - $_SESSION['csrf_token_time'] > $lifetime) {
                unset($_SESSION['csrf_token']);
                unset($_SESSION['csrf_token_time']);
                return false;
            }
        }
        
        return hash_equals($_SESSION['csrf_token'], $token);
    }
}

/**
 * CSRF token doğrula (alias)
 */
if (!function_exists('validateCsrfToken')) {
    function validateCsrfToken($token) {
        return verifyCsrfToken($token);
    }
}

/**
 * Aktivite logla
 * 
 * @param string $action Aksiyon (örn: 'student_created', 'excel_imported')
 * @param int|null $userId Kullanıcı ID (null ise mevcut kullanıcı)
 * @param array $data Ek bilgiler
 * @return bool
 */
if (!function_exists('logActivity')) {
    function logActivity($action, $userId = null, $data = []) {
        try {
            // Kullanıcı ID'si verilmemişse mevcut kullanıcıyı al
            if ($userId === null) {
                $userId = $_SESSION['user_id'] ?? null;
            }
            
            // Log dizini oluştur
            $logDir = defined('LOG_PATH') ? LOG_PATH : __DIR__ . '/../../storage/logs';
            if (!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
            }
            
            // Log mesajı oluştur
            $logMessage = sprintf(
                "[%s] User:%s Action:%s Data:%s IP:%s\n",
                date('Y-m-d H:i:s'),
                $userId ?? 'guest',
                $action,
                json_encode($data, JSON_UNESCAPED_UNICODE),
                $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            );
            
            // Log dosyasına yaz
            $logFile = $logDir . '/activity.log';
            return file_put_contents($logFile, $logMessage, FILE_APPEND) !== false;
            
        } catch (Exception $e) {
            error_log("Log yazma hatası: " . $e->getMessage());
            return false;
        }
    }
}

/**
 * Telefon numarasını formatla
 * 
 * @param string $phone Telefon numarası
 * @return string Formatlanmış telefon
 */
if (!function_exists('formatPhone')) {
    function formatPhone($phone) {
        if (empty($phone)) {
            return '-';
        }
        
        // Sadece rakamları al
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Türkiye formatı: 0(5XX) XXX XX XX
        if (strlen($phone) == 11 && substr($phone, 0, 1) == '0') {
            return sprintf(
                '0(%s) %s %s %s',
                substr($phone, 1, 3),
                substr($phone, 4, 3),
                substr($phone, 7, 2),
                substr($phone, 9, 2)
            );
        }
        
        // 10 haneli: (5XX) XXX XX XX
        if (strlen($phone) == 10) {
            return sprintf(
                '(%s) %s %s %s',
                substr($phone, 0, 3),
                substr($phone, 3, 3),
                substr($phone, 6, 2),
                substr($phone, 8, 2)
            );
        }
        
        // Diğer durumlar için olduğu gibi döndür
        return $phone;
    }
}
if (!function_exists('isLoggedIn')) {
    /**
     * Kullanıcı giriş yapmış mı kontrol et
     * 
     * @return bool
     */
    function isLoggedIn() {
        return \Core\Auth::getInstance()->check();
    }
}

if (!function_exists('getUser')) {
    /**
     * Mevcut kullanıcıyı al
     * 
     * @return array|null
     */
    function getUser() {
        return \Core\Auth::getInstance()->user();
    }
}

if (!function_exists('getUserId')) {
    /**
     * Mevcut kullanıcının ID'sini al
     * 
     * @return int|null
     */
    function getUserId() {
        $user = getUser();
        return $user ? $user['id'] : null;
    }
}

if (!function_exists('hasRole')) {
    /**
     * Kullanıcının belirli bir rolü var mı kontrol et
     * 
     * @param string $role
     * @return bool
     */
    function hasRole($role) {
        $user = getUser();
        return $user && isset($user['role']) && $user['role'] === $role;
    }
}
// ==============================================
// AUTH HELPER FUNCTIONS
// ==============================================

if (!function_exists('isLoggedIn')) {
    /**
     * Kullanıcı giriş yapmış mı kontrol et
     * 
     * @return bool
     */
    function isLoggedIn() {
        return \Core\Auth::getInstance()->check();
    }
}

if (!function_exists('getUser')) {
    /**
     * Mevcut kullanıcıyı al
     * 
     * @return array|null
     */
    function getUser() {
        return \Core\Auth::getInstance()->user();
    }
}

if (!function_exists('getUserId')) {
    /**
     * Mevcut kullanıcının ID'sini al
     * 
     * @return int|null
     */
    function getUserId() {
        $user = getUser();
        return $user ? ($user['id'] ?? null) : null;
    }
}

if (!function_exists('hasRole')) {
    /**
     * Kullanıcının belirli bir rolü var mı kontrol et
     * 
     * @param string $role
     * @return bool
     */
    function hasRole($role) {
        $user = getUser();
        return $user && isset($user['role']) && $user['role'] === $role;
    }
}

if (!function_exists('can')) {
    /**
     * Kullanıcının belirli bir yetkisi var mı kontrol et
     * 
     * @param string $permission
     * @return bool
     */
    function can($permission) {
        // Şimdilik basit implementasyon
        // İleride permission sistemi eklenebilir
        return isLoggedIn();
    }
}