<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/config.php';

// Database bağlantısı
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Tablo Bilgileri: vp_pages</h2>";
    
    // Table character set kontrol et
    $stmt = $pdo->query("SHOW CREATE TABLE vp_pages");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<pre>";
    echo htmlspecialchars($row['Create Table']);
    echo "</pre>";
    
    // Sayfa adlarını al
    echo "<h2>Sayfa Adları (Raw)</h2>";
    $stmt = $pdo->query("SELECT id, page_name FROM vp_pages LIMIT 5");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: " . $row['id'] . " | Name: " . htmlspecialchars($row['page_name']) . "<br>";
    }
    
    // JSON encode test
    echo "<h2>JSON Encode Test</h2>";
    $stmt = $pdo->query("SELECT page_name FROM vp_pages WHERE id = 3 LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $json = json_encode($row, JSON_UNESCAPED_UNICODE);
    echo "<pre>" . htmlspecialchars($json) . "</pre>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
