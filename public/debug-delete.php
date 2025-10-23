<?php
/**
 * Debug Delete User - Temporary debug page
 * Remove after debugging is complete
 */

// Başlangıçta error_reporting ve display_errors'ı aç
ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set content type
header('Content-Type: text/html; charset=UTF-8');

// Try to load config and helpers
$errors = [];
$config_loaded = false;
$helpers_loaded = false;

try {
    // Try different config paths
    $config_paths = [
        __DIR__ . '/../config/config.php',
        __DIR__ . '/../../../portalv2/config/config.php',
        __DIR__ . '/../../../config/config.php',
    ];
    
    foreach ($config_paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            $config_loaded = true;
            break;
        }
    }
    
    if (!$config_loaded) {
        $errors[] = "Config.php not found in any path";
    }
} catch (\Exception $e) {
    $errors[] = "Config Error: " . $e->getMessage();
}

try {
    if (file_exists(__DIR__ . '/../app/helpers/functions.php')) {
        require_once __DIR__ . '/../app/helpers/functions.php';
        $helpers_loaded = true;
    }
} catch (\Exception $e) {
    $errors[] = "Helpers Error: " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug - Kullanıcı Silme</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .section {
            margin: 20px 0;
            padding: 15px;
            background: #f9f9f9;
            border-left: 4px solid #007bff;
            border-radius: 4px;
        }
        .label {
            font-weight: bold;
            color: #333;
            display: inline-block;
            width: 150px;
        }
        .value {
            color: #666;
            font-family: 'Courier New', monospace;
            background: #fff;
            padding: 8px;
            border-radius: 3px;
            display: inline-block;
            margin-left: 10px;
        }
        .success {
            color: green;
            background: #e8f5e9;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .error {
            color: red;
            background: #ffebee;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .warning {
            color: orange;
            background: #fff3e0;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .test-form {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 4px;
            margin: 20px 0;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        button:hover {
            background: #0056b3;
        }
        pre {
            background: #f4f4f4;
            padding: 10px;
            border-radius: 4px;
            overflow-x: auto;
        }
        .info-box {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 15px;
            border-radius: 4px;
            margin: 10px 0;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>🔧 Debug - Kullanıcı Silme İşlemi</h1>
    
    <div class="info-box">
        <strong>⚠️ Uyarı:</strong> Bu sayfa yalnızca debug amaçlıdır. Production'da silinmelidir.
    </div>

    <?php
    // Show any loading errors
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo '<div class="warning">⚠️ ' . htmlspecialchars($error) . '</div>';
        }
    }

    $currentUser = $_SESSION['user'] ?? null;
    $userId = $_SESSION['user_id'] ?? null;

    echo '<div class="section">';
    echo '<h3>👤 Mevcut Kullanıcı</h3>';
    if ($userId) {
        echo '<div><span class="label">User ID:</span><span class="value">' . htmlspecialchars($userId) . '</span></div><br>';
        echo '<div><span class="label">Username:</span><span class="value">' . htmlspecialchars($currentUser['username'] ?? 'N/A') . '</span></div><br>';
        echo '<div><span class="label">Role:</span><span class="value">' . htmlspecialchars($currentUser['role'] ?? 'N/A') . '</span></div><br>';
        echo '<div><span class="label">Role Slug:</span><span class="value">' . htmlspecialchars($currentUser['role_slug'] ?? 'N/A') . '</span></div>';
    } else {
        echo '<div class="warning">⚠️ Giriş yapılmamış - Bazı özellikler sınırlı olacaktır</div>';
        echo '<div><a href="/login">Giriş Yap</a></div>';
    }
    echo '</div>';

    // Check if deleteUser handler exists
    echo '<div class="section">';
    echo '<h3>🛣️ Route Bilgisi</h3>';
    
    $routes = [
        'POST /admin/users/{id}/delete' => 'AdminController@deleteUser',
        'POST /admin/users/delete' => 'AdminController@deleteUser (old)',
        'POST /api/users/{id}/delete' => 'AdminController@deleteUser (api)'
    ];
    
    foreach ($routes as $route => $handler) {
        echo '<div><span class="label">Route:</span><span class="value">' . htmlspecialchars($route) . ' → ' . htmlspecialchars($handler) . '</span></div>';
    }
    echo '</div>';

    // Test delete request
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_delete'])) {
        echo '<div class="section">';
        echo '<h3>📝 Test İsteği Gönderiliyor...</h3>';
        
        $testUserId = $_POST['test_user_id'] ?? null;
        
        if (!$testUserId) {
            echo '<div class="error">❌ Kullanıcı ID boş!</div>';
        } else if ($testUserId == $userId) {
            echo '<div class="error">❌ Kendi hesabınızı silemezsiniz!</div>';
        } else {
            // Generate CSRF token
            $csrfToken = $_SESSION['csrf_token'] ?? '';
            
            echo '<div class="success">✅ POST İsteği Test Modu</div>';
            echo '<pre>';
            echo "URL: /admin/users/{$testUserId}/delete\n";
            echo "Method: POST\n";
            echo "Body: " . json_encode(['id' => $testUserId, 'csrf_token' => $csrfToken]) . "\n";
            echo '</pre>';
            
            echo '<p><strong>CURL Komutu:</strong></p>';
            echo '<pre>';
            echo "curl -X POST 'https://vldn.in/admin/users/{$testUserId}/delete' \\
  -H 'Content-Type: application/json' \\
  -H 'Cookie: PHPSESSID=" . session_id() . "' \\
  -d '{\"id\": {$testUserId}, \"csrf_token\": \"{$csrfToken}\"}'";
            echo '</pre>';
        }
        echo '</div>';
    }

    // Form to test delete
    echo '<div class="test-form">';
    echo '<h3>🧪 Test İsteği Gönder</h3>';
    echo '<form method="POST">';
    echo '<div>';
    echo '<label>Silinecek Kullanıcı ID:</label><br>';
    echo '<input type="number" name="test_user_id" min="1" required style="padding: 8px; width: 200px;">';
    echo '</div><br>';
    echo '<button type="submit" name="test_delete">Test İsteği Gönder</button>';
    echo '</form>';
    echo '</div>';

    // CSRF Token info
    echo '<div class="section">';
    echo '<h3>🔐 CSRF Token Bilgisi</h3>';
    echo '<div><span class="label">Token:</span><span class="value">' . substr($_SESSION['csrf_token'] ?? '', 0, 20) . '...</span></div><br>';
    echo '<div><span class="label">Token Time:</span><span class="value">' . ($_SESSION['csrf_token_time'] ?? 'N/A') . '</span></div><br>';
    echo '<div><span class="label">Current Time:</span><span class="value">' . time() . '</span></div>';
    echo '</div>';

    // Database check
    echo '<div class="section">';
    echo '<h3>🗄️ Database Kontrol</h3>';
    
    if (defined('DB_HOST') && defined('DB_USER') && defined('DB_PASS') && defined('DB_NAME')) {
        try {
            require_once __DIR__ . '/../core/Database.php';
            $db = \Core\Database::getInstance();
            
            // Check vp_users table
            $result = $db->query("SELECT COUNT(*) as count FROM vp_users")->single();
            echo '<div class="success">✅ vp_users tablosu erişilebilir (' . ($result['count'] ?? 0) . ' kullanıcı)</div>';
            
            // List users
            echo '<p><strong>Kullanıcılar:</strong></p>';
            echo '<pre>';
            $users = $db->query("SELECT id, username, email, role_id FROM vp_users LIMIT 10")->resultSet();
            foreach ($users as $user) {
                echo "ID: {$user['id']} | Username: {$user['username']} | Email: {$user['email']} | Role: {$user['role_id']}\n";
            }
            echo '</pre>';
        } catch (\Exception $e) {
            echo '<div class="error">❌ Database Hatası: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    } else {
        echo '<div class="error">❌ Database Constants tanımlanmamış:</div>';
        echo '<pre>';
        echo "DB_HOST: " . (defined('DB_HOST') ? 'DEFINED' : 'NOT DEFINED') . "\n";
        echo "DB_USER: " . (defined('DB_USER') ? 'DEFINED' : 'NOT DEFINED') . "\n";
        echo "DB_PASS: " . (defined('DB_PASS') ? 'DEFINED' : 'NOT DEFINED') . "\n";
        echo "DB_NAME: " . (defined('DB_NAME') ? 'DEFINED' : 'NOT DEFINED') . "\n";
        echo '</pre>';
    }
    echo '</div>';

    // Session info
    echo '<div class="section">';
    echo '<h3>📦 Session Verisi</h3>';
    echo '<pre>';
    foreach ($_SESSION as $key => $value) {
        if (is_array($value)) {
            echo "$key: " . json_encode($value) . "\n";
        } else {
            echo "$key: " . htmlspecialchars((string)$value) . "\n";
        }
    }
    echo '</pre>';
    echo '</div>';

    // API Test
    echo '<div class="section">';
    echo '<h3>🌐 JavaScript Test</h3>';
    echo '<textarea id="console" style="width: 100%; height: 150px; font-family: monospace; padding: 10px; border: 1px solid #ccc; border-radius: 4px;" readonly></textarea>';
    echo '<br><br>';
    echo '<button type="button" onclick="testDelete()">Test Delete API</button>';
    echo '</div>';

    ?>

</div>

<script>
async function testDelete() {
    const console_el = document.getElementById('console');
    console_el.value = '';
    
    const log = (msg) => {
        console_el.value += msg + '\n';
        console_el.scrollTop = console_el.scrollHeight;
    };

    try {
        const userId = 3; // Test user ID
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 'test-token';
        
        log('🚀 DELETE İsteği Gönderiliyor...');
        log('URL: /admin/users/' + userId + '/delete');
        log('Body: {"id": ' + userId + ', "csrf_token": "' + csrfToken.substring(0, 20) + '..."}');
        log('');
        
        const response = await fetch(`/admin/users/${userId}/delete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: userId,
                csrf_token: csrfToken
            })
        });

        log(`📊 Status: ${response.status} ${response.statusText}`);
        log('Headers:');
        for (const [key, value] of response.headers.entries()) {
            log(`  ${key}: ${value}`);
        }
        log('');

        const contentType = response.headers.get('content-type');
        let data;
        if (contentType && contentType.includes('application/json')) {
            data = await response.json();
            log('✅ Response (JSON):');
            log(JSON.stringify(data, null, 2));
        } else {
            const text = await response.text();
            log('📄 Response (Text):');
            log(text);
        }
    } catch (error) {
        log('❌ Error: ' + error.message);
        log(error.stack);
    }
}
</script>

</body>
</html>
