<?php
// Auto-load composer dependencies
require 'vendor/autoload.php';

// Load config
require 'config/database.php';

// Setup error handling
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Manual database connection
$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
$pdo = new PDO($dsn, DB_USER, DB_PASS);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "=== ROLE 5 (vice_principal) İZİN KONTROL ===\n\n";

$result = $pdo->query('
    SELECT 
        p.id,
        p.page_name,
        rp.can_view,
        rp.can_create,
        rp.can_edit,
        rp.can_delete
    FROM vp_pages p
    LEFT JOIN vp_role_page_permissions rp ON p.id = rp.page_id AND rp.role_id = 5
    WHERE p.is_active = 1
    ORDER BY p.id
')->fetchAll(PDO::FETCH_ASSOC);

foreach($result as $row) {
    $status = $row['can_view'] !== null ? "✓ SAVED" : "✗ EMPTY";
    echo sprintf(
        "ID %2d | %-40s | %s\n",
        $row['id'],
        substr($row['page_name'], 0, 40),
        $status
    );
}

echo "\n=== ÖZET ===\n";
$empty = array_filter($result, fn($r) => $r['can_view'] === null);
$saved = array_filter($result, fn($r) => $r['can_view'] !== null);

echo "Kaydedilen: " . count($saved) . "\n";
echo "Boş olan: " . count($empty) . "\n";

if (!empty($empty)) {
    echo "\nBoş olan sayfalar:\n";
    foreach($empty as $row) {
        echo "- ID {$row['id']}: {$row['page_name']}\n";
    }
}
