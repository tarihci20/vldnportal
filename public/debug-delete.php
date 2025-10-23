<?php
/**
 * Debug Delete User - Temporary debug page
 * Remove after debugging is complete
 * 
 * Place this file in the root of portalv2 project
 */

// Başlangıçta error_reporting ve display_errors'ı aç
ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get root path (parent of public directory)
$rootPath = dirname(__DIR__);

// Load helpers FIRST to have access to csrf_token()
$helpersPath = $rootPath . '/app/helpers/functions.php';
if (file_exists($helpersPath)) {
    require_once $helpersPath;
} else {
    die("Helpers not found at " . $helpersPath);
}

// Now generate CSRF token if not exists
// This ensures $_SESSION['csrf_token'] is populated
csrf_token();

// Set content type
header('Content-Type: text/html; charset=UTF-8');

// Try to load config and helpers
$errors = [];
$config_loaded = false;
$helpers_loaded = true;  // Already loaded above

// Load config from config directory
$configPath = $rootPath . '/config/config.php';
if (file_exists($configPath)) {
    require_once $configPath;
    $config_loaded = true;
} else {
    $errors[] = "Config.php not found at " . $configPath;
}

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?? '' ?>">
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
        h3 {
            color: #555;
            margin-top: 20px;
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
            word-break: break-all;
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
            max-height: 300px;
            overflow-y: auto;
        }
        .info-box {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 15px;
            border-radius: 4px;
            margin: 10px 0;
        }
        textarea {
            width: 100%;
            height: 200px;
            font-family: monospace;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
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

    // Show config load status
    echo '<div class="section">';
    echo '<h3>📋 Yükleme Durumu</h3>';
    echo '<div><span class="label">Config Loaded:</span><span class="value ' . ($config_loaded ? 'success' : 'error') . '">' . ($config_loaded ? 'YES ✅' : 'NO ❌') . '</span></div><br>';
    echo '<div><span class="label">Helpers Loaded:</span><span class="value ' . ($helpers_loaded ? 'success' : 'error') . '">' . ($helpers_loaded ? 'YES ✅' : 'NO ❌') . '</span></div>';
    echo '</div>';

    $currentUser = $_SESSION['user'] ?? null;
    $userId = $_SESSION['user_id'] ?? null;

    echo '<div class="section">';
    echo '<h3>👤 Mevcut Kullanıcı</h3>';
    if ($userId) {
        echo '<div class="success">✅ Giriş yapılmış</div>';
        echo '<div><span class="label">User ID:</span><span class="value">' . htmlspecialchars($userId) . '</span></div><br>';
        echo '<div><span class="label">Username:</span><span class="value">' . htmlspecialchars($currentUser['username'] ?? 'N/A') . '</span></div><br>';
        echo '<div><span class="label">Role:</span><span class="value">' . htmlspecialchars($currentUser['role'] ?? 'N/A') . '</span></div><br>';
        echo '<div><span class="label">Role Slug:</span><span class="value">' . htmlspecialchars($currentUser['role_slug'] ?? 'N/A') . '</span></div>';
    } else {
        echo '<div class="warning">⚠️ Giriş yapılmamış - CSRF token alınamaz</div>';
        echo '<div style="margin-top: 10px;"><a href="/portalv2/login">🔐 Giriş Yap</a></div>';
    }
    echo '</div>';

    // CSRF Token info
    echo '<div class="section">';
    echo '<h3>🔐 CSRF Token Bilgisi</h3>';
    $csrfToken = $_SESSION['csrf_token'] ?? '';
    if ($csrfToken) {
        echo '<div class="success">✅ CSRF Token bulundu</div>';
        echo '<div><span class="label">Token:</span><span class="value">' . substr($csrfToken, 0, 30) . '...</span></div>';
    } else {
        echo '<div class="error">❌ CSRF Token bulunamadı</div>';
    }
    echo '<div><span class="label">Token Time:</span><span class="value">' . ($_SESSION['csrf_token_time'] ?? 'N/A') . '</span></div><br>';
    echo '<div><span class="label">Current Time:</span><span class="value">' . time() . '</span></div>';
    echo '</div>';

    // Database check
    echo '<div class="section">';
    echo '<h3>🗄️ Database Kontrol</h3>';
    
    $dbDefined = defined('DB_HOST') && defined('DB_USER') && defined('DB_PASS') && defined('DB_NAME');
    
    if ($dbDefined) {
        echo '<div class="success">✅ Database Constants tanımlanmış</div>';
        try {
            $dbPath = $rootPath . '/core/Database.php';
            require_once $dbPath;
            $db = \Core\Database::getInstance();
            
            // Check vp_users table
            $result = $db->query("SELECT COUNT(*) as count FROM vp_users")->single();
            echo '<div class="success">✅ vp_users tablosu erişilebilir (' . ($result['count'] ?? 0) . ' kullanıcı)</div>';
            
            // List users
            echo '<p><strong>Kullanıcılar (İlk 10):</strong></p>';
            echo '<pre>';
            $users = $db->query("SELECT id, username, email, role_id FROM vp_users LIMIT 10")->resultSet();
            foreach ($users as $user) {
                echo "ID: {$user['id']} | Username: {$user['username']} | Email: {$user['email']} | Role: {$user['role_id']}\n";
            }
            echo '</pre>';
        } catch (\Exception $e) {
            echo '<div class="error">❌ Database Bağlantı Hatası: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    } else {
        echo '<div class="error">❌ Database Constants tanımlanmamış</div>';
        echo '<pre>';
        echo "DB_HOST: " . (defined('DB_HOST') ? 'DEFINED' : 'NOT DEFINED') . "\n";
        echo "DB_USER: " . (defined('DB_USER') ? 'DEFINED' : 'NOT DEFINED') . "\n";
        echo "DB_PASS: " . (defined('DB_PASS') ? 'DEFINED' : 'NOT DEFINED') . "\n";
        echo "DB_NAME: " . (defined('DB_NAME') ? 'DEFINED' : 'NOT DEFINED') . "\n";
        echo '</pre>';
    }
    echo '</div>';

    // API Test
    echo '<div class="section">';
    echo '<h3>🌐 Test Delete API</h3>';
    echo '<p>Test user ID 3\'ü silmeyi dene:</p>';
    echo '<textarea id="console" readonly></textarea>';
    echo '<br><br>';
    echo '<button type="button" onclick="testDelete()">▶️ Test Delete API Çalıştır</button>';
    echo '</div>';

    // Routes info
    echo '<div class="section">';
    echo '<h3>🛣️ Route Bilgisi</h3>';
    $routes = [
        'POST /admin/users/{id}/delete' => 'AdminController@deleteUser',
        'POST /admin/users/delete' => 'AdminController@deleteUser (old)',
    ];
    foreach ($routes as $route => $handler) {
        echo '<div><span class="label">Route:</span><span class="value">' . htmlspecialchars($route) . ' → ' . htmlspecialchars($handler) . '</span></div>';
    }
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
        const userId = 3; // Test user ID (admin hesabını silmeyin!)
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        
        log('🚀 DELETE İsteği Gönderiliyor...');
        log('URL: /admin/users/' + userId + '/delete');
        log('Method: POST');
        log('CSRF Token: ' + (csrfToken ? csrfToken.substring(0, 20) + '...' : 'BOŞŞ ⚠️'));
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
        log('');
        log('Headers:');
        for (const [key, value] of response.headers.entries()) {
            log(`  ${key}: ${value.substring(0, 50)}`);
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
            log(text.substring(0, 500));
        }
    } catch (error) {
        log('❌ Network Error: ' + error.message);
    }
}
</script>

</body>
</html>
