<?php
// Test Database Connection
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

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
    echo "<pre>";
    print_r($stmt->fetchAll());
    echo "</pre>";
    
    // Basit INSERT test
    echo "<h2>INSERT Test:</h2>";
    try {
        $stmt = $pdo->prepare("INSERT INTO vp_roles (role_name, display_name, description, is_active) VALUES (?, ?, ?, ?)");
        $result = $stmt->execute(['test_role_' . time(), 'Test Rol', 'Test açıklama', 1]);
        
        if ($result) {
            $lastId = $pdo->lastInsertId();
            echo "<p style='color: green;'>✓ INSERT başarılı! Oluşturulan ID: " . $lastId . "</p>";
            
            // Hemen sil
            $pdo->query("DELETE FROM vp_roles WHERE id = " . $lastId);
            echo "<p>Test verisi silindi.</p>";
        } else {
            echo "<p style='color: red;'>✗ INSERT başarısız</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ INSERT Hata: " . $e->getMessage() . "</p>";
    }
    
} catch (PDOException $e) {
    echo "<h1 style='color: red;'>✗ Database Bağlantı Hatası</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>
