<?php
/**
 * TEST: User Deletion - Complete Test Script
 * 
 * This file tests the complete user deletion flow
 * 1. Creates a test session with CSRF token
 * 2. Shows the token to verify it exists
 * 3. Allows testing deletion with proper CSRF token
 * 4. Shows all debug information
 * 
 * Instructions:
 * 1. First login to the application normally
 * 2. Go to /admin/users 
 * 3. Then run this script to see debug info
 */

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: text/html; charset=UTF-8');

// Get root path (parent of public directory)
$rootPath = dirname(__DIR__);

// Load helpers FIRST - need csrf_token() function
$helpersPath = $rootPath . '/app/helpers/functions.php';
if (!file_exists($helpersPath)) {
    die("❌ Helpers not found at " . $helpersPath);
}
require_once $helpersPath;

// Generate CSRF token if not exists
csrf_token();

// Load config
$configPath = $rootPath . '/config/config.php';
if (!file_exists($configPath)) {
    die("❌ Config not found at " . $configPath);
}
require_once $configPath;

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?? '' ?>">
    <title>Test - Kullanıcı Silme</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #007bff;
            padding-bottom: 15px;
        }
        h2 {
            color: #555;
            margin-top: 25px;
            border-left: 4px solid #007bff;
            padding-left: 15px;
        }
        .section {
            margin: 20px 0;
            padding: 15px;
            background: #f9f9f9;
            border-left: 4px solid #007bff;
            border-radius: 4px;
        }
        .row {
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 15px;
            margin: 10px 0;
            align-items: start;
        }
        .label {
            font-weight: bold;
            color: #333;
        }
        .value {
            color: #666;
            font-family: 'Courier New', monospace;
            background: #fff;
            padding: 8px 12px;
            border-radius: 3px;
            word-break: break-all;
            border: 1px solid #ddd;
        }
        .success {
            color: green;
            background: #e8f5e9;
            padding: 12px;
            border-radius: 4px;
            margin: 10px 0;
            border-left: 4px solid green;
        }
        .error {
            color: red;
            background: #ffebee;
            padding: 12px;
            border-radius: 4px;
            margin: 10px 0;
            border-left: 4px solid red;
        }
        .warning {
            color: orange;
            background: #fff3e0;
            padding: 12px;
            border-radius: 4px;
            margin: 10px 0;
            border-left: 4px solid orange;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin: 5px;
        }
        button:hover {
            background: #0056b3;
        }
        button:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        pre {
            background: #f4f4f4;
            padding: 12px;
            border-radius: 4px;
            overflow-x: auto;
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #ddd;
            font-size: 12px;
        }
        .input-group {
            display: flex;
            gap: 10px;
            margin: 10px 0;
        }
        input[type="number"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            width: 100px;
        }
        textarea {
            width: 100%;
            height: 200px;
            font-family: monospace;
            font-size: 12px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .info-box {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 15px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>🧪 Test - Kullanıcı Silme İşlemi</h1>
    
    <div class="info-box">
        <strong>ℹ️ Bilgi:</strong> Bu sayfa kullanıcı silme işlemini test etmek için kullanılır.
        Silmek istediğiniz kullanıcının ID'sini girin ve Test Butonu'na basın.
    </div>

    <?php
    // Check if user is logged in
    $isLoggedIn = isset($_SESSION['user_id']);
    $userId = $_SESSION['user_id'] ?? null;
    $userRole = $_SESSION['user']['role'] ?? null;
    $csrfToken = getCsrfToken();
    
    echo '<div class="section">';
    echo '<h2>👤 Oturum Bilgisi</h2>';
    
    if ($isLoggedIn) {
        echo '<div class="success">✅ Oturum Açık - Test yapabilirsiniz</div>';
        echo '<div class="row">';
        echo '<div class="label">Kullanıcı ID:</div>';
        echo '<div class="value">' . htmlspecialchars($userId) . '</div>';
        echo '</div>';
        echo '<div class="row">';
        echo '<div class="label">Rol:</div>';
        echo '<div class="value">' . htmlspecialchars($userRole) . '</div>';
        echo '</div>';
    } else {
        echo '<div class="error">❌ Oturum Kapalı - Giriş yapmalısınız</div>';
        echo '<a href="' . url('/login') . '" style="color: #007bff; text-decoration: underline;">Giriş Yap</a>';
        exit;
    }
    
    echo '</div>';
    
    // CSRF Token Information
    echo '<div class="section">';
    echo '<h2>🔐 CSRF Token Bilgisi</h2>';
    
    if ($csrfToken) {
        echo '<div class="success">✅ CSRF Token Başarıyla Oluşturuldu</div>';
        echo '<div class="row">';
        echo '<div class="label">Token (İlk 50):</div>';
        echo '<div class="value">' . substr($csrfToken, 0, 50) . '...</div>';
        echo '</div>';
        echo '<div class="row">';
        echo '<div class="label">Token Uzunluğu:</div>';
        echo '<div class="value">' . strlen($csrfToken) . ' karakter</div>';
        echo '</div>';
        echo '<div class="row">';
        echo '<div class="label">Token Oluşturma Zamanı:</div>';
        echo '<div class="value">' . date('Y-m-d H:i:s', $_SESSION['csrf_token_time']) . '</div>';
        echo '</div>';
    } else {
        echo '<div class="error">❌ CSRF Token Bulunamadı!</div>';
    }
    
    echo '</div>';
    
    // Database Connection Test
    echo '<div class="section">';
    echo '<h2>🗄️ Veritabanı Bağlantısı</h2>';
    
    try {
        if (defined('DB_HOST') && defined('DB_USER') && defined('DB_PASS') && defined('DB_NAME')) {
            echo '<div class="success">✅ Database Constants Tanımlanmış</div>';
            
            require_once __DIR__ . '/core/Database.php';
            $db = \Core\Database::getInstance();
            
            $result = $db->query("SELECT COUNT(*) as count FROM vp_users")->single();
            echo '<div class="success">✅ Veritabanı Bağlantısı OK - ' . ($result['count'] ?? 0) . ' kullanıcı vardır</div>';
        } else {
            echo '<div class="error">❌ Database Constants Tanımlanmamış</div>';
        }
    } catch (\Exception $e) {
        echo '<div class="error">❌ Database Hatası: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
    
    echo '</div>';
    
    // Delete Test Section
    echo '<div class="section">';
    echo '<h2>🧪 Silme Test</h2>';
    echo '<p>Test etmek istediğiniz kullanıcının ID\'sini girin:</p>';
    echo '<div class="input-group">';
    echo '<input type="number" id="deleteUserId" placeholder="Kullanıcı ID" min="1">';
    echo '<button onclick="testDelete()">Test Gönder</button>';
    echo '</div>';
    echo '<div id="testResults" style="display: none;">';
    echo '<p><strong>Test Sonuçları:</strong></p>';
    echo '<textarea id="testOutput" readonly></textarea>';
    echo '</div>';
    echo '</div>';
    
    // Available Users List
    echo '<div class="section">';
    echo '<h2>📋 Mevcut Kullanıcılar</h2>';
    
    try {
        $db = \Core\Database::getInstance();
        $users = $db->query("
            SELECT id, username, email, role_id, is_active 
            FROM vp_users 
            ORDER BY id DESC 
            LIMIT 20
        ")->resultSet();
        
        if (!empty($users)) {
            echo '<table border="1" cellpadding="10" style="width:100%; border-collapse: collapse;">';
            echo '<thead style="background: #f0f0f0;">';
            echo '<tr>';
            echo '<th>ID</th>';
            echo '<th>Kullanıcı Adı</th>';
            echo '<th>E-posta</th>';
            echo '<th>Rol ID</th>';
            echo '<th>Durum</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            
            foreach ($users as $user) {
                echo '<tr>';
                echo '<td>' . $user['id'] . '</td>';
                echo '<td>' . htmlspecialchars($user['username']) . '</td>';
                echo '<td>' . htmlspecialchars($user['email']) . '</td>';
                echo '<td>' . $user['role_id'] . '</td>';
                echo '<td>' . ($user['is_active'] ? 'Aktif' : 'Pasif') . '</td>';
                echo '</tr>';
            }
            
            echo '</tbody>';
            echo '</table>';
        }
    } catch (\Exception $e) {
        echo '<div class="error">Kullanıcı listesi alınamadı: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
    
    echo '</div>';
    
    ?>

</div>

<script>
async function testDelete() {
    const userId = document.getElementById('deleteUserId').value;
    const resultsDiv = document.getElementById('testResults');
    const output = document.getElementById('testOutput');
    
    if (!userId) {
        alert('Lütfen bir kullanıcı ID\'si girin');
        return;
    }
    
    resultsDiv.style.display = 'block';
    output.value = 'Test başlatılıyor...\n';
    
    try {
        const csrfToken = '<?= $csrfToken ?>';
        
        output.value += `✓ CSRF Token alındı (${csrfToken.substring(0, 20)}...)\n`;
        output.value += `✓ Kullanıcı ID: ${userId}\n\n`;
        output.value += `📤 POST İsteği Gönderiliyor...\n`;
        output.value += `URL: /admin/users/${userId}/delete\n`;
        output.value += `CSRF Token: ${csrfToken.substring(0, 30)}...\n\n`;
        
        const response = await fetch(`/admin/users/${userId}/delete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: userId,
                csrf_token: csrfToken
            })
        });
        
        output.value += `📥 Cevap Alındı\n`;
        output.value += `Status: ${response.status} ${response.statusText}\n\n`;
        
        const data = await response.json();
        output.value += `Response JSON:\n`;
        output.value += JSON.stringify(data, null, 2) + '\n\n';
        
        if (data.success) {
            output.value += '✅ ŞİRKET BAŞARILI - Sayfa 3 saniye sonra yenilenecek\n';
            setTimeout(() => window.location.reload(), 3000);
        } else {
            output.value += '❌ Silme Başarısız: ' + (data.message || 'Bilinmeyen hata') + '\n';
        }
    } catch (error) {
        output.value += `❌ HATA: ${error.message}\n`;
        output.value += `Stack: ${error.stack}\n`;
    }
}
</script>

</body>
</html>
