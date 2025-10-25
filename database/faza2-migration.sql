-- ============================================================
-- FAZA 2: DATABASE MIGRATION
-- ============================================================
-- Amaç: vp_pages ve vp_role_page_permissions tablolarını güncellemek
-- ============================================================

-- ADIM 1: vp_pages tablosuna access_level kolonu ekle
-- (Eğer varsa skip et)
ALTER TABLE vp_pages ADD COLUMN `access_level` VARCHAR(50) DEFAULT 'all' COMMENT 'Erişim düzeyi: all, authenticated, role_specific';

-- ADIM 2: Mevcut sayfaları access_level'e göre güncelle
-- Normal sayfalar: 'all'
UPDATE vp_pages SET access_level = 'all' WHERE etut_type IS NULL OR etut_type = 'all';

-- Etüt sayfaları: 'role_specific'
UPDATE vp_pages SET access_level = 'role_specific' WHERE etut_type IN ('ortaokul', 'lise');

-- ADIM 3: Eksik rolе-sayfa izinlerini ekle
-- ============================================================

-- Rol 1 (Admin) - TÜM SAYFALAr
INSERT IGNORE INTO vp_role_page_permissions (role_id, page_id, can_view, can_create, can_edit, can_delete, created_at, updated_at)
SELECT 1 as role_id, p.id, 1, 1, 1, 1, NOW(), NOW()
FROM vp_pages p
WHERE p.is_active = 1
AND NOT EXISTS (SELECT 1 FROM vp_role_page_permissions WHERE role_id = 1 AND page_id = p.id);

-- Rol 2 (Öğretmen) - Tüm sayfalar + Etüt sayfaları
INSERT IGNORE INTO vp_role_page_permissions (role_id, page_id, can_view, can_create, can_edit, can_delete, created_at, updated_at)
SELECT 2 as role_id, p.id, 1, 1, 1, 1, NOW(), NOW()
FROM vp_pages p
WHERE p.is_active = 1
AND (p.etut_type IS NULL OR p.etut_type = 'all' OR p.etut_type IN ('ortaokul', 'lise'))
AND NOT EXISTS (SELECT 1 FROM vp_role_page_permissions WHERE role_id = 2 AND page_id = p.id);

-- Rol 3 (Sekreter) - Normal sayfalar (etüt hariç)
INSERT IGNORE INTO vp_role_page_permissions (role_id, page_id, can_view, can_create, can_edit, can_delete, created_at, updated_at)
SELECT 3 as role_id, p.id, 1, 1, 1, 1, NOW(), NOW()
FROM vp_pages p
WHERE p.is_active = 1
AND (p.etut_type IS NULL OR p.etut_type = 'all')
AND NOT EXISTS (SELECT 1 FROM vp_role_page_permissions WHERE role_id = 3 AND page_id = p.id);

-- Rol 4 (Müdür) - Normal sayfalar (okuma only)
INSERT IGNORE INTO vp_role_page_permissions (role_id, page_id, can_view, can_create, can_edit, can_delete, created_at, updated_at)
SELECT 4 as role_id, p.id, 1, 0, 0, 0, NOW(), NOW()
FROM vp_pages p
WHERE p.is_active = 1
AND (p.etut_type IS NULL OR p.etut_type = 'all')
AND NOT EXISTS (SELECT 1 FROM vp_role_page_permissions WHERE role_id = 4 AND page_id = p.id);

-- Rol 5 (Müdür Yardımcısı) - Normal sayfalar + Etüt sayfaları
INSERT IGNORE INTO vp_role_page_permissions (role_id, page_id, can_view, can_create, can_edit, can_delete, created_at, updated_at)
SELECT 5 as role_id, p.id, 1, 1, 1, 1, NOW(), NOW()
FROM vp_pages p
WHERE p.is_active = 1
AND (p.etut_type IS NULL OR p.etut_type = 'all' OR p.etut_type IN ('ortaokul', 'lise'))
AND NOT EXISTS (SELECT 1 FROM vp_role_page_permissions WHERE role_id = 5 AND page_id = p.id);

-- ============================================================
-- SONUÇ KONTROL
-- ============================================================

SELECT '✅ MIGRATION TAMAMLANDI' as durum;

-- Tüm rollerin izin sayılarını göster
SELECT 
    r.id,
    r.display_name,
    COUNT(rp.id) as toplam_izin,
    SUM(CASE WHEN rp.can_view = 1 THEN 1 ELSE 0 END) as goruntuleme,
    SUM(CASE WHEN rp.can_create = 1 THEN 1 ELSE 0 END) as ekleme,
    SUM(CASE WHEN rp.can_edit = 1 THEN 1 ELSE 0 END) as duzenleme,
    SUM(CASE WHEN rp.can_delete = 1 THEN 1 ELSE 0 END) as silme
FROM vp_roles r
LEFT JOIN vp_role_page_permissions rp ON r.id = rp.role_id
WHERE r.id IN (1,2,3,4,5)
GROUP BY r.id, r.display_name
ORDER BY r.id;

-- Rol 5 (vice_principal) için 3 önemli sayfayı kontrol et
SELECT 
    p.id,
    p.page_name,
    rp.can_view,
    rp.can_create,
    rp.can_edit,
    rp.can_delete
FROM vp_pages p
LEFT JOIN vp_role_page_permissions rp ON p.id = rp.page_id AND rp.role_id = 5
WHERE p.id IN (11, 12, 13)
ORDER BY p.id;
