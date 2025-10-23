<?php
/**
 * Global Constants
 * Vildan Portal - Production Settings
 */

// ============================================================
// URL SABİTLERİ - PRODUCTION
// ============================================================
if (!defined('BASE_URL')) define('BASE_URL', 'https://vldn.in/portalv2');
if (!defined('BASE_PATH')) define('BASE_PATH', '/portalv2');
if (!defined('SITE_URL')) define('SITE_URL', 'https://vldn.in');
if (!defined('PUBLIC_URL')) define('PUBLIC_URL', 'https://vldn.in/portalv2/public');

// ============================================================
// ASSET URL'LERİ
// ============================================================
if (!defined('ASSETS_URL')) define('ASSETS_URL', PUBLIC_URL . '/assets');
if (!defined('UPLOADS_URL')) define('UPLOADS_URL', PUBLIC_URL . '/assets/uploads');
if (!defined('CSS_URL')) define('CSS_URL', ASSETS_URL . '/css');
if (!defined('JS_URL')) define('JS_URL', ASSETS_URL . '/js');
if (!defined('IMG_URL')) define('IMG_URL', ASSETS_URL . '/images');

// ============================================================
// DOSYA YOLLARI (cPanel)
// ============================================================
if (!defined('ROOT_PATH')) define('ROOT_PATH', '/home/vildacgg/vldn.in/portalv2');
if (!defined('APP_PATH')) define('APP_PATH', ROOT_PATH . '/app');
if (!defined('CONFIG_PATH')) define('CONFIG_PATH', ROOT_PATH . '/config');
if (!defined('CORE_PATH')) define('CORE_PATH', ROOT_PATH . '/core');
if (!defined('PUBLIC_PATH')) define('PUBLIC_PATH', ROOT_PATH . '/public');
if (!defined('STORAGE_PATH')) define('STORAGE_PATH', ROOT_PATH . '/storage');
if (!defined('VIEW_PATH')) define('VIEW_PATH', APP_PATH . '/views');
if (!defined('LOG_PATH')) define('LOG_PATH', dirname(__DIR__) . '/storage/logs');
if (!defined('CACHE_PATH')) define('CACHE_PATH', STORAGE_PATH . '/cache');
if (!defined('UPLOAD_PATH')) define('UPLOAD_PATH', PUBLIC_PATH . '/assets/uploads');

// ============================================================
// UYGULAMA SABİTLERİ
// ============================================================
if (!defined('APP_NAME')) define('APP_NAME', 'Vildan Portal');
if (!defined('APP_ENV')) define('APP_ENV', 'production');
if (!defined('APP_DEBUG')) define('APP_DEBUG', true); // ⚠️ DEBUG MODE (Temporary for delete button testing)

// ============================================================
// TARİH VE ZAMAN
// ============================================================
if (!defined('DATE_FORMAT')) define('DATE_FORMAT', 'd.m.Y');
if (!defined('TIME_FORMAT')) define('TIME_FORMAT', 'H:i');
if (!defined('DATETIME_FORMAT')) define('DATETIME_FORMAT', 'd.m.Y H:i');
if (!defined('TIMEZONE')) define('TIMEZONE', 'Europe/Istanbul');

// Türkçe aylar
if (!defined('TR_MONTHS')) define('TR_MONTHS', [
    1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan',
    5 => 'Mayıs', 6 => 'Haziran', 7 => 'Temmuz', 8 => 'Ağustos',
    9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık'
]);

// Türkçe günler
if (!defined('TR_DAYS')) define('TR_DAYS', [
    'Monday' => 'Pazartesi',
    'Tuesday' => 'Salı',
    'Wednesday' => 'Çarşamba',
    'Thursday' => 'Perşembe',
    'Friday' => 'Cuma',
    'Saturday' => 'Cumartesi',
    'Sunday' => 'Pazar'
]);

// ============================================================
// GÜVENLİK
// ============================================================
if (!defined('CSRF_TOKEN_LIFETIME')) define('CSRF_TOKEN_LIFETIME', 86400); // 24 saat
if (!defined('SESSION_LIFETIME')) define('SESSION_LIFETIME', 86400); // 24 saat
if (!defined('PASSWORD_MIN_LENGTH')) define('PASSWORD_MIN_LENGTH', 8);
if (!defined('MAX_LOGIN_ATTEMPTS')) define('MAX_LOGIN_ATTEMPTS', 5);
if (!defined('LOGIN_LOCKOUT_TIME')) define('LOGIN_LOCKOUT_TIME', 900); // 15 dakika

// ============================================================
// VERİTABANI SABİTLERİ
// ============================================================
if (!defined('DB_DRIVER')) define('DB_DRIVER', 'mysql');
if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_PORT')) define('DB_PORT', 3306);
if (!defined('DB_NAME')) define('DB_NAME', 'vildacgg_portalv2');
if (!defined('DB_USER')) define('DB_USER', 'vildacgg_tarihci20');
if (!defined('DB_PASS')) define('DB_PASS', 'C@rg_;NBXBu5');
if (!defined('DB_CHARSET')) define('DB_CHARSET', 'utf8mb4');
if (!defined('DB_COLLATION')) define('DB_COLLATION', 'utf8mb4_unicode_ci');
if (!defined('DB_PREFIX')) define('DB_PREFIX', 'vp_');

// ============================================================
// SAYFALAMA
// ==============================================
if (!defined('PER_PAGE')) define('PER_PAGE', 20);
if (!defined('MAX_PER_PAGE')) define('MAX_PER_PAGE', 100);
if (!defined('STUDENTS_PER_PAGE')) define('STUDENTS_PER_PAGE', 50);  // ← EKLE
if (!defined('SEARCH_RESULTS_PER_PAGE')) define('SEARCH_RESULTS_PER_PAGE', 20);  // ← EKLE
// ============================================================

// DOSYA YÜKLEME
// ============================================================
if (!defined('MAX_UPLOAD_SIZE')) define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024); // 10MB
if (!defined('ALLOWED_IMAGE_TYPES')) define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
if (!defined('ALLOWED_DOCUMENT_TYPES')) define('ALLOWED_DOCUMENT_TYPES', ['pdf', 'doc', 'docx', 'xls', 'xlsx']);
if (!defined('MAX_FILE_SIZE')) define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
if (!defined('ALLOWED_EXCEL_TYPES')) define('ALLOWED_EXCEL_TYPES', [
    'application/vnd.ms-excel',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'application/octet-stream'
]);

// ============================================================
// UPLOAD YOLLARI (Özel Klasörler)
// ============================================================
if (!defined('ACTIVITY_AREA_UPLOAD_PATH')) define('ACTIVITY_AREA_UPLOAD_PATH', UPLOAD_PATH . '/activity-areas');
if (!defined('ACTIVITY_UPLOAD_PATH')) define('ACTIVITY_UPLOAD_PATH', UPLOAD_PATH . '/activities');
if (!defined('STUDENT_UPLOAD_PATH')) define('STUDENT_UPLOAD_PATH', UPLOAD_PATH . '/students');
if (!defined('USER_UPLOAD_PATH')) define('USER_UPLOAD_PATH', UPLOAD_PATH . '/users');
if (!defined('ETUT_UPLOAD_PATH')) define('ETUT_UPLOAD_PATH', UPLOAD_PATH . '/etuts');

// Timezone ayarla
date_default_timezone_set(TIMEZONE);
