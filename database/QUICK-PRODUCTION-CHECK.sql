-- ============================================================================
-- QUICK PRODUCTION CHECK
-- Şu SQL'i phpMyAdmin'de çalıştır ve sonuçları gözlemle
-- ============================================================================

USE vildacgg_portalv2;

-- 1. DATABASE CONNECTION TEST
SELECT 'Database Connected ✅' as status;

-- 2. TOTAL PERMISSION RECORDS
SELECT COUNT(*) as total_permission_records FROM vp_role_page_permissions;

-- 3. PERMISSIONS PER ROLE (KRİTİK!)
SELECT 
    r.id,
    r.display_name as role,
    COUNT(rpp.id) as permission_count
FROM vp_roles r
LEFT JOIN vp_role_page_permissions rpp ON r.id = rpp.role_id
GROUP BY r.id, r.display_name
ORDER BY r.id;

-- 4. ROLE 5 (Vice Principal) DETAILED
SELECT 
    rpp.page_id,
    p.page_name,
    rpp.can_view,
    rpp.can_create,
    rpp.can_edit,
    rpp.can_delete
FROM vp_role_page_permissions rpp
LEFT JOIN vp_pages p ON rpp.page_id = p.id
WHERE rpp.role_id = 5
ORDER BY rpp.page_id;

-- 5. IF Role 5 has 0 permissions, RUN THIS:
-- INSERT INTO vp_role_page_permissions (role_id, page_id, can_view, can_create, can_edit, can_delete)
-- VALUES 
-- (5, 1, 1, 1, 1, 1), (5, 2, 1, 1, 1, 1), (5, 3, 1, 1, 1, 1), (5, 4, 1, 1, 1, 1),
-- (5, 5, 1, 1, 1, 1), (5, 6, 1, 1, 1, 1), (5, 7, 1, 1, 1, 1), (5, 8, 1, 1, 1, 1),
-- (5, 9, 1, 1, 1, 1), (5, 11, 1, 1, 1, 1), (5, 12, 1, 1, 1, 1), (5, 13, 1, 1, 1, 1);
