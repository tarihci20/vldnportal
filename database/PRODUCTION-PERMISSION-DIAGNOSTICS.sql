-- ============================================================================
-- PERMISSION SYSTEM DIAGNOSTICS
-- Production'da çalıştırmak için MySQL/phpMyAdmin'de
-- ============================================================================

-- 1. ROLES - Tüm roller var mı?
SELECT '=== ROLES ===' as info;
SELECT id, role_name, display_name FROM vp_roles ORDER BY id;

-- 2. PAGES - Etüt sayfaları var mı?
SELECT '=== PAGES ===' as info;
SELECT id, page_name, page_key, is_active, 
       COALESCE(etut_type, 'normal') as type
FROM vp_pages 
WHERE is_active = 1 
ORDER BY id;

-- 3. TOTAL PERMISSION RECORDS
SELECT '=== PERMISSION RECORDS COUNT ===' as info;
SELECT COUNT(*) as total_permissions FROM vp_role_page_permissions;

-- 4. PERMISSIONS PER ROLE (KRİTİK!)
SELECT '=== PERMISSIONS PER ROLE ===' as info;
SELECT role_id, COUNT(*) as permission_count FROM vp_role_page_permissions 
GROUP BY role_id 
ORDER BY role_id;

-- 5. ROLE 5 (Vice Principal) İZİNLERİ
SELECT '=== ROLE 5 (VICE PRINCIPAL) PERMISSIONS ===' as info;
SELECT 
    rpp.role_id,
    rpp.page_id,
    p.page_name,
    p.page_key,
    COALESCE(p.etut_type, 'normal') as page_type,
    rpp.can_view,
    rpp.can_create,
    rpp.can_edit,
    rpp.can_delete
FROM vp_role_page_permissions rpp
LEFT JOIN vp_pages p ON rpp.page_id = p.id
WHERE rpp.role_id = 5
ORDER BY rpp.page_id;

-- 6. ETÜT SAYFALARININ İZİNLERİ (TÜM ROLLER)
SELECT '=== ETUT PAGES PERMISSIONS (ALL ROLES) ===' as info;
SELECT 
    rpp.role_id,
    r.display_name as role_name,
    rpp.page_id,
    p.page_name,
    p.etut_type,
    CONCAT(
        IF(rpp.can_view, 'V', '-'),
        IF(rpp.can_create, 'C', '-'),
        IF(rpp.can_edit, 'E', '-'),
        IF(rpp.can_delete, 'D', '-')
    ) as permissions
FROM vp_role_page_permissions rpp
LEFT JOIN vp_roles r ON rpp.role_id = r.id
LEFT JOIN vp_pages p ON rpp.page_id = p.id
WHERE p.etut_type IS NOT NULL
ORDER BY r.id, p.id;

-- 7. EKSIK İZİNLER (Tanımlı sayfa ama izin yok)
SELECT '=== MISSING PERMISSIONS (Pages without role assignments) ===' as info;
SELECT DISTINCT
    r.id as role_id,
    r.display_name,
    p.id as page_id,
    p.page_name
FROM vp_roles r
CROSS JOIN vp_pages p
WHERE p.is_active = 1
AND NOT EXISTS (
    SELECT 1 
    FROM vp_role_page_permissions rpp 
    WHERE rpp.role_id = r.id 
    AND rpp.page_id = p.id
)
ORDER BY r.id, p.id;

-- 8. DATABASE SCHEMA CHECK
SELECT '=== DATABASE SCHEMA ===' as info;
SHOW CREATE TABLE vp_role_page_permissions\G
