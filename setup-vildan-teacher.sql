-- ============================================
-- Vildan Portal - Teacher Account Setup (UPDATED)
-- Vildan hesabını teacher rolüne çevir ve permission ver
-- ============================================

-- STEP 1: Eğer teacher rolü yoksa oluştur
INSERT IGNORE INTO `vp_roles` (`id`, `role_name`, `display_name`, `description`, `created_at`) VALUES
(3, 'teacher', 'Öğretmen', 'Sınıf öğretmeni - Shared Account', NOW());

-- STEP 2: Eğer vp_pages tablosunda sayfalar yoksa ekle
INSERT IGNORE INTO `vp_pages` (`id`, `page_name`, `page_key`, `page_url`, `display_name`, `created_at`) VALUES
(1, 'Dashboard', 'dashboard', '/dashboard', 'Ana Sayfa', NOW()),
(2, 'Students', 'students', '/students', 'Öğrenci Bilgileri', NOW()),
(3, 'Activities', 'activities', '/activities', 'Etkinlikler', NOW()),
(4, 'Activity Areas', 'activity_areas', '/activity-areas', 'Etkinlik Alanları', NOW()),
(5, 'Etut', 'etut', '/etut', 'Etüt', NOW()),
(6, 'Admin', 'admin', '/admin', 'Admin Paneli', NOW()),
(7, 'Users', 'users', '/admin/users', 'Kullanıcı Yönetimi', NOW());

-- STEP 3: Vildan hesabını teacher rolüne çevir
UPDATE `vp_users` SET role_id = 3 WHERE username = 'vildan';

-- STEP 4: Teacher rolüne sadece STUDENTS sayfasına VIEW yetkisi ver
INSERT IGNORE INTO `vp_role_page_permissions` (`role_id`, `page_id`, `can_view`, `can_create`, `can_edit`, `can_delete`, `created_at`, `updated_at`) VALUES
-- Dashboard: Sadece VIEW izni yok
(3, 1, 0, 0, 0, 0, NOW(), NOW()),
-- Students: VIEW izni var
(3, 2, 1, 0, 0, 0, NOW(), NOW()),
-- Activities: VIEW izni yok
(3, 3, 0, 0, 0, 0, NOW(), NOW()),
-- Activity Areas: VIEW izni yok
(3, 4, 0, 0, 0, 0, NOW(), NOW()),
-- Etut: VIEW izni yok
(3, 5, 0, 0, 0, 0, NOW(), NOW()),
-- Admin: VIEW izni yok
(3, 6, 0, 0, 0, 0, NOW(), NOW()),
-- Users: VIEW izni yok
(3, 7, 0, 0, 0, 0, NOW(), NOW());

-- VERIFICATION QUERIES
-- Hangi sayfalar var?
SELECT 'Pages:' as check_type;
SELECT * FROM vp_pages ORDER BY id;

-- Teacher rolü ve permissions
SELECT 'Teacher Role Permissions:' as check_type;
SELECT 
    r.id as role_id,
    r.role_name,
    r.display_name,
    COUNT(rpp.id) as permission_count,
    SUM(rpp.can_view) as view_permissions
FROM vp_roles r
LEFT JOIN vp_role_page_permissions rpp ON r.id = rpp.role_id
WHERE r.role_name = 'teacher'
GROUP BY r.id;

-- Vildan kullanıcı
SELECT 'Vildan User:' as check_type;
SELECT 
    u.id, 
    u.username, 
    u.email, 
    r.role_name, 
    r.display_name
FROM vp_users u
LEFT JOIN vp_roles r ON u.role_id = r.id
WHERE u.username = 'vildan';

-- Teacher'ın izinli sayfaları
SELECT 'Teacher Can View Pages:' as check_type;
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
WHERE rpp.role_id = 3
ORDER BY p.id;
