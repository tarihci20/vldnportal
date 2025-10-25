<?php
/**
 * SIMPLE Permission Test
 */
session_name('app_session');
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Permission Test</title>
    <style>
        body { font-family: Arial; max-width: 1000px; margin: 20px auto; padding: 20px; }
        .ok { color: green; font-weight: bold; }
        .fail { color: red; font-weight: bold; }
        .warn { color: orange; font-weight: bold; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #4CAF50; color: white; }
        h2 { border-bottom: 2px solid #333; padding-bottom: 5px; }
        pre { background: #f4f4f4; padding: 10px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>🔍 Permission System Test</h1>
    <p>Test Time: <?= date('Y-m-d H:i:s') ?></p>

    <?php
    // Test 1: Sidebar dosyası
    echo "<h2>1. Sidebar Kodu</h2>";
    $sidebarPath = __DIR__ . '/../app/views/layouts/sidebar.php';
    
    if (file_exists($sidebarPath)) {
        $content = file_get_contents($sidebarPath);
        $modTime = date('Y-m-d H:i:s', filemtime($sidebarPath));
        
        echo "<p><strong>Dosya son değişiklik:</strong> $modTime</p>";
        
        if (strpos($content, "hasPermission('etut', 'can_view')") !== false) {
            echo "<p class='fail'>❌ ESKİ KOD! (hasPermission('etut', 'can_view') bulundu)</p>";
        } elseif (strpos($content, "hasPermission('etut-ortaokul', 'can_view') || hasPermission('etut-lise', 'can_view')") !== false) {
            echo "<p class='ok'>✅ YENİ KOD! (Commit 68a715e9 çalışıyor)</p>";
        } else {
            echo "<p class='warn'>⚠️ Beklenmeyen kod yapısı</p>";
        }
        
        // Etüt bölümünü göster
        preg_match('/<!-- Etüt.*?endif; \?>/s', $content, $matches);
        if (!empty($matches)) {
            echo "<h3>Etüt Menü Kodu:</h3>";
            echo "<pre>" . htmlspecialchars(substr($matches[0], 0, 500)) . "...</pre>";
        }
    } else {
        echo "<p class='fail'>❌ Sidebar dosyası bulunamadı!</p>";
    }

    // Test 2: Session
    echo "<h2>2. Session Durumu</h2>";
    if (isset($_SESSION['user_id'])) {
        echo "<p class='ok'>✅ Login olmuşsunuz</p>";
        echo "<table>";
        foreach (['user_id', 'username', 'role_id', 'role_name'] as $key) {
            if (isset($_SESSION[$key])) {
                echo "<tr><td><strong>$key</strong></td><td>" . htmlspecialchars($_SESSION[$key]) . "</td></tr>";
            }
        }
        echo "</table>";
    } else {
        echo "<p class='fail'>❌ Login değilsiniz!</p>";
        echo "<p>Lütfen emine ile login olun ve bu sayfayı tekrar açın.</p>";
    }

    // Test 3: Database
    echo "<h2>3. Database</h2>";
    try {
        $configPath = __DIR__ . '/../config/database.php';
        if (file_exists($configPath)) {
            require_once $configPath;
            
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            echo "<p class='ok'>✅ Database bağlantısı başarılı</p>";
            
            // Sayfa sayısı
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM vp_pages");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['total'] == 11) {
                echo "<p class='ok'>✅ vp_pages: {$result['total']} sayfa (DOĞRU!)</p>";
            } else {
                echo "<p class='fail'>❌ vp_pages: {$result['total']} sayfa (11 olmalıydı!)</p>";
            }
            
            // Sayfalar
            echo "<h3>Sayfa Listesi:</h3>";
            $stmt = $pdo->query("SELECT page_key, page_name FROM vp_pages ORDER BY sort_order");
            echo "<table><tr><th>#</th><th>page_key</th><th>page_name</th></tr>";
            $i = 1;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr><td>$i</td><td>{$row['page_key']}</td><td>{$row['page_name']}</td></tr>";
                $i++;
            }
            echo "</table>";
            
            // emine permissions
            echo "<h3>emine Kullanıcısı Permissions:</h3>";
            $stmt = $pdo->prepare("
                SELECT u.id, u.username, u.role_id, r.role_name 
                FROM vp_users u 
                JOIN vp_roles r ON r.id = u.role_id 
                WHERE u.username = 'emine'
            ");
            $stmt->execute();
            $emine = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($emine) {
                echo "<p class='ok'>✅ emine bulundu (Role: {$emine['role_name']})</p>";
                
                $stmt = $pdo->prepare("
                    SELECT p.page_key, p.page_name, 
                           rpp.can_view, rpp.can_create, rpp.can_edit, rpp.can_delete
                    FROM vp_role_page_permissions rpp
                    JOIN vp_pages p ON p.id = rpp.page_id
                    WHERE rpp.role_id = :role_id
                    ORDER BY p.sort_order
                ");
                $stmt->execute(['role_id' => $emine['role_id']]);
                $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo "<table>";
                echo "<tr><th>page_key</th><th>page_name</th><th>V</th><th>C</th><th>E</th><th>D</th></tr>";
                foreach ($permissions as $perm) {
                    echo "<tr>";
                    echo "<td>{$perm['page_key']}</td>";
                    echo "<td>{$perm['page_name']}</td>";
                    echo "<td class='" . ($perm['can_view'] ? 'ok' : 'fail') . "'>" . ($perm['can_view'] ? '✓' : '✗') . "</td>";
                    echo "<td class='" . ($perm['can_create'] ? 'ok' : 'fail') . "'>" . ($perm['can_create'] ? '✓' : '✗') . "</td>";
                    echo "<td class='" . ($perm['can_edit'] ? 'ok' : 'fail') . "'>" . ($perm['can_edit'] ? '✓' : '✗') . "</td>";
                    echo "<td class='" . ($perm['can_delete'] ? 'ok' : 'fail') . "'>" . ($perm['can_delete'] ? '✓' : '✗') . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                
                if (count($permissions) == 11) {
                    echo "<p class='ok'>✅ 11 permission var (DOĞRU!)</p>";
                } else {
                    echo "<p class='fail'>❌ " . count($permissions) . " permission var (11 olmalıydı!)</p>";
                }
            } else {
                echo "<p class='fail'>❌ emine kullanıcısı bulunamadı!</p>";
            }
            
        } else {
            echo "<p class='fail'>❌ database.php bulunamadı!</p>";
        }
    } catch (Exception $e) {
        echo "<p class='fail'>❌ Hata: " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }

    // Test 4: hasPermission() fonksiyonu
    echo "<h2>4. hasPermission() Fonksiyon Testi</h2>";
    
    $helperPath = __DIR__ . '/../app/helpers/session.php';
    if (file_exists($helperPath) && isset($_SESSION['user_id'])) {
        require_once $helperPath;
        
        $testPages = [
            'dashboard', 'student-search', 'students', 'activities', 
            'activity-areas', 'etut-ortaokul', 'etut-lise', 
            'reports', 'users', 'roles', 'settings'
        ];
        
        echo "<table><tr><th>page_key</th><th>hasPermission('can_view')</th></tr>";
        foreach ($testPages as $pageKey) {
            $result = hasPermission($pageKey, 'can_view');
            $class = $result ? 'ok' : 'fail';
            $text = $result ? '✅ TRUE' : '❌ FALSE';
            echo "<tr><td>$pageKey</td><td class='$class'>$text</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='warn'>⚠️ hasPermission() test edilemedi (session.php yok veya login değilsiniz)</p>";
    }
    ?>

    <h2>📌 SONUÇ</h2>
    <ul>
        <li>Sidebar kodu <strong>YENİ</strong> olmalı ✅</li>
        <li>Database'de <strong>11 sayfa</strong> olmalı ✅</li>
        <li>emine'nin <strong>11 permission</strong>'ı olmalı ✅</li>
        <li>hasPermission() <strong>hepsi TRUE</strong> olmalı ✅</li>
    </ul>
    
    <p><strong>Eğer hepsi ✅ ama sidebar yine yanlışsa:</strong></p>
    <ul>
        <li>Browser cache temizle (Ctrl+Shift+R)</li>
        <li>Logout → Login tekrar yap</li>
        <li>Farklı browser dene</li>
    </ul>

</body>
</html>
