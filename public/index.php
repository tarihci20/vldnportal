<?php
// ======================================================================
// FATAL ERROR YAKALAMA (Beyaz sayfa için)
// ======================================================================
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error) {
        echo '<pre style="background:#fff;color:#c00;padding:16px;border:2px solid #c00;font-size:16px;">';
        echo "<b>Fatal Error:</b>\n";
        print_r($error);
        echo '</pre>';
    }
});

// ======================================================================
// 0. HATA AYIKLAMA KODU (Geliştirme için)
// ======================================================================
error_reporting(E_ALL);
ini_set('display_errors', '1'); // Hataları ekranda göster


// ======================================================================
// 1. COMPOSER VE ORTAM DEĞİŞKENLERİNİ YÜKLEME
// ======================================================================

// Composer Autoloader'ı Yükle
require __DIR__ . '/../vendor/autoload.php';


// 1. Sabitler ve config dosyalarını yükle
$rootPath = dirname(__DIR__);

// ÖNCE: Fallback ROOT_PATH'ı tanımla (constants.php'nin ihtiyacı var)
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $rootPath);
}

// SONRA: constants.php'yi yüklemeye çalış
$constantsLoaded = false;
$constantsPaths = [
    ROOT_PATH . '/config/constants.php',
    '/home/vildacgg/vldn.in/portalv2/config/constants.php',
];

foreach ($constantsPaths as $path) {
    if (file_exists($path)) {
        require_once $path;
        $constantsLoaded = true;
        break;
    }
}

// SONRA: Eğer constants.php yüklenemedi, manuel tanımlama yap
if (!$constantsLoaded) {
    // Fallback: define constants manually
    define('BASE_URL', 'https://vldn.in/portalv2');
    define('BASE_PATH', '/portalv2');
    define('SITE_URL', 'https://vldn.in');
    define('PUBLIC_URL', 'https://vldn.in/portalv2/public');
    define('ASSETS_URL', PUBLIC_URL . '/assets');
    define('UPLOADS_URL', PUBLIC_URL . '/assets/uploads');
    define('CSS_URL', ASSETS_URL . '/css');
    define('JS_URL', ASSETS_URL . '/js');
    define('IMG_URL', ASSETS_URL . '/images');
    define('APP_PATH', ROOT_PATH . '/app');
    define('CONFIG_PATH', ROOT_PATH . '/config');
    define('CORE_PATH', ROOT_PATH . '/core');
    define('PUBLIC_PATH', ROOT_PATH . '/public');
    define('APP_DEBUG', false);
}

// 2. Veritabanı sabitlerini tanımla (constants.php'den sonra)
if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_PORT')) define('DB_PORT', '3306');
if (!defined('DB_NAME')) define('DB_NAME', 'vildacgg_portalv2');
if (!defined('DB_USER')) define('DB_USER', 'vildacgg_tarihci20');
if (!defined('DB_PASS')) define('DB_PASS', 'C@rg_;NBXBu5');
if (!defined('DB_CHARSET')) define('DB_CHARSET', 'utf8mb4');

// 3. Diğer config ve core dosyalarını yükle
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/core/Database.php';
require_once ROOT_PATH . '/core/Router.php';
require_once ROOT_PATH . '/core/Request.php';
require_once ROOT_PATH . '/core/Response.php';
require_once ROOT_PATH . '/core/Controller.php';

// Helper dosyaları
require_once APP_PATH . '/helpers/session.php';
require_once APP_PATH . '/helpers/response.php';
require_once APP_PATH . '/helpers/functions.php';
require_once APP_PATH . '/helpers/excel.php';


// ======================================================================
// 3. SESSION BAŞLATMA
// ======================================================================

startSession();


// ======================================================================
// 3.5. TEST DOSYALARINI BYPASS ET (Development/Debug)
// ======================================================================

$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$testFiles = ['debug-delete.php', 'test-delete-user.php', 'debug-session.php', 'check-code.php'];

foreach ($testFiles as $testFile) {
    // Kontrol: URL'de test dosyası var mı?
    if (strpos($requestUri, '/' . $testFile) !== false) {
        // Dosyayı doğrudan serve et (router'ı bypass et)
        $filePath = __DIR__ . '/' . $testFile;
        if (file_exists($filePath)) {
            include $filePath;
            exit;
        }
    }
}

// ======================================================================
// 4. ROUTER VE ROUTE TANIMALARI
// ======================================================================

use Core\Router;

// Router oluştur (BASE_PATH'i kullan)
$router = new Router(BASE_PATH);

// Route dosyalarını yükle
require_once ROOT_PATH . '/routes/api.php';
require_once ROOT_PATH . '/routes/web.php';


// ======================================================================
// 5. ROUTE DISPATCH (İsteği işle)
// ======================================================================

try {
    $router->dispatch();
} catch (Exception $e) {
    // AJAX isteği için JSON hata dön
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Bir hata oluştu',
            'error' => APP_DEBUG ? $e->getMessage() : null,
            'trace' => APP_DEBUG ? $e->getTraceAsString() : null
        ]);
        exit;
    }
    
    // Normal istek için HTML hata
    if (APP_DEBUG) {
        echo "<h1>Router Error</h1>";
        echo "<p>" . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    } else {
        http_response_code(500);
        echo "Bir hata oluştu.";
    }
}