<?php
/**
 * PERMISSION SYSTEM TEST - Production Debug
 * Test URL: https://vldn.in/portalv2/permission-test.php
 */

// Session başlat
session_start();

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permission System Test</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 10px;
        }
        h2 {
            color: #666;
            margin-top: 30px;
            border-left: 4px solid #2196F3;
            padding-left: 10px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #28a745;
            margin: 10px 0;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #dc3545;
            margin: 10px 0;
        }
        .warning {
            background: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #ffc107;
            margin: 10px 0;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #17a2b8;
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #4CAF50;
            color: white;
        }
        tr:hover {
            background: #f5f5f5;
        }
        .check-ok {
            color: #28a745;
            font-weight: bold;
        }
        .check-fail {
            color: #dc3545;
            font-weight: bold;
        }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
        pre {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
        }
        .timestamp {
            color: #999;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Permission System Debug Test</h1>
        <p class="timestamp">Test Time: <?= date('Y-m-d H:i:s') ?></p>

        <?php
        // Database bağlantısı
        try {
            require_once __DIR__ . '/../vendor/autoload.php';
            require_once __DIR__ . '/../config/database.php';

            use Core\Database;

            $db = Database::getInstance();
        } catch (Exception $e) {
            echo "<div class='error'>❌ Database bağlantı hatası: " . $e->getMessage() . "</div>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
            die();
        }

        // Test 1: Git Deployment Kontrolü
        echo "<h2>1️⃣ Git Deployment Kontrolü</h2>";
        
        $sidebarPath = __DIR__ . '/app/views/layouts/sidebar.php';
        if (file_exists($sidebarPath)) {
            $sidebarContent = file_get_contents($sidebarPath);
            
            // Eski kod var mı kontrol et
            if (strpos($sidebarContent, "hasPermission('etut', 'can_view')") !== false) {
                echo "<div class='error'>❌ ESKİ KOD TESPİT EDİLDİ! sidebar.php güncellenmemiş!</div>";
                echo "<p>SFTP deployment çalışmamış olabilir. Manuel kontrol gerekli.</p>";
            } else if (strpos($sidebarContent, "hasPermission('etut-ortaokul', 'can_view') || hasPermission('etut-lise', 'can_view')") !== false) {
                echo "<div class='success'>✅ YENİ KOD ÇALIŞIYOR! Sidebar güncel (Commit 68a715e9)</div>";
            } else {
                echo "<div class='warning'>⚠️ Kod yapısı beklenenden farklı</div>";
            }
            
            // Dosya değişiklik zamanı
            echo "<div class='info'>";
            echo "📅 sidebar.php son değişiklik: <strong>" . date('Y-m-d H:i:s', filemtime($sidebarPath)) . "</strong><br>";
            echo "🕐 Şu anki zaman: <strong>" . date('Y-m-d H:i:s') . "</strong>";
            echo "</div>";
        }

        // Test 2: Session Kontrolü
        echo "<h2>2️⃣ Session Durumu</h2>";
        
        if (isset($_SESSION['user_id'])) {
            echo "<div class='success'>✅ Aktif session var</div>";
            echo "<table>";
            echo "<tr><th>Key</th><th>Value</th></tr>";
            foreach ($_SESSION as $key => $value) {
                if (!is_array($value) && !is_object($value)) {
                    echo "<tr><td><code>$key</code></td><td>" . htmlspecialchars($value) . "</td></tr>";
                }
            }
            echo "</table>";
        } else {
            echo "<div class='warning'>⚠️ Aktif session yok - Login olmamışsınız</div>";
        }

        // Test 3: Database - vp_pages
        echo "<h2>3️⃣ Database: vp_pages Kontrolü</h2>";
        
        $sql = "SELECT COUNT(*) as total FROM vp_pages";
        $db->query($sql);
        $result = $db->single();
        
        echo "<div class='info'>📊 Toplam sayfa sayısı: <strong>{$result->total}</strong></div>";
        
        if ($result->total == 11) {
            echo "<div class='success'>✅ 11 sayfa var (DOĞRU!)</div>";
        } else {
            echo "<div class='error'>❌ Sayfa sayısı {$result->total} (11 olmalıydı!)</div>";
        }

        // Sayfa listesi
        $sql = "SELECT page_key, page_name FROM vp_pages ORDER BY sort_order";
        $db->query($sql);
        $pages = $db->resultSet();
        
        echo "<table>";
        echo "<tr><th>#</th><th>page_key</th><th>page_name</th></tr>";
        $i = 1;
        foreach ($pages as $page) {
            echo "<tr><td>$i</td><td><code>{$page->page_key}</code></td><td>{$page->page_name}</td></tr>";
            $i++;
        }
        echo "</table>";

        // Test 4: emine kullanıcısı permissions
        echo "<h2>4️⃣ emine Kullanıcısı Permission Kontrolü</h2>";
        
        $sql = "SELECT u.id, u.username, u.role_id, r.role_name 
                FROM vp_users u 
                JOIN vp_roles r ON r.id = u.role_id 
                WHERE u.username = 'emine'";
        $db->query($sql);
        $emine = $db->single();
        
        if ($emine) {
            echo "<div class='success'>✅ emine kullanıcısı bulundu</div>";
            echo "<table>";
            echo "<tr><th>User ID</th><td>{$emine->id}</td></tr>";
            echo "<tr><th>Username</th><td>{$emine->username}</td></tr>";
            echo "<tr><th>Role ID</th><td>{$emine->role_id}</td></tr>";
            echo "<tr><th>Role Name</th><td>{$emine->role_name}</td></tr>";
            echo "</table>";
            
            // Permission detayları
            $sql = "SELECT p.page_key, p.page_name, rpp.can_view, rpp.can_create, rpp.can_edit, rpp.can_delete
                    FROM vp_role_page_permissions rpp
                    JOIN vp_pages p ON p.id = rpp.page_id
                    WHERE rpp.role_id = :role_id
                    ORDER BY p.sort_order";
            $db->query($sql);
            $db->bind(':role_id', $emine->role_id);
            $permissions = $db->resultSet();
            
            echo "<h3>📋 Permission Detayları</h3>";
            echo "<table>";
            echo "<tr><th>page_key</th><th>page_name</th><th>View</th><th>Create</th><th>Edit</th><th>Delete</th></tr>";
            
            foreach ($permissions as $perm) {
                echo "<tr>";
                echo "<td><code>{$perm->page_key}</code></td>";
                echo "<td>{$perm->page_name}</td>";
                echo "<td class='" . ($perm->can_view ? 'check-ok' : 'check-fail') . "'>" . ($perm->can_view ? '✓' : '✗') . "</td>";
                echo "<td class='" . ($perm->can_create ? 'check-ok' : 'check-fail') . "'>" . ($perm->can_create ? '✓' : '✗') . "</td>";
                echo "<td class='" . ($perm->can_edit ? 'check-ok' : 'check-fail') . "'>" . ($perm->can_edit ? '✓' : '✗') . "</td>";
                echo "<td class='" . ($perm->can_delete ? 'check-ok' : 'check-fail') . "'>" . ($perm->can_delete ? '✓' : '✗') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            if (count($permissions) == 11) {
                echo "<div class='success'>✅ 11 permission var - DOĞRU!</div>";
            } else {
                echo "<div class='error'>❌ " . count($permissions) . " permission var (11 olmalıydı!)</div>";
            }
        } else {
            echo "<div class='error'>❌ emine kullanıcısı bulunamadı!</div>";
        }

        // Test 5: hasPermission() Fonksiyonu Test
        echo "<h2>5️⃣ hasPermission() Fonksiyon Testi</h2>";
        
        if (file_exists(__DIR__ . '/../app/helpers/session.php')) {
            require_once __DIR__ . '/../app/helpers/session.php';
        } else {
            echo "<div class='error'>❌ session.php bulunamadı!</div>";
        }
        
        if (isset($_SESSION['user_id'])) {
            $testPages = [
                'dashboard', 'student-search', 'students', 'activities', 
                'activity-areas', 'etut-ortaokul', 'etut-lise', 
                'reports', 'users', 'roles', 'settings'
            ];
            
            echo "<table>";
            echo "<tr><th>page_key</th><th>hasPermission() Sonucu</th></tr>";
            
            foreach ($testPages as $pageKey) {
                $result = hasPermission($pageKey, 'can_view');
                $class = $result ? 'check-ok' : 'check-fail';
                $icon = $result ? '✅ TRUE' : '❌ FALSE';
                echo "<tr><td><code>$pageKey</code></td><td class='$class'>$icon</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<div class='warning'>⚠️ Session yok, hasPermission() test edilemiyor</div>";
        }

        // Test 6: PHP Opcache Kontrolü
        echo "<h2>6️⃣ PHP Opcache Durumu</h2>";
        
        if (function_exists('opcache_get_status')) {
            $opcache = opcache_get_status();
            if ($opcache && $opcache['opcache_enabled']) {
                echo "<div class='warning'>⚠️ Opcache AKTİF - Cache problemi olabilir!</div>";
                echo "<div class='info'>";
                echo "💡 <strong>Çözüm:</strong> Hosting panel'den 'Clear PHP Opcache' yapın<br>";
                echo "veya sunucuyu restart edin.";
                echo "</div>";
            } else {
                echo "<div class='success'>✅ Opcache kapalı</div>";
            }
        } else {
            echo "<div class='info'>ℹ️ Opcache bilgisi alınamadı</div>";
        }

        ?>

        <h2>🎯 SONUÇ ve ÖNERİLER</h2>
        <div class='info'>
            <strong>Bu sayfayı emine ile login olduktan sonra tekrar ziyaret edin:</strong><br>
            <code>https://vldn.in/portalv2/permission-test.php</code>
            <br><br>
            <strong>Yapılacaklar:</strong>
            <ol>
                <li>emine ile login olun</li>
                <li>Bu test sayfasını açın</li>
                <li>Tüm testlerin ✅ olduğunu doğrulayın</li>
                <li>Eğer sidebar.php eski kodsa → SFTP deploy problemi var</li>
                <li>Eğer opcache aktifse → Cache temizleyin</li>
            </ol>
        </div>

    </div>
</body>
</html>
