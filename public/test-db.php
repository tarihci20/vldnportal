<?php
// Test Database Connection
error_reporting(E_ALL);
ini_set('display_errors', 1);

// DB config
define('DB_HOST', 'localhost');
define('DB_NAME', 'vildacgg_portalv2');
define('DB_USER', 'vildacgg_tarihci20');
define('DB_PASS', 'vildacgg123'); // Bu şifreyi kontrol et!

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
    
    echo "<h1>✓ Database Bağlantısı Başarılı</h1>";
    
    // Rolleri listele
    echo "<h2>Mevcut Roller:</h2>";
    $stmt = $pdo->query("SELECT * FROM vp_roles");
    $roles = $stmt->fetchAll();
    echo "<pre>";
    print_r($roles);
    echo "</pre>";
    
    echo "<h2>Tablo Yapısı:</h2>";
    $stmt = $pdo->query("DESCRIBE vp_roles");
    $columns = $stmt->fetchAll();
    echo "<pre>";
    print_r($columns);
    echo "</pre>";
    
} catch (PDOException $e) {
    echo "<h1 style='color: red;'>✗ Database Bağlantı Hatası</h1>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Host: " . DB_HOST . "</p>";
    echo "<p>Database: " . DB_NAME . "</p>";
    echo "<p>User: " . DB_USER . "</p>";
}
?>

