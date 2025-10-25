<?php
/**
 * FAZA 1: Mevcut Roller ve İzinler Analizi
 * 
 * Bu script:
 * 1. Tüm rolleri listeler
 * 2. Her rol için sayfa izin istatistiklerini gösterir
 * 3. Access rules'ları belirler
 * 4. Missing permissions'ları listeler
 */

require_once dirname(__DIR__) . '/config/database.php';

try {
    $dbConfig = require_once dirname(__DIR__) . '/config/database.php';
    $dsn = "mysql:host=" . $dbConfig['host'] . ";dbname=" . $dbConfig['database'] . ";charset=utf8mb4";
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    echo "\n";
    echo "╔════════════════════════════════════════════════════════════════╗\n";
    echo "║  FAZA 1: MEVCUT ROLLER VE İZİNLER ANALİZİ                      ║\n";
    echo "╚════════════════════════════════════════════════════════════════╝\n\n";
    
    // ============================================================
    // 1. TÜM ROLLER
    // ============================================================
    echo "📋 1. TÜM ROLLER\n";
    echo "─────────────────────────────────────────────────────────────────\n";
    
    $stmt = $pdo->query('SELECT id, role_name, display_name FROM vp_roles ORDER BY id');
    $roles = $stmt->fetchAll();
    
    foreach ($roles as $role) {
        echo sprintf("[%d] %s (%s)\n", $role['id'], $role['display_name'], $role['role_name']);
    }
    
    // ============================================================
    // 2. TÜM SAYFALAR
    // ============================================================
    echo "\n📄 2. TÜM SAYFALAR (ACTIVE)\n";
    echo "─────────────────────────────────────────────────────────────────\n";
    
    $stmt = $pdo->query('SELECT id, page_name, etut_type, is_active FROM vp_pages WHERE is_active = 1 ORDER BY id');
    $pages = $stmt->fetchAll();
    
    $etutPages = [];
    $normalPages = [];
    
    foreach ($pages as $page) {
        $type = $page['etut_type'] ?? 'all';
        if ($type === 'all') {
            $normalPages[] = $page;
        } else {
            $etutPages[] = $page;
        }
        echo sprintf("[%2d] %-40s (Type: %s)\n", $page['id'], substr($page['page_name'], 0, 40), $type);
    }
    
    // ============================================================
    // 3. HER ROL İÇİN İZİN STATİSTİKLERİ
    // ============================================================
    echo "\n📊 3. HER ROL İÇİN İZİN İSTATİSTİKLERİ\n";
    echo "─────────────────────────────────────────────────────────────────\n";
    
    foreach ($roles as $role) {
        $stmt = $pdo->prepare('
            SELECT COUNT(*) as total FROM vp_role_page_permissions WHERE role_id = ?
        ');
        $stmt->execute([$role['id']]);
        $count = $stmt->fetchColumn();
        
        $status = $count == count($pages) ? '✅ TÜM İZİNLER' : "⚠️  EKSIK ({$count}/" . count($pages) . ")";
        echo sprintf("[%d] %-30s → %s\n", $role['id'], $role['display_name'], $status);
    }
    
    // ============================================================
    // 4. ROLE TARIFLERİ VE ACCESS RULES
    // ============================================================
    echo "\n🔐 4. ROLE TARIFLERİ VE ACCESS RULES\n";
    echo "─────────────────────────────────────────────────────────────────\n";
    
    $accessRules = [
        1 => ['name' => 'Admin', 'rule' => 'Tüm sayfaları görebilir ve yönetebilir'],
        2 => ['name' => 'Öğretmen', 'rule' => 'Tüm normal sayfalar + etüt sayfaları'],
        3 => ['name' => 'Sekreter', 'rule' => 'Tüm normal sayfalar'],
        4 => ['name' => 'Müdür', 'rule' => 'Tüm normal sayfalar (okuma)'],
        5 => ['name' => 'Müdür Yardımcısı', 'rule' => 'Normal sayfalar + etüt sayfaları'],
    ];
    
    foreach ($accessRules as $roleId => $rule) {
        echo sprintf("[%d] %s → %s\n", $roleId, $rule['name'], $rule['rule']);
    }
    
    // ============================================================
    // 5. EKSIK İZİNLER
    // ============================================================
    echo "\n❌ 5. EKSIK İZİNLER (İzinsiz Sayfa-Rol Kombinasyonları)\n";
    echo "─────────────────────────────────────────────────────────────────\n";
    
    $missingCount = 0;
    
    foreach ($roles as $role) {
        // Role göre erişebilecek sayfa listesi
        $accessiblePages = [];
        
        foreach ($pages as $page) {
            $type = $page['etut_type'] ?? 'all';
            
            // Erişebilirlik kuralları
            $canAccess = false;
            
            if ($type === 'all') {
                // Normal sayfalar: herkese
                $canAccess = true;
            } else {
                // Etüt sayfaları: admin, teacher, vice_principal
                $canAccess = in_array($role['role_name'], ['admin', 'teacher', 'vice_principal']);
            }
            
            if ($canAccess) {
                $accessiblePages[] = $page['id'];
            }
        }
        
        // Veritabanında olan izinleri kontrol et
        $placeholders = implode(',', array_fill(0, count($accessiblePages), '?'));
        $stmt = $pdo->prepare("
            SELECT page_id FROM vp_role_page_permissions 
            WHERE role_id = ? AND page_id IN ($placeholders)
        ");
        $stmt->execute(array_merge([$role['id']], $accessiblePages));
        $existingPages = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Eksik olanları bul
        $missingPages = array_diff($accessiblePages, $existingPages);
        
        if (!empty($missingPages)) {
            echo sprintf("  [%d] %s → Eksik sayfalar: %s\n", 
                $role['id'], 
                $role['display_name'],
                implode(', ', array_map(fn($id) => "ID:{$id}", $missingPages))
            );
            $missingCount += count($missingPages);
        }
    }
    
    if ($missingCount === 0) {
        echo "  ✅ Eksik izin yok!\n";
    }
    
    // ============================================================
    // 6. ÖZET
    // ============================================================
    echo "\n📈 6. ÖZET\n";
    echo "─────────────────────────────────────────────────────────────────\n";
    
    $stmt = $pdo->query('SELECT COUNT(*) FROM vp_roles');
    $totalRoles = $stmt->fetchColumn();
    
    $stmt = $pdo->query('SELECT COUNT(*) FROM vp_pages WHERE is_active = 1');
    $totalPages = $stmt->fetchColumn();
    
    $stmt = $pdo->query('SELECT COUNT(*) FROM vp_role_page_permissions');
    $totalPermissions = $stmt->fetchColumn();
    
    $maxPermissions = $totalRoles * $totalPages;
    
    echo sprintf("Toplam Roller: %d\n", $totalRoles);
    echo sprintf("Toplam Sayfalar (aktif): %d\n", $totalPages);
    echo sprintf("Mevcut İzinler: %d / %d (%%%d)\n", 
        $totalPermissions, 
        $maxPermissions,
        $totalPermissions > 0 ? ($totalPermissions * 100) / $maxPermissions : 0
    );
    echo sprintf("Eksik İzinler: %d\n", $maxPermissions - $totalPermissions);
    
    echo sprintf("\nNormal Sayfalar: %d\n", count($normalPages));
    echo sprintf("Etüt Sayfaları: %d\n", count($etutPages));
    
    echo "\n✅ Analiz tamamlandı!\n\n";
    
} catch (Exception $e) {
    echo "❌ Hata: " . $e->getMessage() . "\n";
    exit(1);
}
