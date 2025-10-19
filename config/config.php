<?php
/**
 * Vildan Portal - Ana Yapılandırma
 * Path: /home/vildacgg/vldn.in/portalv2/config/config.php
 */

return [
    // ============================================================
    // UYGULAMA BİLGİLERİ
    // ============================================================
    'app_name' => 'Vildan Portal',
    'app_url' => 'https://vldn.in/portalv2',
    'base_path' => '/portalv2',
    'base_url' => 'https://vldn.in/portalv2',
    'environment' => 'production', // development, production
    'debug' => false, // Production'da mutlaka false olmalı!

    // ============================================================
    // YOLLAR (cPanel Production)
    // ============================================================
    'root_path' => '/home/vildacgg/vldn.in/portalv2',
    'public_path' => '/home/vildacgg/vldn.in/portalv2/public',
    'storage_path' => '/home/vildacgg/vldn.in/portalv2/storage',
    'upload_path' => '/home/vildacgg/vldn.in/portalv2/public/assets/uploads',

    // URL'ler
    'assets_url' => 'https://vldn.in/portalv2/public/assets',
    'upload_url' => 'https://vldn.in/portalv2/public/assets/uploads',

    // ============================================================
    // SESSION
    // ============================================================
    'session_lifetime' => 7200, // 2 saat (saniye)
    'session_name' => 'vildan_portal_session',
    'session_path' => '/portalv2',

    // ============================================================
    // ZAMAN VE DİL
    // ============================================================
    'timezone' => 'Europe/Istanbul',
    'locale' => 'tr_TR',

    // ============================================================
    // SAYFALAMA
    // ============================================================
    'per_page' => 20,
    'max_per_page' => 100,

    // ============================================================
    // DOSYA YÜKLEME
    // ============================================================
    'max_upload_size' => 10 * 1024 * 1024, // 10MB
    'allowed_image_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
    'allowed_document_types' => ['pdf', 'doc', 'docx', 'xls', 'xlsx'],

    // ============================================================
    // GÜVENLİK
    // ============================================================
    'csrf_token_name' => 'csrf_token',
    'csrf_token_lifetime' => 3600, // 1 saat
    'password_min_length' => 8,
    'max_login_attempts' => 5,
    'login_lockout_time' => 900, // 15 dakika

    // ============================================================
    // EMAIL (PHPMailer)
    // ============================================================
    'mail_from_address' => 'noreply@vldn.in',
    'mail_from_name' => 'Vildan Portal',

    // ============================================================
    // LOGGING
    // ============================================================
    'log_level' => 'error', // debug, info, warning, error
    'log_path' => '/home/vildacgg/vldn.in/portalv2/storage/logs',

    // ============================================================
    // CACHE
    // ============================================================
    'cache_enabled' => true,
    'cache_lifetime' => 3600, // 1 saat
    'cache_path' => '/home/vildacgg/vldn.in/portalv2/storage/cache',

    // ============================================================
    // BAKIM MODU
    // ============================================================
    'maintenance_mode' => false,
    'maintenance_allowed_ips' => [], // Bakım modunda izin verilen IP'ler
];