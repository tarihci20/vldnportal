<?php
/**
 * Permission System Debug Script
 * Production'da koşalıp durum kontrol etmek için
 * 
 * Erişim: https://portal.vildacgg.com/test-permissions-debug.php
 * 
 * ⚠️  PRODUCTION ONLY - TEST DOSYASI
 * Kurulum tamamlandıktan sonra SİLİNMELİDİR
 */

// Hataları göster (debug)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// HTML başlığı
echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1'>";
echo "<title>Permission System Debug</title>";
echo "<style>";
echo "body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f5f5; padding: 20px; }";
echo ".container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }";
echo "h1 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }";
echo "h2 { color: #555; margin-top: 30px; background: #f9f9f9; padding: 10px; border-left: 4px solid #007bff; }";
echo ".status { padding: 10px; margin: 10px 0; border-radius: 4px; }";
echo ".status.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }";
echo ".status.error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }";
echo ".status.warning { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }";
echo "table { width: 100%; border-collapse: collapse; margin: 10px 0; }";
echo "th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }";
echo "th { background: #f9f9f9; font-weight: 600; }";
echo "tr:hover { background: #f9f9f9; }";
echo "pre { background: #f4f4f4; padding: 10px; border-radius: 4px; overflow-x: auto; }";
echo "</style>";
echo "</head>";
echo "<body>";
echo "<div class='container'>";

// Veritabanı bağlantısı
try {
    echo "<h1>🔍 Permission System Diagnostics</h1>";
    echo "<p>System Status: <span class='status success'>Checking...</span></p>";
    
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
    echo "<div class='status success'>✅ Database Connection: OK</div>";
    
    // 1. Roller
    echo "<h2>📋 Roles</h2>";
    $db->query("SELECT id, role_name, display_name FROM vp_roles ORDER BY id");
    $roles = $db->resultSet();
    
    if (!empty($roles)) {
        echo "<table><tr><th>ID</th><th>Role Name</th><th>Display Name</th></tr>";
        foreach ($roles as $role) {
            echo "<tr><td>{$role['id']}</td><td>{$role['role_name']}</td><td>{$role['display_name']}</td></tr>";
        }
        echo "</table>";
    }
    
    // 2. Sayfalar
    echo "<h2>📄 Active Pages</h2>";
    $db->query("SELECT id, page_name, page_key, COALESCE(etut_type, 'normal') as type FROM vp_pages WHERE is_active = 1 ORDER BY id");
    $pages = $db->resultSet();
    
    if (!empty($pages)) {
        echo "<table><tr><th>ID</th><th>Page Name</th><th>Page Key</th><th>Type</th></tr>";
        foreach ($pages as $page) {
            $type = $page['type'] === 'normal' ? '📄' : '📚';
            echo "<tr><td>{$page['id']}</td><td>{$page['page_name']}</td><td>{$page['page_key']}</td><td>{$type} {$page['type']}</td></tr>";
        }
        echo "</table>";
    }
    
    // 3. Role-Page Permissions (kritik!)
    echo "<h2>🔐 Permission Records Summary</h2>";
    $db->query("SELECT COUNT(*) as total FROM vp_role_page_permissions");
    $count = $db->single();
    
    if ($count['total'] > 0) {
        echo "<div class='status success'>✅ Total permission records: <strong>{$count['total']}</strong></div>";
    } else {
        echo "<div class='status error'>❌ PROBLEM: Total permission records: <strong>0</strong></div>";
    }
    
    // 4. Her rol için permission sayısı
    echo "<h2>📊 Permissions per Role</h2>";
    $db->query("SELECT 
                    r.id,
                    r.display_name,
                    COALESCE(COUNT(rpp.role_id), 0) as perm_count 
               FROM vp_roles r
               LEFT JOIN vp_role_page_permissions rpp ON r.id = rpp.role_id
               GROUP BY r.id, r.display_name
               ORDER BY r.id");
    $roleCounts = $db->resultSet();
    
    echo "<table><tr><th>Role ID</th><th>Role Name</th><th>Permission Count</th><th>Status</th></tr>";
    foreach ($roleCounts as $rc) {
        $status = $rc['perm_count'] > 0 ? "✅ OK" : "❌ MISSING";
        $statusClass = $rc['perm_count'] > 0 ? "success" : "error";
        echo "<tr>";
        echo "<td>{$rc['id']}</td>";
        echo "<td>{$rc['display_name']}</td>";
        echo "<td><strong>{$rc['perm_count']}</strong></td>";
        echo "<td><span class='status {$statusClass}'>{$status}</span></td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // 5. Vice Principal (role 5) özel kontrol
    echo "<h2>⭐ Vice Principal (Role 5) - Detailed Check</h2>";
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
        echo "<div class='status error'>❌ PROBLEM: Role 5 (Vice Principal) has NO permissions!</div>";
        echo "<p>💡 <strong>Solution:</strong> Run the SQL setup from PRODUCTION-PERMISSION-DIAGNOSTICS.sql</p>";
    } else {
        echo "<div class='status success'>✅ Role 5 has " . count($vicePermissions) . " permission records</div>";
        echo "<table>";
        echo "<tr><th>Page ID</th><th>Page Name</th><th>View</th><th>Create</th><th>Edit</th><th>Delete</th></tr>";
        foreach ($vicePermissions as $vp) {
            $v = $vp['can_view'] ? '✓' : '✗';
            $c = $vp['can_create'] ? '✓' : '✗';
            $e = $vp['can_edit'] ? '✓' : '✗';
            $d = $vp['can_delete'] ? '✓' : '✗';
            echo "<tr><td>{$vp['page_id']}</td><td>{$vp['page_name']}</td><td>{$v}</td><td>{$c}</td><td>{$e}</td><td>{$d}</td></tr>";
        }
        echo "</table>";
    }
    
    // 6. Etüt sayfaları
    echo "<h2>📚 Etüt Pages Check</h2>";
    $db->query("SELECT id, page_name, etut_type FROM vp_pages WHERE etut_type IS NOT NULL AND is_active = 1");
    $etutPages = $db->resultSet();
    
    if (count($etutPages) > 0) {
        echo "<div class='status success'>✅ Found " . count($etutPages) . " etut pages</div>";
        echo "<table><tr><th>ID</th><th>Page Name</th><th>Type</th></tr>";
        foreach ($etutPages as $ep) {
            echo "<tr><td>{$ep['id']}</td><td>{$ep['page_name']}</td><td>{$ep['etut_type']}</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<div class='status error'>❌ No etut pages found!</div>";
    }
    
    echo "<hr style='margin: 30px 0;'>";
    echo "<p style='color: #666; font-size: 12px;'>Last Updated: " . date('Y-m-d H:i:s') . "</p>";
    
} catch (\Exception $e) {
    echo "<div class='status error'>";
    echo "<h2>❌ Error</h2>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    echo "</div>";
}

echo "</div>";
echo "</body>";
echo "</html>";
?>

    
    echo "<h1>🔍 Permission System Diagnostics</h1>";
    echo "<p>System Status: <span class='status success'>Checking...</span></p>";
    
    // Vendor autoload'ı yükle
