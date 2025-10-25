-- =================================================================
-- PERMISSION SYSTEM REBUILD - SADECE ESKİ KAYITLARI SİL VE YENİLERİ EKLE
-- =================================================================
-- ⚠️ CONSTRAINTS zaten var, sadece data rebuild
-- =================================================================

-- =================================================================
-- BACKUP
-- =================================================================
CREATE TABLE IF NOT EXISTS vp_pages_backup_20251025 AS SELECT * FROM vp_pages;
CREATE TABLE IF NOT EXISTS vp_role_page_permissions_backup_20251025 AS SELECT * FROM vp_role_page_permissions;

-- =================================================================
-- ESKİ KAYITLARI SİL
-- =================================================================
TRUNCATE TABLE vp_role_page_permissions;
TRUNCATE TABLE vp_pages;

-- =================================================================
-- 11 TEMİZ SAYFA EKLE
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

-- Kontrol
SELECT COUNT(*) as 'Toplam Sayfa' FROM vp_pages;

-- =================================================================
-- DEFAULT PERMISSIONS
-- =================================================================

-- Admin (1) - FULL ACCESS
INSERT INTO vp_role_page_permissions (role_id, page_id, can_view, can_create, can_edit, can_delete)
SELECT 1, id, 1, 1, 1, 1 FROM vp_pages;

-- Teacher (2) - Sadece student-search
INSERT INTO vp_role_page_permissions (role_id, page_id, can_view, can_create, can_edit, can_delete)
SELECT 2, id, 1, 0, 0, 0 FROM vp_pages WHERE page_key = 'student-search';

-- Principal (4) - FULL ACCESS
INSERT INTO vp_role_page_permissions (role_id, page_id, can_view, can_create, can_edit, can_delete)
SELECT 4, id, 1, 1, 1, 1 FROM vp_pages;

-- Vice Principal (5) - FULL ACCESS
INSERT INTO vp_role_page_permissions (role_id, page_id, can_view, can_create, can_edit, can_delete)
SELECT 5, id, 1, 1, 1, 1 FROM vp_pages;

-- =================================================================
-- KONTROL
-- =================================================================

-- Her role kaç sayfaya erişebiliyor?
SELECT 
    r.id,
    r.role_name,
    COUNT(rpp.id) as 'Sayfa Sayısı'
FROM vp_roles r
LEFT JOIN vp_role_page_permissions rpp ON rpp.role_id = r.id
GROUP BY r.id, r.role_name
ORDER BY r.id;

-- Vice Principal (emine) detaylı kontrol
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
-- ✅ BİTTİ!
-- =================================================================
-- Şimdi:
-- 1. Logout
-- 2. emine ile login
-- 3. Sidebar'da 11 menü görmelisiniz!
-- =================================================================
