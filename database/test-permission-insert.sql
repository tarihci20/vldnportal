-- Test: Permission kaydetme simülasyonu
-- Bu script'i production'da çalıştırdıktan sonra kontrol et

-- 1. TEMIZLE - Role 5 için tüm existing permissions sil
DELETE FROM vp_role_page_permissions WHERE role_id = 5;

-- 2. YENİ PERMISSIONS ekle - tüm sayfalar için (ID 12, 13'ü dahil et)
INSERT INTO vp_role_page_permissions (role_id, page_id, can_view, can_create, can_edit, can_delete)
VALUES
-- Page 12 (Ortaokul Etüt Başvuruları) - sadece can_view ve can_create
(5, 12, 1, 1, 0, 0),
-- Page 13 (Lise Etüt Başvuruları) - hiç permission
(5, 13, 0, 0, 0, 0),
-- Page 1 (Users) - all permissions
(5, 1, 1, 1, 1, 1),
-- Page 2 (Aktiviteler) - view only
(5, 2, 1, 0, 0, 0),
-- Page 3 (Öğrenciler) - view only  
(5, 3, 1, 0, 0, 0),
-- Page 4 (Sistem Ayarları) - no permissions
(5, 4, 0, 0, 0, 0);

-- 3. KONTROL ET - Tüm kayıtları görüntüle
SELECT 'Newly inserted permissions for Role 5:' AS status;
SELECT * FROM vp_role_page_permissions WHERE role_id = 5 ORDER BY page_id;

-- 4. KONTROL ET - Role 5 user'larını kontrol et  
SELECT 'Users with Role 5 (vice_principal):' AS status;
SELECT u.id, u.name, u.email, r.role_name, u.etut_type
FROM vp_users u
LEFT JOIN vp_roles r ON u.role_id = r.id
WHERE u.role_id = 5;

-- 5. Tüm pages'i ve role 5'in permission'larını birlikte listele
SELECT 
    p.id as page_id,
    p.page_name,
    p.is_active,
    p.etut_type,
    COALESCE(rp.can_view, 0) as can_view,
    COALESCE(rp.can_create, 0) as can_create,
    COALESCE(rp.can_edit, 0) as can_edit,
    COALESCE(rp.can_delete, 0) as can_delete
FROM vp_pages p
LEFT JOIN vp_role_page_permissions rp ON (p.id = rp.page_id AND rp.role_id = 5)
WHERE p.is_active = 1
ORDER BY p.id;
