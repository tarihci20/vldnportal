<?php
// DB sabitleri tanımla
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'vildacgg_portalv2');
define('DB_USER', 'vildacgg_tarihci20');
define('DB_PASS', 'C@rg_;NBXBu5');
define('DB_CHARSET', 'utf8mb4');

try {
    $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    
    echo "✓ Veritabanı bağlantısı başarılı<br><br>";
    
    // 1. vp_pages tablosuna etut_type kolonu ekle
    $sql1 = "ALTER TABLE `vp_pages` ADD COLUMN `etut_type` enum('all','ortaokul','lise') DEFAULT 'all' COMMENT 'etut_type: all=herkese, ortaokul=ortaokul müdür yardımcısı, lise=lise müdür yardımcısı'";
    
    try {
        $pdo->exec($sql1);
        echo "✓ vp_pages tablosuna etut_type kolonu eklendi<br>";
    } catch (PDOException $e) {
        echo "⚠️ vp_pages.etut_type zaten var veya hata: " . $e->getMessage() . "<br>";
    }
    
    // 2. vp_pages verilerini güncelle
    $sql2 = "UPDATE `vp_pages` SET `etut_type` = 'ortaokul' WHERE `page_key` = 'etut_ortaokul'";
    $pdo->exec($sql2);
    echo "✓ etut_ortaokul sayfası 'ortaokul' olarak işaretlendi<br>";
    
    $sql3 = "UPDATE `vp_pages` SET `etut_type` = 'lise' WHERE `page_key` = 'etut_lise'";
    $pdo->exec($sql3);
    echo "✓ etut_lise sayfası 'lise' olarak işaretlendi<br>";
    
    // 3. vp_users tablosuna etut_type kolonu ekle
    $sql4 = "ALTER TABLE `vp_users` ADD COLUMN `etut_type` enum('ortaokul','lise') DEFAULT NULL COMMENT 'Müdür yardımcısı için ortaokul/lise ayırımı'";
    
    try {
        $pdo->exec($sql4);
        echo "✓ vp_users tablosuna etut_type kolonu eklendi<br>";
    } catch (PDOException $e) {
        echo "⚠️ vp_users.etut_type zaten var veya hata: " . $e->getMessage() . "<br>";
    }
    
    echo "<br>✓ Tüm güncellemeler başarılı!";
    
} catch (PDOException $e) {
    echo "❌ Hata: " . $e->getMessage();
}
?>
