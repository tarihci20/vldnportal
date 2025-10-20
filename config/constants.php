<?php
/**
 * Global Constants
 * Vildan Portal - Production Settings
 */

// ============================================================
// URL SABİTLERİ - PRODUCTION
// ============================================================
define('BASE_URL', 'https://vldn.in/portalv2');
define('BASE_PATH', '/portalv2');
define('SITE_URL', 'https://vldn.in');
define('PUBLIC_URL', 'https://vldn.in/portalv2/public');

// ============================================================
// ASSET URL'LERİ
// ============================================================
define('ASSETS_URL', PUBLIC_URL . '/assets');
define('UPLOADS_URL', PUBLIC_URL . '/assets/uploads');
define('CSS_URL', ASSETS_URL . '/css');
define('JS_URL', ASSETS_URL . '/js');
define('IMG_URL', ASSETS_URL . '/images');

// ============================================================
// DOSYA YOLLARI (cPanel)
// ============================================================
define('ROOT_PATH', '/home/vildacgg/vldn.in/portalv2');
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('CORE_PATH', ROOT_PATH . '/core');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('VIEW_PATH', APP_PATH . '/views');
define('LOG_PATH', dirname(__DIR__) . '/storage/logs');
define('CACHE_PATH', STORAGE_PATH . '/cache');
define('UPLOAD_PATH', PUBLIC_PATH . '/assets/uploads');

// ============================================================
// UYGULAMA SABİTLERİ
// ============================================================
define('APP_NAME', 'Vildan Portal');
define('APP_ENV', 'production');
define('APP_DEBUG', false); // Production'da false olmalı

// ============================================================
// TARİH VE ZAMAN
// ============================================================
define('DATE_FORMAT', 'd.m.Y');
define('TIME_FORMAT', 'H:i');
define('DATETIME_FORMAT', 'd.m.Y H:i');
define('TIMEZONE', 'Europe/Istanbul');

// Türkçe aylar
define('TR_MONTHS', [
    1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan',
    5 => 'Mayıs', 6 => 'Haziran', 7 => 'Temmuz', 8 => 'Ağustos',
    9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık'
]);

// Türkçe günler
define('TR_DAYS', [
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
define('CSRF_TOKEN_LIFETIME', 86400); // 24 saat
define('SESSION_LIFETIME', 86400); // 24 saat
define('PASSWORD_MIN_LENGTH', 8);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900); // 15 dakika

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
define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024); // 10MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('ALLOWED_DOCUMENT_TYPES', ['pdf', 'doc', 'docx', 'xls', 'xlsx']);
if (!defined('MAX_FILE_SIZE')) define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
if (!defined('ALLOWED_EXCEL_TYPES')) define('ALLOWED_EXCEL_TYPES', [
    'application/vnd.ms-excel',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'application/octet-stream'
]);

// Timezone ayarla
date_default_timezone_set(TIMEZONE);
