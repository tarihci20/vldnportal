<?php
/**
 * Activities Create Diagnostic
 */

// Bootstrap
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/config.php';

// Helpers
foreach (glob(__DIR__ . '/../app/helpers/*.php') as $helper) {
    require_once $helper;
}

// Core
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../core/Auth.php';

// Start session (use helper function)
startSession();
\Core\Auth::loadUserFromSession();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Activities Create Diagnostic</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .box { background: white; padding: 20px; margin: 10px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .success { border-left: 4px solid #10b981; }
        .error { border-left: 4px solid #ef4444; }
        .warning { border-left: 4px solid #f59e0b; }
        h2 { margin-top: 0; color: #333; }
        pre { background: #f9fafb; padding: 10px; border-radius: 4px; overflow-x: auto; }
        .btn { display: inline-block; padding: 10px 20px; background: #3b82f6; color: white; text-decoration: none; border-radius: 5px; margin: 5px; }
        .btn:hover { background: #2563eb; }
    </style>
</head>
<body>
    <h1>🔍 Activities Create Sayfası Diagnostic</h1>
    
    <div class="box <?= isLoggedIn() ? 'success' : 'error' ?>">
        <h2>1. Oturum Durumu</h2>
        <?php if (isLoggedIn()): ?>
            <p>✅ Kullanıcı giriş yapmış</p>
            <pre><?php print_r([
                'user_id' => getCurrentUserId(),
                'username' => auth('username'),
                'role_id' => getUserSession('role_id'),
                'is_admin' => isAdmin(),
                'is_teacher' => isTeacher()
            ]); ?></pre>
        <?php else: ?>
            <p>❌ Kullanıcı giriş yapmamış</p>
            <p><a href="/portalv2/login" class="btn">Giriş Yap</a></p>
        <?php endif; ?>
    </div>
    
    <?php if (isLoggedIn()): ?>
    
    <div class="box <?= hasPermission('activities', 'can_view') ? 'success' : 'error' ?>">
        <h2>2. Activities İzni (View)</h2>
        <?php if (hasPermission('activities', 'can_view')): ?>
            <p>✅ Kullanıcı activities sayfasını görebilir</p>
        <?php else: ?>
            <p>❌ Kullanıcı activities sayfasını görme izni yok</p>
        <?php endif; ?>
    </div>
    
    <div class="box <?= hasPermission('activities', 'can_create') ? 'success' : 'error' ?>">
        <h2>3. Activities İzni (Create)</h2>
        <?php if (hasPermission('activities', 'can_create')): ?>
            <p>✅ Kullanıcı yeni etkinlik oluşturabilir</p>
        <?php else: ?>
            <p>❌ Kullanıcı yeni etkinlik oluşturma izni yok</p>
            <p><strong>Sorun:</strong> Bu izin olmadan "Yeni Etkinlik" butonu görünmez veya çalışmaz.</p>
        <?php endif; ?>
    </div>
    
    <?php
    try {
        require_once __DIR__ . '/../core/Model.php';
        require_once __DIR__ . '/../app/models/Activity.php';
        require_once __DIR__ . '/../app/models/ActivityArea.php';
        
        $activityModel = new App\Models\Activity();
        $areaModel = new App\Models\ActivityArea();
        
        $slots = $activityModel->getAllTimeSlots();
        $areas = $areaModel->where(['is_active' => 1]);
        
        $modelSuccess = true;
    } catch (Exception $e) {
        $modelSuccess = false;
        $modelError = $e->getMessage();
    }
    ?>
    
    <div class="box <?= $modelSuccess ? 'success' : 'error' ?>">
        <h2>4. Activity Model Test</h2>
        <?php if ($modelSuccess): ?>
            <p>✅ Activity model çalışıyor</p>
            <ul>
                <li>Toplam saat dilimleri: <?= count($slots) ?></li>
                <li>Aktif etkinlik alanları: <?= count($areas) ?></li>
            </ul>
        <?php else: ?>
            <p>❌ Activity model hatası</p>
            <pre><?= $modelError ?? 'Bilinmeyen hata' ?></pre>
        <?php endif; ?>
    </div>
    
    <div class="box <?= file_exists(__DIR__ . '/../app/controllers/ActivityController.php') ? 'success' : 'error' ?>">
        <h2>5. Controller Dosyası</h2>
        <?php if (file_exists(__DIR__ . '/../app/controllers/ActivityController.php')): ?>
            <p>✅ ActivityController.php mevcut</p>
        <?php else: ?>
            <p>❌ ActivityController.php bulunamadı!</p>
        <?php endif; ?>
    </div>
    
    <div class="box">
        <h2>6. Test Linkleri</h2>
        <p>Eğer yukarıdaki tüm testler ✅ işaretliyse, şu linkleri deneyin:</p>
        <a href="/portalv2/activities" class="btn">Activities Liste</a>
        <a href="/portalv2/activities/create" class="btn">Yeni Etkinlik (GET)</a>
        <form method="GET" action="/portalv2/activities/create" style="display:inline;">
            <button type="submit" class="btn" style="border:none;cursor:pointer;">Yeni Etkinlik (FORM)</button>
        </form>
    </div>
    
    <div class="box warning">
        <h2>7. Hata Logları</h2>
        <?php
        $errorLog = __DIR__ . '/../storage/logs/error.log';
        if (file_exists($errorLog)) {
            $errors = file_get_contents($errorLog);
            $lines = explode("\n", $errors);
            $recent = array_slice($lines, -20);
            echo "<pre>" . implode("\n", $recent) . "</pre>";
        } else {
            echo "<p>Log dosyası bulunamadı</p>";
        }
        ?>
    </div>
    
    <?php endif; ?>
</body>
</html>
