<?php
/**
 * Fix missing etüt permissions for vice_principal role
 * This script adds permissions for pages 11, 12, 13 to role 5 (vice_principal)
 */

// Determine the root path
$rootPath = dirname(__DIR__);

// Load configuration
$dbConfig = require_once $rootPath . '/config/database.php';

// Create PDO connection manually to avoid any framework issues
$dsn = "mysql:host=" . $dbConfig['host'] . ";dbname=" . $dbConfig['database'] . ";charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    echo "✓ Veritabanı bağlantısı başarılı\n\n";
    
    // Pages to fix: 11, 12, 13
    $pagesToFix = [11, 12, 13];
    $roleId = 5; // vice_principal
    
    $pageNames = [
        11 => 'Etüt Form Ayarları',
        12 => 'Ortaokul Etüt Başvuruları',
        13 => 'Lise Etüt Başvuruları'
    ];
    
    echo "=== EKSIK İZİNLER EKLENIYOR ===\n";
    
    foreach ($pagesToFix as $pageId) {
        // Check if permission already exists
        $checkStmt = $pdo->prepare('
            SELECT id FROM vp_role_page_permissions 
            WHERE role_id = ? AND page_id = ?
        ');
        $checkStmt->execute([$roleId, $pageId]);
        $exists = $checkStmt->fetch();
        
        if ($exists) {
            echo "✓ Sayfa {$pageId} ({$pageNames[$pageId]}) - Zaten kaydedilmiş\n";
            continue;
        }
        
        // Insert permission
        $insertStmt = $pdo->prepare('
            INSERT INTO vp_role_page_permissions 
            (role_id, page_id, can_view, can_create, can_edit, can_delete, created_at, updated_at)
            VALUES (?, ?, 1, 1, 1, 1, NOW(), NOW())
        ');
        
        $insertStmt->execute([$roleId, $pageId]);
        echo "✓ Sayfa {$pageId} ({$pageNames[$pageId]}) - İzin eklendi\n";
    }
    
    echo "\n=== SONUÇ KONTROL ===\n";
    
    $stmt = $pdo->prepare('
        SELECT 
            p.id,
            p.page_name,
            rp.can_view,
            rp.can_create,
            rp.can_edit,
            rp.can_delete
        FROM vp_pages p
        LEFT JOIN vp_role_page_permissions rp ON p.id = rp.page_id AND rp.role_id = ?
        WHERE p.id IN (11, 12, 13)
        ORDER BY p.id
    ');
    
    $stmt->execute([$roleId]);
    $results = $stmt->fetchAll();
    
    foreach ($results as $row) {
        $status = $row['can_view'] ? '✓ Kaydedildi' : '✗ Hala boş';
        echo sprintf(
            "ID %2d | %-40s | %s\n",
            $row['id'],
            $row['page_name'],
            $status
        );
    }
    
    echo "\n✓ İşlem tamamlandı. Admin panelini yenileyiniz.\n";
    
} catch (Exception $e) {
    echo "✗ Hata: " . $e->getMessage() . "\n";
    exit(1);
}
