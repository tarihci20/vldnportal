#!/usr/bin/env php
<?php
/**
 * Production'a Ortaokul ve Lise Etüt Sayfaları Eklemek İçin
 * 
 * Bu script, production veritabanında etut_ortaokul ve etut_lise sayfalarını ekler.
 * Terminal'de şu komutla çalıştırın:
 * php scripts/add-etut-pages.php
 */

// Basepath'i ayarla
define('BASE_PATH', __DIR__ . '/..');

// Database config'i yükle
$dbConfig = include BASE_PATH . '/config/database.php';

// database.php dosyasını kontrol et
if (!file_exists(BASE_PATH . '/config/database.php')) {
    echo "[HATA] config/database.php dosyası bulunamadı!\n";
    exit(1);
}

// config/config.php dosyasını kontrol et
if (!file_exists(BASE_PATH . '/config/config.php')) {
    echo "[HATA] config/config.php dosyası bulunamadı!\n";
    exit(1);
}

// Konfigürasyonları yükle
$config = include BASE_PATH . '/config/config.php';
$dbConfig = include BASE_PATH . '/config/database.php';

$dbHost = $config['db_host'] ?? 'localhost';
$dbName = $config['db_name'] ?? 'vildacgg_portalv2';
$dbUser = $config['db_user'] ?? 'root';
$dbPass = $config['db_pass'] ?? '';

try {
    $pdo = new PDO(
        'mysql:host=' . $dbHost . ';dbname=' . $dbName . ';charset=utf8mb4',
        $dbUser,
        $dbPass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "[OK] Veritabanına bağlandı: $dbName\n";
    
    // Önce etut_area (parent) sayfasının var olup olmadığını kontrol et
    $stmt = $pdo->prepare("SELECT id FROM vp_pages WHERE page_key = ?");
    $stmt->execute(['etut_area']);
    $parentPage = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$parentPage) {
        echo "[HATA] Etüt Alanı (etut_area) sayfası bulunamadı!\n";
        exit(1);
    }
    
    $parentId = $parentPage['id'];
    echo "[OK] Parent Sayfa Bulundu (ID: $parentId)\n";
    
    // Ortaokul Etüt sayfasını ekle
    $stmt = $pdo->prepare("INSERT IGNORE INTO vp_pages (page_key, page_name, page_url, parent_id, sort_order, is_active, etut_type, created_at) 
                           VALUES (?, ?, ?, ?, ?, 1, ?, NOW())");
    $result1 = $stmt->execute(['etut_ortaokul', 'Ortaokul Etüt', '/etut/ortaokul', $parentId, 1, 'ortaokul']);
    
    if ($result1) {
        echo "[OK] Ortaokul Etüt sayfası eklendi/zaten vardı\n";
    } else {
        echo "[HATA] Ortaokul Etüt sayfası eklenirken hata oluştu\n";
    }
    
    // Lise Etüt sayfasını ekle
    $stmt = $pdo->prepare("INSERT IGNORE INTO vp_pages (page_key, page_name, page_url, parent_id, sort_order, is_active, etut_type, created_at) 
                           VALUES (?, ?, ?, ?, ?, 1, ?, NOW())");
    $result2 = $stmt->execute(['etut_lise', 'Lise Etüt', '/etut/lise', $parentId, 2, 'lise']);
    
    if ($result2) {
        echo "[OK] Lise Etüt sayfası eklendi/zaten vardı\n";
    } else {
        echo "[HATA] Lise Etüt sayfası eklenirken hata oluştu\n";
    }
    
    // Kontrol et - kaç sayfa eklendiyse söyle
    $stmt = $pdo->prepare("SELECT * FROM vp_pages WHERE page_key IN (?, ?)");
    $stmt->execute(['etut_ortaokul', 'etut_lise']);
    $pages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\n[SONUÇ] Veritabanında şu Etüt Sayfaları var:\n";
    foreach ($pages as $page) {
        echo "  - [{$page['id']}] {$page['page_name']} ({$page['page_key']}) - Type: {$page['etut_type']}\n";
    }
    
    echo "\n✅ İşlem başarıyla tamamlandı!\n";
    
} catch (PDOException $e) {
    echo "[HATA] Veritabanı Hatası: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "[HATA] Hata: " . $e->getMessage() . "\n";
    exit(1);
}
?>
