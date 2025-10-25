-- =================================================================
-- FIX: Page Keys Insert with AUTO_INCREMENT Fix
-- =================================================================
-- HATA: #1364 - Field 'id' doesn't have a default value
-- ÇÖZÜM: Önce id sütununu AUTO_INCREMENT yap, sonra INSERT
-- =================================================================

-- ADIM 1: vp_pages tablosunun id sütununu PRIMARY KEY ve AUTO_INCREMENT yap
-- =================================================================
-- HATA FIX: #1075 - AUTO_INCREMENT için önce PRIMARY KEY olmalı
ALTER TABLE vp_pages 
MODIFY COLUMN id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY;

-- ADIM 2: Eksik page_key'leri ekle (DUPLICATE hatası varsa devam eder)
-- =================================================================
-- NOT: Bu INSERT önceden var olan kayıtları tekrar eklemez (IGNORE kullanıldı)

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

-- =================================================================
-- ADIM 3: Kontrol - Tüm page_key'ler var mı?
-- =================================================================
SELECT 
    page_key,
    page_name,
    page_url,
    is_active
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

-- BEKLENEN SONUÇ: 11 satır
-- Eğer 11'den az satır görünüyorsa, eksik olan page_key'leri manuel ekleyin

-- =================================================================
-- ADIM 4: Müdür Yardımcısı rolüne tüm yetkileri ver (emine kullanıcısı için)
-- =================================================================
-- Role ID 5 = Müdür Yardımcısı (vice_principal)

-- Önce eski kayıtları temizle
DELETE FROM vp_role_page_permissions WHERE role_id = 5;

-- Tüm sayfalara tüm yetkileri ver
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

-- =================================================================
-- KONTROL: Müdür Yardımcısı rolü yetkilerini göster
-- =================================================================
SELECT 
    r.role_name,
    p.page_key,
    p.page_name,
    rpp.can_view,
    rpp.can_create,
    rpp.can_edit,
    rpp.can_delete
FROM vp_role_page_permissions rpp
JOIN vp_roles r ON r.id = rpp.role_id
JOIN vp_pages p ON p.id = rpp.page_id
WHERE r.id = 5
ORDER BY p.sort_order;

-- BEKLENEN: 11+ satır, hepsi 1,1,1,1

-- =================================================================
-- TAMAMLANDI!
-- =================================================================
-- ✅ vp_pages.id artık AUTO_INCREMENT
-- ✅ Tüm page_key'ler eklendi
-- ✅ Müdür Yardımcısı rolüne tüm yetkiler verildi
-- 
-- ŞİMDİ TEST EDİN:
-- 1. emine kullanıcısı ile login olun
-- 2. Sidebar'da şu menüleri görmeli:
--    - Ana Sayfa (Dashboard)
--    - Öğrenci Ara
--    - Öğrenci Bilgileri
--    - Etkinlikler
--    - Etüt → Ortaokul Başvuruları ← ÖNEMLİ!
--    - Etüt → Lise Başvuruları ← ÖNEMLİ!
--    - Etüt Form Ayarları ← ÖNEMLİ!
--    - Raporlar
--    - Kullanıcılar
--    - Rol İzinleri
-- =================================================================
