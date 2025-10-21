-- Vildan Portal v2 - Database Schema
-- Created: 2024
-- Prefix: vp_

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- =====================================================
-- ROLES TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `vp_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_name` (`role_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- USERS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `vp_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(200) COLLATE utf8mb4_unicode_ci,
  `role_id` int(11) NOT NULL,
  `can_change_password` tinyint(1) DEFAULT 1,
  `is_active` tinyint(1) DEFAULT 1,
  `last_login` datetime,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `vp_users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `vp_roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- STUDENTS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `vp_students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tc_no` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birth_date` date DEFAULT NULL,
  `class` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `father_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `father_phone` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mother_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mother_phone` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `teacher_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `teacher_phone` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tc_no` (`tc_no`),
  KEY `class` (`class`),
  KEY `first_name` (`first_name`),
  KEY `last_name` (`last_name`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- PAGES TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `vp_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `page_slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_slug` (`page_slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- ROLE_PAGE_PERMISSIONS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `vp_role_page_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `can_view` tinyint(1) DEFAULT 0,
  `can_create` tinyint(1) DEFAULT 0,
  `can_edit` tinyint(1) DEFAULT 0,
  `can_delete` tinyint(1) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_role_page` (`role_id`, `page_id`),
  CONSTRAINT `vp_role_page_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `vp_roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `vp_role_page_permissions_ibfk_2` FOREIGN KEY (`page_id`) REFERENCES `vp_pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- ACTIVITIES TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `vp_activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activity_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activity_area_id` int(11) DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `activity_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `activity_date` (`activity_date`),
  KEY `activity_area_id` (`activity_area_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- ACTIVITY_AREAS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `vp_activity_areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `area_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `color_code` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT '#3498db',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TIME_SLOTS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `vp_time_slots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slot_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `day_of_week` int(1) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- ACTIVITY_AREA_TIME_SLOTS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `vp_activity_area_time_slots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activity_area_id` int(11) NOT NULL,
  `time_slot_id` int(11) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_area_slot` (`activity_area_id`, `time_slot_id`),
  CONSTRAINT `vp_activity_area_time_slots_ibfk_1` FOREIGN KEY (`activity_area_id`) REFERENCES `vp_activity_areas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `vp_activity_area_time_slots_ibfk_2` FOREIGN KEY (`time_slot_id`) REFERENCES `vp_time_slots` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- ETUT_APPLICATIONS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `vp_etut_applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `activity_area_id` int(11) NOT NULL,
  `application_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_student_area` (`student_id`, `activity_area_id`),
  KEY `student_id` (`student_id`),
  KEY `activity_area_id` (`activity_area_id`),
  KEY `status` (`status`),
  CONSTRAINT `vp_etut_applications_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `vp_students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `vp_etut_applications_ibfk_2` FOREIGN KEY (`activity_area_id`) REFERENCES `vp_activity_areas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- ETUT_FORM_SETTINGS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `vp_etut_form_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` longtext COLLATE utf8mb4_unicode_ci,
  `data_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'text',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- PUSH_SUBSCRIPTIONS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `vp_push_subscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `endpoint` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `auth_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `p256dh` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  CONSTRAINT `vp_push_subscriptions_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `vp_students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- INSERT DEFAULT DATA
-- =====================================================

-- Insert default roles
INSERT IGNORE INTO `vp_roles` (`id`, `role_name`, `display_name`, `description`) VALUES
(1, 'admin', 'Admin', 'Sistem Yöneticisi - Tüm Yetkilere Sahip'),
(2, 'teacher', 'Öğretmen', 'Öğretmen Kullanıcısı - Sınırlı Yetkiler'),
(3, 'secretary', 'Sekreter', 'Sekreter Kullanıcısı - Orta Seviye Yetkiler'),
(4, 'principal', 'Okul Müdürü', 'Müdür - Yüksek Seviye Yetkiler'),
(5, 'vice_principal', 'Müdür Yardımcısı', 'Müdür Yardımcısı - Yüksek Seviye Yetkiler');

-- Insert default pages
INSERT IGNORE INTO `vp_pages` (`page_slug`, `page_name`, `display_name`, `is_active`) VALUES
('students', 'Öğrenciler', 'Öğrenci Yönetimi', 1),
('users', 'Kullanıcılar', 'Kullanıcı Yönetimi', 1),
('etut', 'ETÜT', 'ETÜT Başvuruları', 1),
('activities', 'Faaliyetler', 'Faaliyet Yönetimi', 1),
('activity-areas', 'Faaliyet Alanları', 'Faaliyet Alanı Yönetimi', 1),
('dashboard', 'Panel', 'Ana Panel', 1),
('reports', 'Raporlar', 'Raporlar', 1);

-- Insert default admin role permissions (all access)
INSERT IGNORE INTO `vp_role_page_permissions` (`role_id`, `page_id`, `can_view`, `can_create`, `can_edit`, `can_delete`)
SELECT 1, `id`, 1, 1, 1, 1 FROM `vp_pages` WHERE `is_active` = 1;

-- Insert teacher permissions (limited)
INSERT IGNORE INTO `vp_role_page_permissions` (`role_id`, `page_id`, `can_view`, `can_create`, `can_edit`, `can_delete`)
SELECT 2, `id`, 1, 0, 0, 0 FROM `vp_pages` WHERE `page_slug` IN ('students', 'etut', 'activities', 'dashboard');

SET FOREIGN_KEY_CHECKS=1;
