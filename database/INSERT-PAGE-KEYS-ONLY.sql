-- =================================================================
-- ADIM 2: Eksik page_key'leri ekle
-- =================================================================
-- NOT: ALTER TABLE başarıyla tamamlandı, şimdi INSERT yapabilirsiniz
-- IGNORE kullanıldı - eğer page_key zaten varsa hata vermez, atlar

INSERT IGNORE INTO vp_pages (page_name, page_key, page_url, is_active, sort_order) VALUES
('Dashboard', 'dashboard', '/dashboard', 1, 1),
('Öğrenci Ara', 'student-search', '/student-search', 1, 2),
('Öğrenciler', 'students', '/students', 1, 3),
('Etkinlikler', 'activities', '/activities', 1, 4),
('Etkinlik Alanları', 'activity-areas', '/activity-areas', 1, 5),
('Ortaokul Etüt', 'etut-ortaokul', '/etut/ortaokul', 1, 7),
('Lise Etüt', 'etut-lise', '/etut/lise', 1, 8),
('Raporlar', 'reports', '/reports', 1, 9),
('Kullanıcılar', 'users', '/admin/users', 1, 10),
('Roller', 'roles', '/admin/roles', 1, 11),
('Ayarlar', 'settings', '/admin/settings', 1, 12);

-- KONTROL: Kaç satır eklendi?
SELECT ROW_COUNT() as 'Eklenen Satır Sayısı';

-- =================================================================
-- ADIM 3: Tüm page_key'lerin varlığını kontrol et
-- =================================================================
SELECT 
    page_key,
    page_name,
    page_url,
    is_active,
    id
FROM vp_pages
WHERE page_key IN (
    'dashboard',
    'student-search', 
    'students',
    'activities',
    'activity-areas',
    'etut-ortaokul',
    'etut-lise',
    'reports',
    'users',
    'roles',
    'settings'
)
ORDER BY sort_order;

-- BEKLENEN: 11 satır görmelisiniz
-- Her page_key bir id'ye sahip olmalı

-- =================================================================
-- ADIM 4: Müdür Yardımcısı rolüne tüm yetkileri ver
-- =================================================================
-- Role ID 5 = Müdür Yardımcısı (emine kullanıcısının rolü)

-- Önce eski kayıtları temizle
DELETE FROM vp_role_page_permissions WHERE role_id = 5;

-- Tüm sayfalara FULL ACCESS ver (1,1,1,1)
INSERT INTO vp_role_page_permissions (role_id, page_id, can_view, can_create, can_edit, can_delete)
SELECT 
    5 as role_id,
    id as page_id,
    1 as can_view,
    1 as can_create,
    1 as can_edit,
    1 as can_delete
FROM vp_pages
WHERE is_active = 1;

-- KONTROL: Kaç yetki eklendi?
SELECT ROW_COUNT() as 'Eklenen Permission Sayısı';

-- =================================================================
-- ADIM 5: FINAL KONTROL - Müdür Yardımcısı yetkilerini göster
-- =================================================================
SELECT 
    r.role_name as 'Rol',
    p.page_key as 'Page Key',
    p.page_name as 'Sayfa Adı',
    CONCAT(
        IF(rpp.can_view=1, 'V', '-'),
        IF(rpp.can_create=1, 'C', '-'),
        IF(rpp.can_edit=1, 'E', '-'),
        IF(rpp.can_delete=1, 'D', '-')
    ) as 'Yetkiler (VCED)'
FROM vp_role_page_permissions rpp
JOIN vp_roles r ON r.id = rpp.role_id
JOIN vp_pages p ON p.id = rpp.page_id
WHERE r.id = 5
ORDER BY p.sort_order;

-- BEKLENEN: 11+ satır
-- Yetkiler sütunu: "VCED" (hepsi aktif) görmelisiniz

-- =================================================================
-- ✅ TAMAMLANDI!
-- =================================================================
-- ŞİMDİ TEST EDİN:
-- 1. Git pull yapın: cd /home/vildacgg/vldn.in/portalv2 && git pull origin main
-- 2. emine kullanıcısı ile login olun
-- 3. Sidebar'da 11 menü görmelisiniz:
--    ✅ Ana Sayfa
--    ✅ Öğrenci Ara
--    ✅ Öğrenci Bilgileri
--    ✅ Etkinlikler
--    ✅ Etüt → Ortaokul Başvuruları ← ÖNEMLİ!
--    ✅ Etüt → Lise Başvuruları ← ÖNEMLİ!
--    ✅ Raporlar
--    ✅ Kullanıcılar
--    ✅ Rol İzinleri
-- =================================================================
