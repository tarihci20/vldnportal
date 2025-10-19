<?php
/**
 * Vildan Portal - Ana Yapılandırma Dosyası
 * 
 * Bu dosyayı config.php olarak kopyalayın ve kendi değerlerinizi girin.
 */

// Hata raporlama (Production'da kapatın!)
define('APP_DEBUG', true);
define('DISPLAY_ERRORS', true);

if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', DISPLAY_ERRORS ? 1 : 0);
} else {
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
    ini_set('display_errors', 0);
}

// Uygulama Ayarları
define('APP_NAME', 'Vildan Portal');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'development'); // development, staging, production

// URL Ayarları
define('BASE_URL', 'http://localhost/vildan-portal');
define('BASE_PATH', '/vildan-portal');
define('ASSETS_URL', BASE_URL . '/public/assets');

// Dosya Yolları
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('UPLOAD_PATH', PUBLIC_PATH . '/assets/uploads');

// Veritabanı Ayarları
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'vildan_portal');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Session Ayarları
define('SESSION_NAME', 'vildan_session');
define('SESSION_LIFETIME', 30 * 24 * 60 * 60); // 30 gün (saniye cinsinden)
define('SESSION_COOKIE_SECURE', false); // HTTPS kullanıyorsanız true yapın
define('SESSION_COOKIE_HTTPONLY', true);
define('SESSION_COOKIE_SAMESITE', 'Lax'); // Lax, Strict, None

// Remember Me Ayarları
define('REMEMBER_ME_LIFETIME', 30 * 24 * 60 * 60); // 30 gün
define('MAX_CONCURRENT_SESSIONS', 5); // Aynı anda maksimum oturum sayısı

// Güvenlik Ayarları
define('CSRF_TOKEN_NAME', 'csrf_token');
define('CSRF_TOKEN_LIFETIME', 3600); // 1 saat (saniye)
define('PASSWORD_MIN_LENGTH', 8);
define('PASSWORD_REQUIRE_UPPERCASE', true);
define('PASSWORD_REQUIRE_LOWERCASE', true);
define('PASSWORD_REQUIRE_NUMBER', true);
define('PASSWORD_REQUIRE_SPECIAL', false);

// Rate Limiting (Dakika başına maksimum istek)
define('RATE_LIMIT_LOGIN', 5); // Giriş denemeleri
define('RATE_LIMIT_API', 60); // API istekleri
define('RATE_LIMIT_SEARCH', 30); // Arama istekleri

// Google OAuth Ayarları
define('GOOGLE_CLIENT_ID', 'BURAYA_GOOGLE_CLIENT_ID_GIRIN');
define('GOOGLE_CLIENT_SECRET', 'BURAYA_GOOGLE_CLIENT_SECRET_GIRIN');
define('GOOGLE_REDIRECT_URI', BASE_URL . '/auth/google/callback');
define('GOOGLE_OAUTH_ENABLED', false); // Google login aktif mi?

// E-posta Ayarları (Şifre sıfırlama için)
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'your-email@gmail.com');
define('MAIL_PASSWORD', 'your-app-password');
define('MAIL_ENCRYPTION', 'tls'); // tls veya ssl
define('MAIL_FROM_ADDRESS', 'noreply@vildanportal.com');
define('MAIL_FROM_NAME', 'Vildan Portal');

// Dosya Yükleme Ayarları
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10 MB (1500+ öğrenci için)
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('ALLOWED_EXCEL_TYPES', ['xlsx', 'xls', 'csv']);

// Sayfalama Ayarları
define('STUDENTS_PER_PAGE', 50); // Sayfa başına öğrenci
define('ACTIVITIES_PER_PAGE', 20);
define('SEARCH_RESULTS_PER_PAGE', 20);
define('ADMIN_ITEMS_PER_PAGE', 50);

// Excel Import Ayarları (1500+ öğrenci için)
define('EXCEL_MAX_ROWS', 2000); // Maksimum satır sayısı
define('EXCEL_IMPORT_CHUNK_SIZE', 100); // Her seferinde 100 kayıt işle
define('EXCEL_TIMEOUT', 300); // 5 dakika timeout

// Tarih ve Saat Ayarları
define('TIMEZONE', 'Europe/Istanbul');
define('DATE_FORMAT', 'd.m.Y');
define('TIME_FORMAT', 'H:i');
define('DATETIME_FORMAT', 'd.m.Y H:i');

// Dil Ayarları
define('DEFAULT_LANGUAGE', 'tr');
define('AVAILABLE_LANGUAGES', ['tr', 'en']);

// Tema Ayarları
define('DEFAULT_THEME', 'light'); // light, dark
define('ALLOW_THEME_SWITCH', true);

// Etkinlik Ayarları
define('DEFAULT_TIME_SLOT_DURATION', 30); // Dakika
define('MIN_TIME_SLOT_DURATION', 20);
define('MAX_TIME_SLOT_DURATION', 120);
define('ACTIVITY_LOOK_AHEAD_MONTHS', 3); // Güncel etkinlikler için
define('ALLOW_ACTIVITY_OVERLAP', false);

// Log Ayarları
define('LOG_LEVEL', 'debug'); // debug, info, notice, warning, error, critical
define('LOG_FILE', STORAGE_PATH . '/logs/app.log');
define('ERROR_LOG_FILE', STORAGE_PATH . '/logs/error.log');
define('SECURITY_LOG_FILE', STORAGE_PATH . '/logs/security.log');

// Cache Ayarları
define('CACHE_ENABLED', true);
define('CACHE_LIFETIME', 3600); // 1 saat (saniye)
define('CACHE_PATH', STORAGE_PATH . '/cache');

// PWA Ayarları
define('PWA_ENABLED', true);
define('PWA_NAME', 'Vildan Portal');
define('PWA_SHORT_NAME', 'Vildan');
define('PWA_THEME_COLOR', '#3B82F6');
define('PWA_BACKGROUND_COLOR', '#FFFFFF');

// Yedekleme Ayarları
define('BACKUP_ENABLED', true);
define('BACKUP_PATH', STORAGE_PATH . '/backups');
define('BACKUP_RETENTION_DAYS', 30); // Yedekleri kaç gün sakla

// API Ayarları
define('API_ENABLED', true);
define('API_VERSION', 'v1');
define('API_RATE_LIMIT', 100); // Saat başına istek

// Öğretmen Özel Ayarları
define('TEACHER_SHARED_ACCOUNT', true); // Tüm öğretmenler aynı hesabı kullanır
define('TEACHER_USERNAME', 'ogretmen');
define('TEACHER_DEFAULT_PASSWORD', 'Ogretmen123!'); // İlk kurulumda

// Timezone ayarla
date_default_timezone_set(TIMEZONE);

// Autoload
require_once ROOT_PATH . '/vendor/autoload.php';

// Helper fonksiyonları yükle
$helperFiles = [
    'functions.php',
    'validation.php',
    'sanitize.php',
    'session.php',
    'excel.php',
    'response.php'
];

foreach ($helperFiles as $file) {
    $path = APP_PATH . '/helpers/' . $file;
    if (file_exists($path)) {
        require_once $path;
    }
}