<?php
/**
 * Permission save flow test
 * Bu script, izin kaydetme akışındaki tüm adımları ve log dosyasının nerede yazıldığını bulur
 */

// Setup
define('ROOT_PATH', __DIR__);
define('LOG_PATH', dirname(__DIR__) . '/storage/logs');

// Test 1: error_log çıktısı nereye gidiyor?
echo "=== TEST 1: Error Log Yolu Testi ===\n";
echo "ini_get('error_log'): " . ini_get('error_log') . "\n";
echo "ini_get('log_errors'): " . ini_get('log_errors') . "\n";
echo "ROOT_PATH: " . ROOT_PATH . "\n";
echo "LOG_PATH: " . LOG_PATH . "\n";

// Test 2: Test error_log
echo "\n=== TEST 2: Error Log Yazma Testi ===\n";
error_log("=== TEST ERROR LOG - " . date('Y-m-d H:i:s') . " ===");
error_log("updateRolePermissions called: roleId=5, permissions count=2");
error_log("Test başarılı!");
echo "error_log() çağrıldı. Aşağıdaki dosyaları kontrol et:\n";

// Olası log dosyası yolları
$possibleLogFiles = [
    '/storage/logs/error.log',
    '/storage/logs/php_error.log',
    ini_get('error_log'),
    '/tmp/php_errors.log',
    '/var/log/php_errors.log',
    '/home/vildacgg/vldn.in/portalv2/storage/logs/php_error.log',
    '/home/vildacgg/vldn.in/portalv2/storage/logs/error.log',
    '/home/vildacgg/public_html/portalv2/storage/logs/php_error.log',
    '/home/vildacgg/vldn.in/error_log',
    '/home/vildacgg/public_html/error_log',
];

foreach ($possibleLogFiles as $logFile) {
    if ($logFile && file_exists($logFile)) {
        echo "\n✓ BULUNDU: $logFile\n";
        echo "  Son 5 satır:\n";
        $lines = array_slice(file($logFile), -5);
        foreach ($lines as $line) {
            echo "  " . trim($line) . "\n";
        }
    }
}

// Test 3: AdminController'ın saveUserPermissions() methodunu test et
echo "\n=== TEST 3: AdminController Permission Save Flow ===\n";
require_once ROOT_PATH . '/vendor/autoload.php';
require_once ROOT_PATH . '/config/constants.php';
require_once ROOT_PATH . '/core/Database.php';

try {
    // Database connect - production credentials
    $db = new Database(
        'localhost',
        'vildacgg_portalv2',
        'vildacgg_tarihci20',
        'C@rg_;NBXBu5',
        3306
    );
    
    // Test: Tüm role permissions'ları kontrol et
    echo "\nSu anda vp_role_page_permissions tablosundaki kayıtlar:\n";
    $result = $db->query("SELECT role_id, page_id, can_view, can_create, can_edit, can_delete FROM vp_role_page_permissions ORDER BY role_id DESC LIMIT 20");
    $permissions = $db->resultSet();
    
    if ($permissions) {
        foreach ($permissions as $perm) {
            echo "Role: {$perm['role_id']}, Page: {$perm['page_id']}, View:{$perm['can_view']}, Create:{$perm['can_create']}, Edit:{$perm['can_edit']}, Delete:{$perm['can_delete']}\n";
        }
    } else {
        echo "Hiç permission kaydı yok!\n";
    }
    
    // Role 5 (vice_principal) için page 12 ve 13 kontrol et
    echo "\n\nRole 5 ve Pages 12, 13 için kontrol:\n";
    $result = $db->query("SELECT * FROM vp_role_page_permissions WHERE role_id = 5 AND page_id IN (12, 13)");
    $permsForRole5 = $db->resultSet();
    
    if ($permsForRole5) {
        echo "BULUNDU: " . count($permsForRole5) . " kayıt\n";
        foreach ($permsForRole5 as $perm) {
            echo json_encode($perm, JSON_UNESCAPED_UNICODE) . "\n";
        }
    } else {
        echo "BULUNAMADI: Role 5 için page 12 ve 13'e ait hiç permission yok!\n";
    }
    
} catch (Exception $e) {
    echo "Hata: " . $e->getMessage() . "\n";
}

echo "\n=== TEST TAMAMLANDI ===\n";
?>
