<?php

// ======================================================================
// 0. HATA AYIKLAMA KODU (Geliştirme için)
// ======================================================================
error_reporting(E_ALL);
ini_set('display_errors', '0'); // Geçici - hataları görmek için


// ======================================================================
// 1. COMPOSER VE ORTAM DEĞİŞKENLERİNİ YÜKLEME
// ======================================================================

// Composer Autoloader'ı Yükle
require __DIR__ . '/../vendor/autoload.php';

// cPanel Production MySQL Ayarları
if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_PORT')) define('DB_PORT', '3306');  // ← BU SATIRI EKLEYİN
if (!defined('DB_NAME')) define('DB_NAME', 'vildacgg_portalv2');
if (!defined('DB_USER')) define('DB_USER', 'vildacgg_tarihci20');
if (!defined('DB_PASS')) define('DB_PASS', 'C@rg_;NBXBu5');
// Charset
if (!defined('DB_CHARSET')) define('DB_CHARSET', 'utf8mb4');

// NOT: APP_ENV ve APP_DEBUG constants.php'de tanımlanacak


// ======================================================================
// 2. ÇEKİRDEK DOSYALAR VE BAĞIMLILIKLAR
// ======================================================================

// İlk olarak constants.php'yi yükle (ROOT_PATH'i tanımlar)
$rootPath = dirname(__DIR__);
require_once $rootPath . '/config/constants.php';

// Diğer config ve core dosyaları
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
// 4. ROUTER VE ROUTE TANIMALARI
// ======================================================================

use Core\Router;

// Router oluştur (BASE_PATH'i kullan)
$router = new Router(BASE_PATH);

// Route dosyalarını yükle
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