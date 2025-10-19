<?php
/**
 * Quick Login Test
 */

session_start();

// Test için direkt admin olarak giriş yap
if (isset($_GET['auto_login'])) {
    try {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=portalv2", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Admin kullanıcısını getir
        $stmt = $pdo->prepare("SELECT * FROM users WHERE role_id = 1 LIMIT 1");
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin) {
            // Session'a kaydet
            $_SESSION['user_id'] = $admin['id'];
            $_SESSION['username'] = $admin['username'];
            $_SESSION['email'] = $admin['email'];
            $_SESSION['role_id'] = $admin['role_id'];
            $_SESSION['full_name'] = $admin['full_name'];
            $_SESSION['is_logged_in'] = true;
            
            echo "<h1>✅ Giriş Başarılı!</h1>";
            echo "<p>Admin olarak giriş yapıldı: " . $admin['username'] . "</p>";
            echo "<p><a href='simple-test.php'>Test Sayfasına Dön</a></p>";
            echo "<p><a href='/portalv2/activities'>Activities Sayfasına Git</a></p>";
            echo "<p><a href='/portalv2/activities/create'>Yeni Etkinlik Sayfasına Git</a></p>";
            exit;
        } else {
            echo "<h1>❌ Admin kullanıcı bulunamadı!</h1>";
            echo "<p>Veritabanında admin kullanıcı yok. users tablosunu kontrol edin.</p>";
        }
        
    } catch (PDOException $e) {
        echo "<h1>❌ Veritabanı Hatası</h1>";
        echo "<p>" . $e->getMessage() . "</p>";
    }
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Quick Login</title>
    <style>
        body { font-family: Arial; padding: 40px; background: #f5f5f5; text-align: center; }
        .box { background: white; padding: 30px; margin: 20px auto; border-radius: 8px; max-width: 500px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .btn { display: inline-block; padding: 15px 30px; background: #3b82f6; color: white; text-decoration: none; border-radius: 5px; margin: 10px; font-size: 16px; }
        .btn:hover { background: #2563eb; }
        .info { background: #e3f2fd; padding: 15px; border-radius: 5px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="box">
        <h1>🔐 Quick Login</h1>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="info">
                <h3>✅ Zaten Giriş Yapılmış</h3>
                <p><strong>User ID:</strong> <?= $_SESSION['user_id'] ?></p>
                <p><strong>Username:</strong> <?= $_SESSION['username'] ?? 'N/A' ?></p>
                <p><strong>Role ID:</strong> <?= $_SESSION['role_id'] ?? 'N/A' ?></p>
            </div>
            
            <a href="simple-test.php" class="btn">Test Sayfasına Git</a>
            <a href="/portalv2/activities/create" class="btn">Yeni Etkinlik</a>
            
            <hr style="margin: 30px 0;">
            
            <a href="?logout=1" class="btn" style="background: #ef4444;">Çıkış Yap</a>
            
        <?php else: ?>
            <p>Test için hızlı giriş yapın:</p>
            <a href="?auto_login=1" class="btn">Admin Olarak Giriş Yap</a>
            
            <div style="margin-top: 30px; text-align: left;">
                <h3>veya Normal Giriş:</h3>
                <p><a href="/portalv2/login">Normal Giriş Sayfasına Git</a></p>
            </div>
        <?php endif; ?>
    </div>
    
    <?php
    if (isset($_GET['logout'])) {
        session_destroy();
        header('Location: quick-login.php');
        exit;
    }
    ?>
</body>
</html>
