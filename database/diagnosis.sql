-- ==================================
-- DIAGNOSIS SQL
-- ==================================

USE vildacgg_portalv2;

-- 1. Diğer roller'in permission'ları var mı?
SELECT 'TÜM ROLLER PERMİSSİON SAYILARI:' as test;
SELECT 
    rp.role_id,
    r.role_name,
    COUNT(*) as permission_count
FROM vp_role_page_permissions rp
LEFT JOIN vp_roles r ON rp.role_id = r.id
GROUP BY rp.role_id
ORDER BY rp.role_id;

-- 2. Role 5 (vice_principal) tüm sayfaları görebilir mi?
SELECT 'ROLE 5 İÇİN GÖSTERÜLECEK SAYFALAR:' as test;
SELECT 
    id,
    page_name,
    is_active,
    etut_type
FROM vp_pages
WHERE is_active = 1
ORDER BY id;

-- 3. Role 5 kullanıcıları
SELECT 'ROLE 5 KULLANICILAR:' as test;
SELECT id, name, email, role_id FROM vp_users WHERE role_id = 5;

-- 4. TÜM TABLOLARIN SAYILARI
SELECT 'TABLO SAYILARI:' as test;
SELECT 
    (SELECT COUNT(*) FROM vp_roles) as toplam_rol,
    (SELECT COUNT(*) FROM vp_pages WHERE is_active = 1) as aktif_sayfa,
    (SELECT COUNT(*) FROM vp_role_page_permissions) as toplam_permission;

-- 5. EN SON GÜNCELLENEN ROLE PERMISSIONS (tüm role'ler)
SELECT 'SON 20 PERMISSION GÜNCELLEMESI:' as test;
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
ORDER BY updated_at DESC
LIMIT 20;

-- 6. Page 12, 13 hangi role'lerde tanımlı?
SELECT 'PAGE 12, 13 PERMİSSİONLARI (TÜM ROLLER):' as test;
SELECT 
    rp.role_id,
    r.role_name,
    rp.page_id,
    p.page_name,
    rp.can_view,
    rp.can_create,
    rp.can_edit,
    rp.can_delete
FROM vp_role_page_permissions rp
LEFT JOIN vp_roles r ON rp.role_id = r.id
LEFT JOIN vp_pages p ON rp.page_id = p.id
WHERE rp.page_id IN (12, 13)
ORDER BY rp.role_id, rp.page_id;
