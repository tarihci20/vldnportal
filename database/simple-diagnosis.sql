-- ===================================
-- PRODUCTION DATABASE KONTROL
-- En temel sorgular
-- ===================================

USE vildacgg_portalv2;

-- 1. Role 1 (admin) permission'ları var mı?
SELECT '=== ROLE 1 (ADMIN) ===' as test;
SELECT COUNT(*) as permission_count FROM vp_role_page_permissions WHERE role_id = 1;
SELECT role_id, page_id, can_view, can_create, can_edit, can_delete FROM vp_role_page_permissions WHERE role_id = 1 LIMIT 5;

-- 2. Role 2 permission'ları var mı?
SELECT '=== ROLE 2 ===' as test;
SELECT COUNT(*) as permission_count FROM vp_role_page_permissions WHERE role_id = 2;

-- 3. Role 3 permission'ları var mı?
SELECT '=== ROLE 3 ===' as test;
SELECT COUNT(*) as permission_count FROM vp_role_page_permissions WHERE role_id = 3;

-- 4. Role 5 permission'ları var mı?
SELECT '=== ROLE 5 (VICE_PRINCIPAL) ===' as test;
SELECT COUNT(*) as permission_count FROM vp_role_page_permissions WHERE role_id = 5;

-- 5. TÜM ROLLER VE PERMISSION SAYILARI
SELECT '=== TÜM ROLLER ===' as test;
SELECT 
    r.id,
    r.role_name,
    r.display_name,
    COUNT(rp.id) as permission_count
FROM vp_roles r
LEFT JOIN vp_role_page_permissions rp ON r.id = rp.role_id
GROUP BY r.id, r.role_name, r.display_name
ORDER BY r.id;

-- 6. TOPLAM VERİ SAYıSı
SELECT '=== TOPLAM SAYILAR ===' as test;
SELECT 
    (SELECT COUNT(*) FROM vp_roles) as toplam_rol,
    (SELECT COUNT(*) FROM vp_pages WHERE is_active = 1) as aktif_sayfa,
    (SELECT COUNT(*) FROM vp_role_page_permissions) as toplam_permission;

-- 7. EN SON 10 GÜNCELLEME
SELECT '=== EN SON GÜNCELLEMELER ===' as test;
SELECT 
    id,
    role_id,
    page_id,
    updated_at
FROM vp_role_page_permissions
ORDER BY updated_at DESC
LIMIT 10;
