<?php
// DB sabitleri tanımla
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'vildacgg_portalv2');
define('DB_USER', 'vildacgg_tarihci20');
define('DB_PASS', 'C@rg_;NBXBu5');
define('DB_CHARSET', 'utf8mb4');

// PDO bağlantısı
try {
    $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    
    echo "Veritabanı bağlantısı başarılı<br><br>";
    
    // ALTER TABLE komutu
    $sql = "ALTER TABLE `vp_roles` 
            MODIFY `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY";
    
    echo "Çalıştırılacak SQL: <pre>$sql</pre><br>";
    
    $result = $pdo->exec($sql);
    
    echo "✓ Başarılı! vp_roles tablosu güncellenidi.<br>";
    echo "id kolonu şimdi AUTO_INCREMENT PRIMARY KEY'dir.<br>";
    
} catch (PDOException $e) {
    echo "❌ Hata: " . $e->getMessage();
}
?>
