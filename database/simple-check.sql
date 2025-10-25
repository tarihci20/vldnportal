-- Önce doğru veritabanına geç
USE vildacgg_portalv2;

-- Sonra sorguları çalıştır

-- 1. ROLE 5 KONTROL
SELECT 'ROLE 5 KONTROL' as test;
SELECT id, role_name, display_name FROM vp_roles WHERE id = 5;

-- 2. PAGE 12, 13 KONTROL  
SELECT 'PAGE 12, 13 KONTROL' as test;
SELECT id, page_name, is_active, etut_type FROM vp_pages WHERE id IN (12, 13);

-- 3. ROLE 5 PERMİSSİONLARI
SELECT 'ROLE 5 TÜM PERMİSSİONLARI' as test;
SELECT * FROM vp_role_page_permissions WHERE role_id = 5;

-- 4. ÖZET
SELECT 'PERMİSSİON ÖZET' as test;
SELECT 
    COUNT(*) as toplam,
    SUM(can_view) as view_count,
    SUM(can_create) as create_count
FROM vp_role_page_permissions 
WHERE role_id = 5;

-- 5. PAGE 12, 13 SPEC
SELECT 'PAGE 12, 13 PERMISSIONS' as test;
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
