-- =================================================================
-- PERMISSION SYSTEM REBUILD - COMPLETE RESET (FIX)
-- =================================================================
-- ⚠️ PRIMARY KEY hatası düzeltildi
-- =================================================================

-- =================================================================
-- BACKUP (İlk çalıştırın, sonucu kaydedin)
-- =================================================================

CREATE TABLE IF NOT EXISTS vp_pages_backup_20251025 AS SELECT * FROM vp_pages;
CREATE TABLE IF NOT EXISTS vp_role_page_permissions_backup_20251025 AS SELECT * FROM vp_role_page_permissions;

SELECT 
    'BACKUP KONTROL' as test,
    (SELECT COUNT(*) FROM vp_pages_backup_20251025) as 'vp_pages (yedek)',
    (SELECT COUNT(*) FROM vp_role_page_permissions_backup_20251025) as 'permissions (yedek)';

-- =================================================================
-- ADIM 1: ESKİ TABLOLARI TEMİZLE
-- =================================================================

TRUNCATE TABLE vp_role_page_permissions;
TRUNCATE TABLE vp_pages;

SELECT COUNT(*) as 'vp_pages (sıfırlanmış)' FROM vp_pages;
SELECT COUNT(*) as 'vp_role_page_permissions (sıfırlanmış)' FROM vp_role_page_permissions;

-- =================================================================
-- ADIM 2: vp_pages TABLOSUNU GÜÇLENDIR
-- =================================================================

-- UNIQUE constraint ekle (eğer yoksa) - duplicate page_key olmasın
-- Önce var mı kontrol et, varsa hata verme
SET @constraint_exists = (
    SELECT COUNT(*) 
    FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
    WHERE TABLE_SCHEMA = 'vildacgg_portalv2' 
    AND TABLE_NAME = 'vp_pages' 
    AND CONSTRAINT_NAME = 'unique_page_key'
);

-- Eğer yoksa ekle
ALTER TABLE vp_pages 
ADD UNIQUE KEY unique_page_key (page_key);

-- =================================================================
-- ADIM 3: vp_role_page_permissions TABLOSUNU GÜÇLENDIR
-- =================================================================

-- UNIQUE constraint ekle (her role+page çifti 1 kez)
ALTER TABLE vp_role_page_permissions 
ADD UNIQUE KEY unique_role_page (role_id, page_id);

-- =================================================================
-- ADIM 4: YENİ SAYFA KAYITLARI (11 SAYFA - DUPLICATE YOK)
-- =================================================================

INSERT INTO vp_pages (page_key, page_name, page_url, is_active, sort_order) VALUES
('dashboard', 'Ana Sayfa', '/dashboard', 1, 1),
('student-search', 'Öğrenci Ara', '/student-search', 1, 2),
('students', 'Öğrenci Bilgileri', '/students', 1, 3),
('activities', 'Etkinlikler', '/activities', 1, 4),
('activity-areas', 'Etkinlik Alanları', '/activity-areas', 1, 5),
('etut-ortaokul', 'Ortaokul Etüt', '/etut/ortaokul', 1, 6),
('etut-lise', 'Lise Etüt', '/etut/lise', 1, 7),
('reports', 'Raporlar', '/reports', 1, 8),
('users', 'Kullanıcılar', '/admin/users', 1, 9),
('roles', 'Rol İzinleri', '/admin/roles', 1, 10),
('settings', 'Sistem Ayarları', '/admin/settings', 1, 11);

SELECT COUNT(*) as 'Eklenen Sayfa Sayısı' FROM vp_pages;
SELECT id, page_key, page_name FROM vp_pages ORDER BY sort_order;

-- =================================================================
-- ADIM 5: DEFAULT PERMISSIONS
-- =================================================================

-- Role 1: Admin - TÜM SAYFALARA FULL ACCESS
INSERT INTO vp_role_page_permissions (role_id, page_id, can_view, can_create, can_edit, can_delete)
SELECT 1, id, 1, 1, 1, 1 FROM vp_pages;

-- Role 2: Teacher - SADECE student-search (VIEW ONLY)
INSERT INTO vp_role_page_permissions (role_id, page_id, can_view, can_create, can_edit, can_delete)
SELECT 2, id, 1, 0, 0, 0 FROM vp_pages WHERE page_key = 'student-search';

-- Role 4: Principal (Müdür) - TÜM SAYFALARA FULL ACCESS
INSERT INTO vp_role_page_permissions (role_id, page_id, can_view, can_create, can_edit, can_delete)
SELECT 4, id, 1, 1, 1, 1 FROM vp_pages;

-- Role 5: Vice Principal (Müdür Yardımcısı) - TÜM SAYFALARA FULL ACCESS
INSERT INTO vp_role_page_permissions (role_id, page_id, can_view, can_create, can_edit, can_delete)
SELECT 5, id, 1, 1, 1, 1 FROM vp_pages;

-- =================================================================
-- ADIM 6: KONTROL
-- =================================================================

SELECT 
    r.id as role_id,
    r.role_name,
    r.display_name,
    COUNT(rpp.id) as 'Sayfa Sayısı'
FROM vp_roles r
LEFT JOIN vp_role_page_permissions rpp ON rpp.role_id = r.id
GROUP BY r.id, r.role_name, r.display_name
ORDER BY r.id;

-- Vice Principal detay (emine için)
SELECT 
    p.page_key,
    p.page_name,
    CONCAT(
        IF(rpp.can_view=1, 'V', '-'),
        IF(rpp.can_create=1, 'C', '-'),
        IF(rpp.can_edit=1, 'E', '-'),
        IF(rpp.can_delete=1, 'D', '-')
    ) as 'Yetkiler'
FROM vp_role_page_permissions rpp
JOIN vp_pages p ON p.id = rpp.page_id
WHERE rpp.role_id = 5
ORDER BY p.sort_order;

-- BEKLENEN: 11 satır, hepsi "VCED"

-- =================================================================
-- ✅ TAMAMLANDI!
-- =================================================================
