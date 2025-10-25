-- =================================================================
-- PERMISSION SYSTEM REBUILD - COMPLETE RESET
-- =================================================================
-- ⚠️ DİKKAT: Bu script mevcut permission sistemini SİLER!
-- ⚠️ BACKUP ZORUNLU!
-- =================================================================

-- =================================================================
-- BACKUP (İlk çalıştırın, sonucu kaydedin)
-- =================================================================

-- Mevcut vp_pages tablosunu yedekle
CREATE TABLE IF NOT EXISTS vp_pages_backup_20251025 AS SELECT * FROM vp_pages;

-- Mevcut vp_role_page_permissions tablosunu yedekle
CREATE TABLE IF NOT EXISTS vp_role_page_permissions_backup_20251025 AS SELECT * FROM vp_role_page_permissions;

-- Backup kontrolü
SELECT 
    'BACKUP KONTROL' as test,
    (SELECT COUNT(*) FROM vp_pages_backup_20251025) as 'vp_pages (yedek)',
    (SELECT COUNT(*) FROM vp_role_page_permissions_backup_20251025) as 'permissions (yedek)';

-- DEVAM ETMEDEN ÖNCE: Bu sonucu kaydedin!
-- =================================================================

-- =================================================================
-- ADIM 1: ESKİ TABLOLARI TEMİZLE
-- =================================================================

-- Permission kayıtlarını sil (foreign key var, önce bu silinmeli)
TRUNCATE TABLE vp_role_page_permissions;

-- Sayfa kayıtlarını sil
TRUNCATE TABLE vp_pages;

-- Kontrol: Boş olmalı
SELECT COUNT(*) as 'vp_pages (sıfırlanmış)' FROM vp_pages;
SELECT COUNT(*) as 'vp_role_page_permissions (sıfırlanmış)' FROM vp_role_page_permissions;

-- =================================================================
-- ADIM 2: vp_pages TABLOSUNU GÜÇLENDIR
-- =================================================================

-- AUTO_INCREMENT ve PRIMARY KEY kontrol
ALTER TABLE vp_pages 
MODIFY COLUMN id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY;

-- UNIQUE constraint ekle (duplicate page_key olmasın)
ALTER TABLE vp_pages 
ADD UNIQUE KEY unique_page_key (page_key);

-- Yeni sütunlar ekle (eğer yoksa)
ALTER TABLE vp_pages 
ADD COLUMN IF NOT EXISTS category VARCHAR(50) DEFAULT 'general' AFTER page_url,
ADD COLUMN IF NOT EXISTS icon VARCHAR(50) DEFAULT 'fas fa-circle' AFTER category;

-- =================================================================
-- ADIM 3: vp_role_page_permissions TABLOSUNU GÜÇLENDIR
-- =================================================================

-- UNIQUE constraint ekle (her role+page çifti 1 kez)
ALTER TABLE vp_role_page_permissions 
ADD UNIQUE KEY unique_role_page (role_id, page_id);

-- =================================================================
-- ADIM 4: YENİ SAYFA KAYITLARI (11 SAYFA - DUPLICATE YOK)
-- =================================================================

INSERT INTO vp_pages (page_key, page_name, page_url, category, icon, is_active, sort_order) VALUES
('dashboard', 'Ana Sayfa', '/dashboard', 'main', 'fas fa-home', 1, 1),
('student-search', 'Öğrenci Ara', '/student-search', 'students', 'fas fa-search', 1, 2),
('students', 'Öğrenci Bilgileri', '/students', 'students', 'fas fa-user-graduate', 1, 3),
('activities', 'Etkinlikler', '/activities', 'activities', 'fas fa-calendar-alt', 1, 4),
('activity-areas', 'Etkinlik Alanları', '/activity-areas', 'activities', 'fas fa-map-marker-alt', 1, 5),
('etut-ortaokul', 'Ortaokul Etüt', '/etut/ortaokul', 'etut', 'fas fa-school', 1, 6),
('etut-lise', 'Lise Etüt', '/etut/lise', 'etut', 'fas fa-graduation-cap', 1, 7),
('reports', 'Raporlar', '/reports', 'reports', 'fas fa-chart-bar', 1, 8),
('users', 'Kullanıcılar', '/admin/users', 'admin', 'fas fa-users', 1, 9),
('roles', 'Rol İzinleri', '/admin/roles', 'admin', 'fas fa-shield-alt', 1, 10),
('settings', 'Sistem Ayarları', '/admin/settings', 'admin', 'fas fa-cog', 1, 11);

-- Kontrol: 11 sayfa olmalı
SELECT COUNT(*) as 'Eklenen Sayfa Sayısı' FROM vp_pages;

-- Sayfaları göster
SELECT id, page_key, page_name, category, icon FROM vp_pages ORDER BY sort_order;

-- =================================================================
-- ADIM 5: DEFAULT PERMISSIONS
-- =================================================================

-- Role 1: Admin - TÜM SAYFALARA FULL ACCESS
INSERT INTO vp_role_page_permissions (role_id, page_id, can_view, can_create, can_edit, can_delete)
SELECT 1, id, 1, 1, 1, 1 FROM vp_pages;

-- Role 2: Teacher - SADECE student-search (VIEW ONLY)
INSERT INTO vp_role_page_permissions (role_id, page_id, can_view, can_create, can_edit, can_delete)
SELECT 2, id, 1, 0, 0, 0 FROM vp_pages WHERE page_key = 'student-search';

-- Role 3: Secretary - Configurable (ŞİMDİLİK HİÇ YETKİ YOK, admin panelden eklenecek)
-- Boş bırakıyoruz

-- Role 4: Principal (Müdür) - TÜM SAYFALARA FULL ACCESS
INSERT INTO vp_role_page_permissions (role_id, page_id, can_view, can_create, can_edit, can_delete)
SELECT 4, id, 1, 1, 1, 1 FROM vp_pages;

-- Role 5: Vice Principal (Müdür Yardımcısı) - TÜM SAYFALARA FULL ACCESS
INSERT INTO vp_role_page_permissions (role_id, page_id, can_view, can_create, can_edit, can_delete)
SELECT 5, id, 1, 1, 1, 1 FROM vp_pages;

-- =================================================================
-- ADIM 6: KONTROL - TÜM ROLLER
-- =================================================================

SELECT 
    r.id as role_id,
    r.role_name,
    r.display_name,
    COUNT(rpp.id) as 'Kaç sayfaya erişim var?'
FROM vp_roles r
LEFT JOIN vp_role_page_permissions rpp ON rpp.role_id = r.id
GROUP BY r.id, r.role_name, r.display_name
ORDER BY r.id;

-- BEKLENEN:
-- Admin (1) → 11 sayfa
-- Teacher (2) → 1 sayfa (student-search)
-- Secretary (3) → 0 sayfa (admin panelden ayarlanacak)
-- Principal (4) → 11 sayfa
-- Vice Principal (5) → 11 sayfa

-- =================================================================
-- ADIM 7: DETAYLI KONTROL - Vice Principal (emine için)
-- =================================================================

SELECT 
    p.page_key,
    p.page_name,
    rpp.can_view,
    rpp.can_create,
    rpp.can_edit,
    rpp.can_delete
FROM vp_role_page_permissions rpp
JOIN vp_pages p ON p.id = rpp.page_id
WHERE rpp.role_id = 5
ORDER BY p.sort_order;

-- BEKLENEN: 11 satır, hepsi 1,1,1,1

-- =================================================================
-- ✅ TAMAMLANDI!
-- =================================================================
-- YAPILAN İŞLEMLER:
-- 1. ✅ Backup oluşturuldu
-- 2. ✅ Eski kayıtlar silindi
-- 3. ✅ Schema güçlendirildi (UNIQUE constraints)
-- 4. ✅ 11 temiz sayfa eklendi
-- 5. ✅ Default permissions ayarlandı
-- 
-- ŞİMDİ NE YAPACAKSINIZ?
-- 1. Production'da git pull origin main
-- 2. Logout → Login (emine)
-- 3. Sidebar'da 11 menü görmelisiniz!
-- =================================================================
