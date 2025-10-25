<?php
/**
 * Permission System Debug Script
 * Production'da koşalıp durum kontrol etmek için
 * 
 * Erişim: https://portal.vildacgg.com/test-permissions-debug.php
 */

// Hataları göster (debug)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Veritabanı bağlantısı
try {
    // Vendor autoload'ı yükle
    require_once __DIR__ . '/../vendor/autoload.php';
    
    // Config yükle
    $rootPath = dirname(__DIR__);
    if (!defined('ROOT_PATH')) {
        define('ROOT_PATH', $rootPath);
    }
    
    // Constants'ları yükle
    $constantsLoaded = false;
    $constantsPaths = [
        ROOT_PATH . '/config/constants.php',
        '/home/vildacgg/vldn.in/portalv2/config/constants.php',
    ];
    
    foreach ($constantsPaths as $path) {
        if (file_exists($path)) {
            require_once $path;
            $constantsLoaded = true;
            break;
        }
    }
    
    if (!$constantsLoaded) {
        throw new Exception("Constants.php bulunamadı");
    }
    
    // Config'i yükle
    $configLoaded = false;
    $configPaths = [
        ROOT_PATH . '/config/config.php',
        '/home/vildacgg/vldn.in/portalv2/config/config.php',
    ];
    
    foreach ($configPaths as $path) {
        if (file_exists($path)) {
            require_once $path;
            $configLoaded = true;
            break;
        }
    }
    
    if (!$configLoaded) {
        throw new Exception("Config.php bulunamadı");
    }
    
    require_once ROOT_PATH . '/core/Database.php';
    
    $db = new \Core\Database();
    
    echo "<h1>Permission System Debug</h1>";
    echo "<pre>";
    
    // 1. Roller
    echo "\n=== ROLES ===\n";
    $db->query("SELECT id, role_name, display_name FROM vp_roles ORDER BY id");
    $roles = $db->resultSet();
    foreach ($roles as $role) {
        echo "Role {$role['id']}: {$role['role_name']} ({$role['display_name']})\n";
    }
    
    // 2. Sayfalar
    echo "\n=== PAGES ===\n";
    $db->query("SELECT id, page_name, page_key FROM vp_pages WHERE is_active = 1 ORDER BY id");
    $pages = $db->resultSet();
    foreach ($pages as $page) {
        echo "Page {$page['id']}: {$page['page_name']} ({$page['page_key']})\n";
    }
    
    // 3. Role-Page Permissions (kritik!)
    echo "\n=== ROLE-PAGE PERMISSIONS ===\n";
    $db->query("SELECT COUNT(*) as total FROM vp_role_page_permissions");
    $count = $db->single();
    echo "Total permission records: {$count['total']}\n";
    
    // 4. Her rol için permission sayısı
    echo "\nPermissions per role:\n";
    $db->query("SELECT role_id, COUNT(*) as perm_count FROM vp_role_page_permissions GROUP BY role_id ORDER BY role_id");
    $roleCounts = $db->resultSet();
    foreach ($roleCounts as $rc) {
        echo "  Role {$rc['role_id']}: {$rc['perm_count']} permissions\n";
    }
    
    // 5. Vice Principal (role 5) özel kontrol
    echo "\n=== VICE PRINCIPAL (Role 5) PERMISSIONS ===\n";
    $db->query("SELECT 
                    rpp.page_id, 
                    p.page_name, 
                    rpp.can_view, 
                    rpp.can_create, 
                    rpp.can_edit, 
                    rpp.can_delete
                FROM vp_role_page_permissions rpp
                LEFT JOIN vp_pages p ON rpp.page_id = p.id
                WHERE rpp.role_id = 5
                ORDER BY rpp.page_id");
    $vicePermissions = $db->resultSet();
    if (empty($vicePermissions)) {
        echo "❌ SORUN: Role 5 (Vice Principal) için hiçbir izin kaydı yok!\n";
    } else {
        echo "✅ Role 5 izinleri:\n";
        foreach ($vicePermissions as $vp) {
            $perms = [];
            if ($vp['can_view']) $perms[] = 'view';
            if ($vp['can_create']) $perms[] = 'create';
            if ($vp['can_edit']) $perms[] = 'edit';
            if ($vp['can_delete']) $perms[] = 'delete';
            echo "  Page {$vp['page_id']} ({$vp['page_name']}): " . implode(', ', $perms) . "\n";
        }
    }
    
    // 6. Etüt sayfaları
    echo "\n=== ETUT PAGES ===\n";
    $db->query("SELECT id, page_name, etut_type FROM vp_pages WHERE etut_type IS NOT NULL AND is_active = 1");
    $etutPages = $db->resultSet();
    echo "Found " . count($etutPages) . " etut pages\n";
    foreach ($etutPages as $ep) {
        echo "  Page {$ep['id']}: {$ep['page_name']} (type: {$ep['etut_type']})\n";
    }
    
    echo "\n</pre>";
    
} catch (\Exception $e) {
    echo "<h1 style='color: red;'>Database Error</h1>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}
?>
