-- ===================================
-- PRODUCTION DATABASE KONTROL SQL
-- ===================================

-- 1. TABLO YAPISI KONTROL
-- vp_role_page_permissions tablosu düzgün mü?
DESCRIBE vp_role_page_permissions;

-- 2. TABLO İNDEKSLERİ KONTROL
SHOW INDEXES FROM vp_role_page_permissions;

-- 3. AUTO_INCREMENT KONTROL
SELECT AUTO_INCREMENT 
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA='vildacgg_portalv2' 
AND TABLE_NAME='vp_role_page_permissions';

-- 4. ROLE 5 (Ortaokul Müdür Yardımcısı) KONTROL
SELECT id, role_name, display_name 
FROM vp_roles 
WHERE id = 5;

-- 5. SAYFALAR KONTROL - Page 12, 13 aktif mi?
SELECT id, page_name, is_active, etut_type 
FROM vp_pages 
WHERE id IN (12, 13) OR page_name LIKE '%Etüt%';

-- 6. TÜM AKTİF SAYFALAR LİSTESİ
SELECT id, page_name, is_active, etut_type 
FROM vp_pages 
WHERE is_active = 1 
ORDER BY id;

-- 7. ROLE 5 İÇİN MEVCUT PERMİSSİONLAR
SELECT 
    rp.id,
    rp.role_id,
    rp.page_id,
    p.page_name,
    rp.can_view,
    rp.can_create,
    rp.can_edit,
    rp.can_delete,
    rp.created_at,
    rp.updated_at
FROM vp_role_page_permissions rp
LEFT JOIN vp_pages p ON rp.page_id = p.id
WHERE rp.role_id = 5
ORDER BY rp.page_id;

-- 8. ROLE 5 PERMİSSİON ÖZET
SELECT 
    COUNT(*) as toplam_permission,
    COUNT(CASE WHEN can_view = 1 THEN 1 END) as view_sayisi,
    COUNT(CASE WHEN can_create = 1 THEN 1 END) as create_sayisi,
    COUNT(CASE WHEN can_edit = 1 THEN 1 END) as edit_sayisi,
    COUNT(CASE WHEN can_delete = 1 THEN 1 END) as delete_sayisi
FROM vp_role_page_permissions
WHERE role_id = 5;

-- 9. PAGE 12, 13 İÇİN ROLE 5 PERMİSSİONLARI
SELECT 
    rp.page_id,
    p.page_name,
    rp.can_view,
    rp.can_create,
    rp.can_edit,
    rp.can_delete
FROM vp_role_page_permissions rp
LEFT JOIN vp_pages p ON rp.page_id = p.id
WHERE rp.role_id = 5 AND rp.page_id IN (12, 13);

-- 10. TÜM ROLLERİN PERMİSSİON SAYILARI
SELECT 
    r.id,
    r.role_name,
    r.display_name,
    COUNT(rp.id) as permission_count
FROM vp_roles r
LEFT JOIN vp_role_page_permissions rp ON r.id = rp.role_id
GROUP BY r.id
ORDER BY r.id;

-- 11. ROLE 5 KULLANICILAR
SELECT 
    u.id,
    u.name,
    u.email,
    u.role_id,
    r.role_name,
    u.etut_type
FROM vp_users u
LEFT JOIN vp_roles r ON u.role_id = r.id
WHERE u.role_id = 5;

-- 12. DATABASE KONTROL - İstatistikler
SELECT 
    (SELECT COUNT(*) FROM vp_roles) as toplam_rol,
    (SELECT COUNT(*) FROM vp_pages WHERE is_active = 1) as aktif_sayfa,
    (SELECT COUNT(*) FROM vp_role_page_permissions) as toplam_permission;

-- 13. PROBLEM TESTİ - Role 5'e ait page 12, 13'ün expected vs actual
SELECT 
    p.id,
    p.page_name,
    CASE WHEN rp.id IS NULL THEN 'EKSIK' ELSE 'KAYITLI' END as durum,
    COALESCE(rp.can_view, 0) as can_view,
    COALESCE(rp.can_create, 0) as can_create,
    COALESCE(rp.can_edit, 0) as can_edit,
    COALESCE(rp.can_delete, 0) as can_delete
FROM vp_pages p
LEFT JOIN vp_role_page_permissions rp ON (p.id = rp.page_id AND rp.role_id = 5)
WHERE p.id IN (12, 13)
ORDER BY p.id;

-- 14. EN SON GÜNCELLENEN PERMİSSİONLAR (Role 5)
SELECT 
    id,
    role_id,
    page_id,
    can_view,
    can_create,
    can_edit,
    can_delete,
    updated_at
FROM vp_role_page_permissions
WHERE role_id = 5
ORDER BY updated_at DESC
LIMIT 10;
