<?php
/**
 * Vildan Portal - Veritabanı Yapılandırması
 * Path: /home/vildacgg/vldn.in/portalv2/config/database.php
 * * NOT: Bu bilgileri cPanel'den MySQL Databases bölümünden alın
 */

return [
    'driver' => 'mysql',
    'host' => 'localhost', // Genellikle localhost
    'port' => 3306, // MySQL varsayılan portu (EKLENDİ)
    
    // ⚠️ BURAYA KENDİ BİLGİLERİNİZİ YAZIN
    'database' => 'vildacgg_portalv2', // Örnek: vildacgg_portal
    'username' => 'vildacgg_tarihci20', // Örnek: vildacgg_portal_user
    'password' => 'C@rg_;NBXBu5', // Güvenli bir şifre
    
    // Karakter seti
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    
    // Tablo prefix
    'prefix' => 'vp_',
    
    // PDO options
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
    ],
    
    // Connection pool
    'persistent' => false,
    
    // Timezone
    'timezone' => '+03:00', // Türkiye saati
];
