-- ============================================
-- Vildan Portal - Shared Teacher Account Setup
-- 70 öğretmenin paylaşımda kullanacağı hesap
-- ============================================

-- Eğer vp_pages tablosunda sayfalar yoksa ekle
INSERT IGNORE INTO `vp_pages` (`id`, `page_name`, `page_key`, `page_url`, `display_name`, `created_at`) VALUES
(1, 'Dashboard', 'dashboard', '/dashboard', 'Ana Sayfa', NOW()),
(2, 'Students', 'students', '/students', 'Öğrenci Bilgileri', NOW()),
(3, 'Activities', 'activities', '/activities', 'Etkinlikler', NOW()),
(4, 'Activity Areas', 'activity_areas', '/activity-areas', 'Etkinlik Alanları', NOW()),
(5, 'Etut', 'etut', '/etut', 'Etüt', NOW()),
(6, 'Admin', 'admin', '/admin', 'Admin Paneli', NOW()),
(7, 'Users', 'users', '/admin/users', 'Kullanıcı Yönetimi', NOW());

-- Eğer teacher rolü yoksa oluştur
INSERT IGNORE INTO `vp_roles` (`id`, `role_name`, `display_name`, `description`, `created_at`) VALUES
(3, 'teacher', 'Öğretmen', 'Sınıf öğretmeni', NOW());

-- Shared teacher kullanıcısını oluştur
-- Şifre: 12345678
-- Şifre hash: $2y$10$KwjPt.TN5Vfk3x.ypJcHSeQ9OKJvMG/HNnZj3/Z3OKJ1eZYyX.96K
INSERT INTO `vp_users` (`username`, `email`, `password_hash`, `role_id`, `first_name`, `last_name`, `phone`, `status`, `created_at`, `updated_at`, `last_login`)
VALUES ('teacher', 'teacher@vldn.in', '$2y$10$KwjPt.TN5Vfk3x.ypJcHSeQ9OKJvMG/HNnZj3/Z3OKJ1eZYyX.96K', 3, 'Öğretmen', 'Hesabı', '', 'active', NOW(), NOW(), NULL)
ON DUPLICATE KEY UPDATE email = VALUES(email), password_hash = VALUES(password_hash), role_id = VALUES(role_id);

-- Teacher rolüne sadece STUDENTS sayfasına VIEW yetkisi ver
-- Diğer tüm izinler 0 (kapalı)
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

-- Doğrulama sorgusu
SELECT 
    u.id, u.username, u.email, r.role_name, r.display_name,
    (SELECT COUNT(*) FROM vp_role_page_permissions WHERE role_id = r.id AND can_view = 1) as view_pages_count
FROM vp_users u
JOIN vp_roles r ON u.role_id = r.id
WHERE u.username = 'teacher';
