-- ============================================
-- Vildan Portal - FIXED Teacher Setup
-- Redirect loop çözümü: Teacher yetkisini dinamik olarak kontrol et
-- ============================================

-- STEP 1: Mevcut sayfaları kontrol et
SELECT 'Checking existing pages...' as step;
SELECT * FROM vp_pages ORDER BY id;

-- STEP 2: Teacher rolü yoksa oluştur
INSERT IGNORE INTO `vp_roles` (`id`, `role_name`, `display_name`, `description`, `created_at`) VALUES
(3, 'teacher', 'Öğretmen', 'Sınıf öğretmeni - Shared Account', NOW());

-- STEP 3: Vildan hesabını teacher rolüne çevir
UPDATE `vp_users` SET role_id = 3 WHERE username = 'vildan';

-- STEP 4: IMPORTANT - Eski permissions'ları temizle (varsa)
DELETE FROM `vp_role_page_permissions` WHERE role_id = 3;

-- STEP 5: Tüm sayfalar için permissions oluştur
-- ADMIN tarafından önceden oluşturulmuş olan tüm sayfaları al
-- Teacher sadece 'student-search' sayfasına VIEW yetkisi alır
INSERT INTO `vp_role_page_permissions` (`role_id`, `page_id`, `can_view`, `can_create`, `can_edit`, `can_delete`, `created_at`, `updated_at`)
SELECT 
    3 as role_id,
    p.id as page_id,
    IF(p.page_key = 'student-search' OR p.page_key = 'student_search', 1, 0) as can_view,
    0 as can_create,
    0 as can_edit,
    0 as can_delete,
    NOW() as created_at,
    NOW() as updated_at
FROM vp_pages p
WHERE NOT EXISTS (
    SELECT 1 FROM vp_role_page_permissions 
    WHERE role_id = 3 AND page_id = p.id
);

-- STEP 6: VERIFY
SELECT 'Teacher Permissions Setup Complete!' as step;
SELECT 
    'TEACHER CAN VIEW THESE PAGES:' as permission_check;
SELECT 
    rpp.role_id,
    p.id as page_id,
    p.page_name,
    p.page_key,
    rpp.can_view,
    rpp.can_create,
    rpp.can_edit,
    rpp.can_delete
FROM vp_role_page_permissions rpp
LEFT JOIN vp_pages p ON rpp.page_id = p.id
WHERE rpp.role_id = 3 AND rpp.can_view = 1
ORDER BY p.id;

SELECT 
    'TEACHER CANNOT VIEW THESE PAGES:' as permission_check;
SELECT 
    rpp.role_id,
    p.id as page_id,
    p.page_name,
    p.page_key
FROM vp_role_page_permissions rpp
LEFT JOIN vp_pages p ON rpp.page_id = p.id
WHERE rpp.role_id = 3 AND rpp.can_view = 0
ORDER BY p.id;
