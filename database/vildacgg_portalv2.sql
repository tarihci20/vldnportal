-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost:3306
-- Üretim Zamanı: 24 Eki 2025, 18:21:11
-- Sunucu sürümü: 10.11.9-MariaDB
-- PHP Sürümü: 8.3.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `vildacgg_portalv2`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `table_name` varchar(50) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `old_values` text DEFAULT NULL,
  `new_values` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `table_name`, `record_id`, `old_values`, `new_values`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'user_login', NULL, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-12 09:54:37'),
(2, 1, 'user_login', NULL, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-12 09:56:13'),
(3, 1, 'user_login', NULL, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-12 10:26:47'),
(4, 1, 'user_login', NULL, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-12 10:33:07'),
(5, 1, 'user_login', NULL, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-12 15:50:07'),
(6, 1, 'user_logout', NULL, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-12 16:35:24'),
(7, 1, 'user_login', NULL, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-12 16:35:34'),
(8, 1, 'user_logout', NULL, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-12 16:41:08'),
(9, 1, 'user_login', NULL, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-12 16:41:21'),
(10, 1, 'user_logout', NULL, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-12 16:43:21'),
(11, 1, 'user_login', NULL, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-12 16:43:32'),
(12, 1, 'user_logout', NULL, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-12 16:45:15'),
(13, 1, 'user_login', NULL, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-12 16:45:25'),
(14, 1, 'user_logout', NULL, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-12 16:45:41'),
(15, 1, 'user_login', NULL, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-12 16:45:56'),
(16, 1, 'user_logout', NULL, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-12 16:48:24'),
(17, 1, 'user_login', NULL, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-12 16:48:36'),
(18, 1, 'user_logout', NULL, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-12 17:46:29'),
(19, 1, 'user_login', NULL, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-12 17:46:37'),
(20, 1, 'user_logout', NULL, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-12 17:59:27'),
(21, 1, 'user_login', NULL, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-12 17:59:36');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `vp_activities`
--

CREATE TABLE `vp_activities` (
  `id` int(11) NOT NULL,
  `area_id` int(11) NOT NULL,
  `activity_name` varchar(255) NOT NULL,
  `activity_type_id` int(11) DEFAULT NULL,
  `activity_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `responsible_person` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_recurring` tinyint(1) DEFAULT 0,
  `recurring_rule` varchar(50) DEFAULT NULL COMMENT 'daily_2, weekly_3 vb.',
  `recurrence_type` enum('daily','weekly','monthly') DEFAULT NULL,
  `recurrence_count` int(11) DEFAULT NULL,
  `parent_activity_id` int(11) DEFAULT NULL COMMENT 'Tekrar edenler için ana etkinlik',
  `ana_etkinlik_id` int(11) DEFAULT NULL COMMENT 'Ana etkinlik ID (tekrar kayıtları için)',
  `tekrar_durumu` enum('hayir','evet') DEFAULT 'hayir' COMMENT 'Tekrar edilip edilmediği',
  `tekrar_turu` varchar(100) DEFAULT NULL COMMENT 'Tekrar türü açıklaması',
  `status` enum('scheduled','completed','cancelled') DEFAULT 'scheduled',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `uses_time_slots` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `vp_activities`
--

INSERT INTO `vp_activities` (`id`, `area_id`, `activity_name`, `activity_type_id`, `activity_date`, `start_time`, `end_time`, `responsible_person`, `notes`, `created_by`, `is_recurring`, `recurring_rule`, `recurrence_type`, `recurrence_count`, `parent_activity_id`, `ana_etkinlik_id`, `tekrar_durumu`, `tekrar_turu`, `status`, `created_at`, `updated_at`, `uses_time_slots`) VALUES
(104, 1, 'asda asdasd', NULL, '2025-10-24', '10:00:00', '11:00:00', '', '', 1, 0, NULL, NULL, NULL, NULL, NULL, 'hayir', NULL, 'scheduled', '2025-10-23 21:59:01', '2025-10-23 21:59:01', 1),
(105, 9, 'adadakda', NULL, '2025-10-24', '09:00:00', '11:00:00', 'sdasdasdas', 'asdasdasd', 1, 0, NULL, NULL, NULL, NULL, NULL, 'hayir', NULL, 'scheduled', '2025-10-23 22:09:52', '2025-10-23 22:09:52', 1),
(106, 9, 'dsasd asdsa', NULL, '2025-10-24', '11:00:00', '12:30:00', 'adsasd', 'asdasd', 1, 0, NULL, NULL, NULL, NULL, NULL, 'hayir', NULL, 'scheduled', '2025-10-23 22:10:19', '2025-10-23 22:10:19', 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `vp_activity_areas`
--

CREATE TABLE `vp_activity_areas` (
  `id` int(11) NOT NULL,
  `area_name` varchar(100) NOT NULL,
  `area_image` varchar(255) DEFAULT NULL,
  `color_code` varchar(7) DEFAULT '#3B82F6',
  `is_active` tinyint(1) DEFAULT 1,
  `default_slot_duration` int(11) DEFAULT 30,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `vp_activity_areas`
--

INSERT INTO `vp_activity_areas` (`id`, `area_name`, `area_image`, `color_code`, `is_active`, `default_slot_duration`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'KONFERANS SALONU', 'area_1760464185_4154.jpg', '#3b82f6', 1, 30, 1, '2025-10-14 16:49:45', '2025-10-23 21:56:42'),
(9, 'Cep Sineması', 'area_1760558119_4963.jpg', '#6d2876', 1, 30, 2, '2025-10-15 18:55:19', '2025-10-15 18:56:19'),
(10, 'Hasan Yılmaz Toplantı Salonu', 'area_1760558521_8080.jpg', '#7c838d', 1, 30, 3, '2025-10-15 19:02:01', '2025-10-15 19:02:01'),
(11, 'Lise Toplantı Salonu', 'area_1760558561_8615.jpeg', '#e1e6ef', 1, 30, 4, '2025-10-15 19:02:41', '2025-10-15 19:02:41'),
(14, '3. KAT TOPLANTI SALONU', 'area_1761255966_7711.jpeg', '#a9aaad', 1, 30, 5, '2025-10-23 21:46:06', '2025-10-23 21:46:06');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `vp_activity_time_slots`
--

CREATE TABLE `vp_activity_time_slots` (
  `activity_id` int(11) NOT NULL,
  `time_slot_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `vp_activity_time_slots`
--

INSERT INTO `vp_activity_time_slots` (`activity_id`, `time_slot_id`, `created_at`) VALUES
(103, 1, '2025-10-19 18:51:34'),
(103, 2, '2025-10-19 18:51:34'),
(104, 4, '2025-10-23 22:08:53'),
(104, 5, '2025-10-23 22:08:53'),
(105, 1, '2025-10-23 22:09:52'),
(105, 2, '2025-10-23 22:09:52'),
(105, 3, '2025-10-23 22:09:52'),
(105, 4, '2025-10-23 22:09:52'),
(106, 5, '2025-10-23 22:10:19'),
(106, 6, '2025-10-23 22:10:19'),
(106, 7, '2025-10-23 22:10:19');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `vp_activity_types`
--

CREATE TABLE `vp_activity_types` (
  `id` int(11) NOT NULL,
  `type_name` varchar(100) NOT NULL,
  `color_code` varchar(7) DEFAULT '#10B981',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `vp_activity_types`
--

INSERT INTO `vp_activity_types` (`id`, `type_name`, `color_code`, `is_active`, `created_at`) VALUES
(1, 'Seminer', '#3B82F6', 1, '2025-10-08 19:18:53'),
(2, 'Sınav', '#EF4444', 1, '2025-10-08 19:18:53'),
(3, 'Veli Toplantısı', '#10B981', 1, '2025-10-08 19:18:53'),
(4, 'Toplantı', '#F59E0B', 1, '2025-10-08 19:18:53'),
(5, 'Diğer', '#6366F1', 1, '2025-10-08 19:18:53');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `vp_allowed_google_emails`
--

CREATE TABLE `vp_allowed_google_emails` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `added_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `vp_etut_applications`
--

CREATE TABLE `vp_etut_applications` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `tc_no` varchar(11) DEFAULT NULL COMMENT 'TC Kimlik No',
  `full_name` varchar(255) DEFAULT NULL COMMENT 'Ad Soyad',
  `grade` varchar(10) DEFAULT NULL COMMENT 'S??n??f',
  `parent_phone` varchar(20) DEFAULT NULL COMMENT 'Veli Telefon',
  `student_phone` varchar(20) DEFAULT NULL COMMENT '????renci Telefon',
  `address` text DEFAULT NULL COMMENT 'Adres',
  `application_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `teacher_name` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','approved','rejected','completed') DEFAULT 'pending',
  `form_type` enum('ortaokul','lise') DEFAULT NULL COMMENT 'Ba??vuru yap??lan form tipi',
  `created_by` int(11) DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `vp_etut_applications`
--

INSERT INTO `vp_etut_applications` (`id`, `student_id`, `tc_no`, `full_name`, `grade`, `parent_phone`, `student_phone`, `address`, `application_date`, `start_time`, `end_time`, `subject`, `teacher_name`, `notes`, `status`, `form_type`, `created_by`, `approved_by`, `created_at`, `updated_at`) VALUES
(23, 1, '34324', 'wqasd asdas', '11.SINIF', '', '', 'asdasdad', '2025-10-18', '00:00:00', '00:00:00', 'BİYOLOJİ', NULL, 'dsadadsa', 'completed', 'lise', NULL, 1, '2025-10-18 03:28:09', '2025-10-18 04:05:32'),
(24, 1, '234234', 'asd sad', '12.SINIF', '', '', '', '2025-10-18', '00:00:00', '00:00:00', 'DİN KÜLTÜRÜ', NULL, 'asdasdads', 'completed', 'lise', NULL, 1, '2025-10-18 03:29:04', '2025-10-18 04:05:32'),
(25, 1, '2423', 'asdasd', '10.SINIF', '', '', 'asdasdasd', '2025-10-18', '00:00:00', '00:00:00', 'TARİH', NULL, 'adsadsa sad', 'completed', 'lise', NULL, 1, '2025-10-18 03:30:25', '2025-10-18 04:05:22');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `vp_etut_form_settings`
--

CREATE TABLE `vp_etut_form_settings` (
  `id` int(11) NOT NULL,
  `form_type` enum('ortaokul','lise') NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `closed_message` text DEFAULT NULL,
  `max_applications_per_student` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `vp_etut_form_settings`
--

INSERT INTO `vp_etut_form_settings` (`id`, `form_type`, `is_active`, `title`, `description`, `closed_message`, `max_applications_per_student`, `created_at`, `updated_at`) VALUES
(4, 'ortaokul', 1, 'Vildan Ortaokul Etüt Başvuru Formu', 'Lütfen tüm alanları eksiksiz doldurunuz.', 'Değerli Öğrencimiz,\r\nŞuanda Başvuru Kabul Edilememektedir. Form Yetkililer Tarafından Kapatıldı. Lütfen Daha Sonra Tekrar Deneyiniz.', 0, '2025-10-17 15:02:34', '2025-10-23 20:28:33'),
(5, 'lise', 1, 'Vildan Lise Etüt Başvuru Formu', 'Lütfen tüm alanları eksiksiz doldurunuz.', 'Değerli Öğrencimiz,\r\nŞuanda Başvuru Kabul Edilememektedir. Form Yetkililer Tarafından Kapatıldı. Lütfen Daha Sonra Tekrar Deneyiniz.', 0, '2025-10-17 15:02:34', '2025-10-18 04:40:13');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `vp_pages`
--

CREATE TABLE `vp_pages` (
  `id` int(11) NOT NULL,
  `page_key` varchar(50) NOT NULL,
  `page_name` varchar(100) NOT NULL,
  `page_url` varchar(255) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `vp_pages`
--

INSERT INTO `vp_pages` (`id`, `page_key`, `page_name`, `page_url`, `parent_id`, `sort_order`, `is_active`, `created_at`) VALUES
(1, 'dashboard', 'Ana Sayfa', '/dashboard', NULL, 1, 1, '2025-10-08 19:18:52'),
(2, 'student_search', 'Öğrenci Ara', '/student-search', NULL, 2, 1, '2025-10-08 19:18:52'),
(3, 'students', 'Öğrenci Bilgileri', '/students', NULL, 3, 1, '2025-10-08 19:18:52'),
(4, 'activities', 'Etkinlikler', '/activities', NULL, 4, 1, '2025-10-08 19:18:52'),
(5, 'activity_areas', 'Etkinlik Alanları', '/activity-areas', NULL, 5, 1, '2025-10-08 19:18:52'),
(6, 'etut', 'Etüt Başvuruları', '/etut', NULL, 6, 1, '2025-10-08 19:18:52'),
(7, 'users', 'Kullanıcılar', '/users', NULL, 7, 1, '2025-10-08 19:18:52'),
(8, 'settings', 'Ayarlar', '/settings', NULL, 8, 1, '2025-10-08 19:18:52'),
(9, 'time_slots', 'Saat Ayarları', '/time-slots', 8, 1, 1, '2025-10-08 19:18:52'),
(10, 'system_settings', 'Sistem Ayarları', '/system-settings', 8, 2, 1, '2025-10-08 19:18:52'),
(11, 'etut_area', 'Et??t Alan??', '/etut', NULL, 6, 1, '2025-10-16 16:21:24'),
(12, 'etut_ortaokul', 'Ortaokul Et??t', '/etut?type=ortaokul', 11, 1, 1, '2025-10-16 16:21:24'),
(13, 'etut_lise', 'Lise Et??t', '/etut?type=lise', 11, 2, 1, '2025-10-16 16:21:24'),
(14, 'etut_settings', 'Et??t Form Ayarlar??', '/admin/etut-settings', 11, 3, 1, '2025-10-16 16:21:24');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `vp_password_resets`
--

CREATE TABLE `vp_password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `used` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `vp_recurring_rules`
--

CREATE TABLE `vp_recurring_rules` (
  `id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `recurrence_type` enum('daily','weekly','monthly','custom') NOT NULL,
  `recurrence_interval` int(11) DEFAULT 1,
  `days_of_week` varchar(20) DEFAULT NULL COMMENT '1,2,3,4,5 (Pzt-Cum)',
  `end_date` date DEFAULT NULL,
  `occurrences` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `vp_roles`
--

CREATE TABLE `vp_roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `vp_roles`
--

INSERT INTO `vp_roles` (`id`, `role_name`, `display_name`, `sort_order`, `description`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Admin', 1, 'Tam yetki', '2025-10-08 19:18:52', '2025-10-12 20:53:51'),
(2, 'teacher', 'Öğretmen', 2, 'Öğretmen yetkisi', '2025-10-08 19:18:52', '2025-10-12 20:53:51'),
(3, 'secretary', 'Sekreter', 3, 'Sekreter yetkisi', '2025-10-08 19:18:52', '2025-10-12 20:53:51'),
(4, 'principal', 'Okul Müdürü', 4, 'Okul müdürü yetkisi', '2025-10-08 19:18:52', '2025-10-12 20:53:51'),
(5, 'vice_principal', 'Müdür Yardımcısı', 5, 'Müdür yardımcısı yetkisi', '2025-10-08 19:18:52', '2025-10-12 20:53:51');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `vp_role_page_permissions`
--

CREATE TABLE `vp_role_page_permissions` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `can_view` tinyint(1) DEFAULT 0,
  `can_create` tinyint(1) DEFAULT 0,
  `can_edit` tinyint(1) DEFAULT 0,
  `can_delete` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `vp_role_page_permissions`
--

INSERT INTO `vp_role_page_permissions` (`id`, `role_id`, `page_id`, `can_view`, `can_create`, `can_edit`, `can_delete`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 1, 1, '2025-10-23 15:34:21', '2025-10-23 15:34:21'),
(2, 1, 2, 1, 1, 1, 1, '2025-10-23 15:34:21', '2025-10-23 15:34:21'),
(3, 1, 3, 1, 1, 1, 1, '2025-10-23 15:34:21', '2025-10-23 15:34:21'),
(4, 1, 4, 1, 1, 1, 1, '2025-10-23 15:34:21', '2025-10-23 15:34:21'),
(5, 1, 5, 1, 1, 1, 1, '2025-10-23 15:34:21', '2025-10-23 15:34:21'),
(6, 1, 6, 1, 1, 1, 1, '2025-10-23 15:34:21', '2025-10-23 15:34:21'),
(7, 1, 7, 1, 1, 1, 1, '2025-10-23 15:34:21', '2025-10-23 15:34:21'),
(79, 3, 1, 1, 1, 1, 1, '2025-10-23 21:33:53', '2025-10-23 21:40:39'),
(80, 3, 2, 1, 1, 1, 1, '2025-10-23 21:33:53', '2025-10-23 21:40:39'),
(81, 3, 3, 1, 1, 1, 1, '2025-10-23 21:33:53', '2025-10-23 21:40:39'),
(82, 3, 4, 1, 1, 1, 1, '2025-10-23 21:33:53', '2025-10-23 21:40:39'),
(83, 3, 5, 1, 1, 1, 1, '2025-10-23 21:33:53', '2025-10-23 21:40:39'),
(84, 3, 6, 0, 0, 0, 0, '2025-10-23 21:33:53', '2025-10-23 21:33:53'),
(85, 3, 7, 0, 0, 0, 0, '2025-10-23 21:33:53', '2025-10-23 21:33:53'),
(86, 3, 8, 0, 0, 0, 0, '2025-10-23 21:33:53', '2025-10-23 21:33:53'),
(87, 3, 9, 0, 0, 0, 0, '2025-10-23 21:33:53', '2025-10-23 21:33:53'),
(88, 3, 10, 0, 0, 0, 0, '2025-10-23 21:33:53', '2025-10-23 21:33:53'),
(89, 3, 11, 0, 0, 0, 0, '2025-10-23 21:33:53', '2025-10-23 21:33:53'),
(90, 3, 12, 0, 0, 0, 0, '2025-10-23 21:33:53', '2025-10-23 21:33:53'),
(91, 3, 13, 0, 0, 0, 0, '2025-10-23 21:33:53', '2025-10-23 21:33:53'),
(92, 3, 14, 0, 0, 0, 0, '2025-10-23 21:33:53', '2025-10-23 21:33:53'),
(93, 2, 1, 0, 0, 0, 0, '2025-10-23 21:39:29', '2025-10-23 21:39:29'),
(94, 2, 2, 1, 0, 0, 0, '2025-10-23 21:39:29', '2025-10-23 21:39:29'),
(95, 2, 3, 0, 0, 0, 0, '2025-10-23 21:39:29', '2025-10-23 21:39:29'),
(96, 2, 4, 0, 0, 0, 0, '2025-10-23 21:39:29', '2025-10-23 21:39:29'),
(97, 2, 5, 0, 0, 0, 0, '2025-10-23 21:39:29', '2025-10-23 21:39:29'),
(98, 2, 6, 0, 0, 0, 0, '2025-10-23 21:39:29', '2025-10-23 21:39:29'),
(99, 2, 7, 0, 0, 0, 0, '2025-10-23 21:39:29', '2025-10-23 21:39:29'),
(100, 2, 8, 0, 0, 0, 0, '2025-10-23 21:39:29', '2025-10-23 21:39:29'),
(101, 2, 9, 0, 0, 0, 0, '2025-10-23 21:39:29', '2025-10-23 21:39:29'),
(102, 2, 10, 0, 0, 0, 0, '2025-10-23 21:39:29', '2025-10-23 21:39:29'),
(103, 2, 11, 0, 0, 0, 0, '2025-10-23 21:39:29', '2025-10-23 21:39:29'),
(104, 2, 12, 0, 0, 0, 0, '2025-10-23 21:39:29', '2025-10-23 21:39:29'),
(105, 2, 13, 0, 0, 0, 0, '2025-10-23 21:39:29', '2025-10-23 21:39:29'),
(106, 2, 14, 0, 0, 0, 0, '2025-10-23 21:39:29', '2025-10-23 21:39:29');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `vp_students`
--

CREATE TABLE `vp_students` (
  `id` int(11) NOT NULL,
  `tc_no` varchar(11) DEFAULT NULL COMMENT 'TC kimlik no (boş olabilir)',
  `first_name` varchar(100) NOT NULL COMMENT 'İsim',
  `last_name` varchar(100) NOT NULL COMMENT 'Soyisim',
  `class` varchar(50) DEFAULT NULL COMMENT 'Sınıfı',
  `birth_date` date DEFAULT NULL,
  `father_name` varchar(100) DEFAULT NULL,
  `father_phone` varchar(20) DEFAULT NULL,
  `mother_name` varchar(100) DEFAULT NULL,
  `mother_phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `teacher_name` varchar(100) DEFAULT NULL,
  `teacher_phone` varchar(20) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `vp_students`
--

INSERT INTO `vp_students` (`id`, `tc_no`, `first_name`, `last_name`, `class`, `birth_date`, `father_name`, `father_phone`, `mother_name`, `mother_phone`, `address`, `teacher_name`, `teacher_phone`, `notes`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(1329, '30979411262', 'ELİSA', 'EYÜPOĞLU', 'ANASINIFI-E', '2022-11-13', 'YUŞA EYÜPOĞLU', '05325937347', 'AYŞEGÜL EYÜPOĞLU', '05345197347', '', 'MERYEM DOĞAN', '05319412731', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1330, '38527161508', 'DUYGU NECLA', 'İNCE', '8.SINIF', '2012-01-01', 'AHMET ALİ İNCE', '05066106698', 'AYŞE İNCE', '05066106696', '', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1331, '24706622076', 'BERRENUR', 'YOLLU', '8.SINIF', '2012-12-12', 'İSMAİL YOLLU', '05518385866', 'NURTEN YOLLU', '05058171323', '', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1332, '32758353848', 'ZEYNEP', 'KOCABIYIK', '9-A', NULL, 'MEHMET KOCABIYIK', '05323735378', 'PERİHAN KOCABIYIK', '05383456060', 'KARAHASANLI MAH. 2008 SOK.N:1 NİLSU SİTESİ A BLOK', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1333, NULL, 'YUSUF EYMEN', 'KARTAL', '10-A', NULL, 'İSMAİL KARTAL', '05323753959', 'BİLGİN KARTAL', '05448346840', '', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1334, '15913152142', 'SELMA NUR', 'ALTUN', '5-A', NULL, 'YÜCEL ALTUN', '05334626377', 'HAVVA ALTUN', '05551705154', '', 'ARZU SAKMAN', '05522429911', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1335, NULL, 'ÖZNUR NİDA', 'ULUDAĞ', '11-A', NULL, 'ÖZGÜR ULUDAĞ', '05426458437', 'AYLİN ULUDAĞ', '05074172712', '', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1336, '28525494234', 'ZEYNEP GÜL', 'ONA', '4-C', '2016-06-21', 'BEKİR ONA', '05327616869', 'ÜMRAN ONA', '05545324273', '', 'MEHMET ZAHİD YILMAZ', '05465511553', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1337, NULL, 'ERDEM', 'YILMAZ', '4-C', NULL, 'OKAN YILMAZ', '', '', '', '', 'MEHMET ZAHİD YILMAZ', '05465511553', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1338, '15214395774', 'İSMAİL', 'DOĞAN', '11.SINIF', '2008-06-14', 'SADIK DOĞAN', '05059540051', 'MEDİNE DOĞAN', '05377657722', 'SARUHAN MAH 12002 SOK NO:3 D:3', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1339, '12716019412', 'ÖMER FARUK', 'AHRAZ', '12.SINIF', '2008-03-17', 'İBRAHİM AHRAZ', '05363522056', 'HATİCE AHRAZ', '05366074570', '', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1340, '12233035032', 'ÖMER ASAF', 'BIYIKOĞLU', '12.SINIF', NULL, 'İSMAİL BIYIKOĞLU', '05327971704', 'AYNUR BIYIKOĞLU', '05384202615', '', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1341, '15895913090', 'SEYFULLAH HALİT', 'IŞIK', '11-A', '2009-09-25', 'FATİH IŞIK', '05353406874', 'RAZİYE IŞIK', '05353406874', '', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1342, NULL, 'TOPRAK BURAK', 'AKÇAM', '9-A', NULL, 'OYTUN AKÇAM', '05325091358', 'ALİYE AKÇAM', '05385083618', '', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1343, '30394432828', 'EGEHAN', 'YARENOĞLU', 'ANASINIF-D', '2021-04-06', 'YASİN YARENOĞLU', '05332172056', 'AYLA YARENOĞLU', '05348903739', 'SERVERGAZİ MAH. ZAMBAK SOK. NO:7', 'FATMA CEYLAN', '05432659829', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1344, NULL, 'CEMAL', 'DAĞDELEN', '9-A', NULL, 'UĞUR DAĞDELEN', '05337483599', 'MELEK DAĞDELEN', '05302208226', '', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1345, NULL, 'AYŞENUR SERRA', 'ÇEMEN', '10-A', NULL, 'MURAT ÇEMEN', '05052926874', 'HATİCE ÇEMEN', '05326074170', '', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1346, '42751020180', 'MEHMET SAİD', 'AVCIK', '9-A', '2011-04-12', 'GÖKHAN AVCIK', '05377321272', 'TUĞBA AVCIK', '05362655673', '', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1347, NULL, 'NUR', 'FİDAN', '11-A', NULL, '', '', 'NACİYE ZEYREK', '05441524488', '', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1348, NULL, 'SİNEM', 'TÜRKMEN', '11-A', NULL, 'SALİH TÜRKMEN', '05322809195', 'ŞEYDA TÜRKMEN', '05322752185', 'ŞEMİKLER MAH. CİNKAYA BUL. IRMAK KENT SİTESİ N:104 K:1 D:2', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1349, NULL, 'CEYLİN', 'KÖKER', '11-A', NULL, 'İSMAİL KÖKER', '05323507027', 'ZEYNEP KÖKER', '05325105009', 'YUNUSEMRE MAH. TOKAT CAD.GAZİ KENT SİTESİ N:70 B BLOK K.1 D:1', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1350, NULL, 'ÖMER FARUK', 'HANÇAR', '11-A', NULL, 'FEHMİ HANÇAR', '05322436164', 'SELDA HANÇAR', '05559827778', 'ATATÜRK MAH. CEVAT VURAL SOK. NO.36/3', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1351, NULL, 'TALHA', 'AKYOL', '11-A', NULL, 'TAYFUN AKYOL', '05322921807', 'TUBA ÇAKMAK', '05303941984', 'HALLAÇLAR MAH. 3050 SOK.N:16 SAFİR KONAĞI K:3 D.5', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1352, NULL, 'FURKAN ESAD', 'KOCAKAYA', '12.SINIF', NULL, 'ABBAS KOCAKAYA', '05336219928', 'SEMA EMİNE KOCAKAYA', '05372870599', 'YENİŞEHİR MAH.26 SOK. N:10 ORMAN APT.', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1353, NULL, 'BURAK', 'ÜÇER', '9-A', NULL, 'AHMET ÜÇER', '05323867655', 'EŞE ÜÇER', '05375709238', 'YENİŞAFAK MAH. 1034 SOK. N:3 K:4 D.16 KIYAK APT.', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1354, '42856016002', 'ALPER', 'BAYINDIR', '11-A', '2009-03-21', 'MEHMET BAYINDIR', '05352908055', 'PEMBE BAYINDIR', '05398273057', '', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1355, '34654289832', 'MUHAMMET YUNUS', 'ACAR', '9-A', '2011-04-01', 'YUSUF ACAR', '05519575793', 'ÜMRAN ACAR', '05522077789', 'İNCİLİPINAR MAH.3378 SOK. NO:33/1', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1356, '40507094874', 'ZEYNEP', 'KARAKAYA', '12.SINIF', NULL, 'HÜSEYİN KARAKAYA', '05352299034', 'ŞÜKRAN KARAKAYA', '05376294650', '', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1357, NULL, 'BETÜL', 'KOÇ', '12.SINIF', NULL, 'TACETTİN KOÇ', '05382690043', 'HAKİME KOÇ', '05398273234', 'YENİŞAFAK MAH.1133 SOK.NO:6 D:5', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1358, '41233531428', 'FATIMA BEYZA', 'GÜLÇER', '12.SINIF', '2008-01-31', '', '', 'LATİFE ÇELİKER', '05052166988', '', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1359, NULL, 'EMRE EDWARD', 'MADRAN', '12.SINIF', NULL, '', '', 'BAŞAK LAMAN MADRAN', '05511466577', '', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1360, '12635023620', 'EFE KAĞAN', 'KARADAĞ', '12.SINIF', '2008-02-07', 'İDRİS KARADAĞ', '05354523343', 'ELİF', '05415592938', '', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1361, '27892513456', 'FATMA SUDE', 'SERBAN', '12.SINIF', NULL, 'MEHMET SERBAN', '05306512063', 'KAMİLE AKYOL', '05076622416', 'SARAYKÖY', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1362, '38605156914', 'ZEYNEP', 'ARABACI', '12.SINIF', '2009-02-05', 'YASİN ARABACI', '05056973610', 'ŞÜKRAN ARABACI', '05055810232', 'FESLEĞEN MAH 1004 SOK NO:12 K:2 D:5', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1363, '41983046364', 'KEREM FARUK', 'KAHRAMAN', '12.SINIF', '2008-11-03', 'AHMET KAHRAMAN', '05354343660', 'AYŞE KAHRAMAN', '05392582684', 'ZÜMRÜT MH. VATAN BULV. NO:209 K:3  BAĞBAŞI', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1364, '24091642604', 'ŞEHRİNAZ', 'YILDIRIM', '12.SINIF', '2008-10-17', 'OKAN YILDIRIM', '05548810888', 'KERİMAN YILDIRIM', '05323782676', 'ZÜMRÜT MH. HUZUR CD. SOYLU SİT. A BLK. K:5 D:12', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1365, '12578023480', 'ABDULLAH TAHA', 'KARATAŞ', '12.SINIF', '2008-01-12', 'YAHYA KARATAŞ', '05556111449', 'HATİCE KARATAŞ', '05558058371', 'ZÜMRÜT MAH 2102 SOK NO:11 ORKİDE APT', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1366, '25357597430', 'BAHRİYE', 'DEMİR', '12.SINIF', '2008-11-26', 'ÜNAL DEMİR', '05355768125', 'FADİME DEMİR', '05385055531', 'ŞEMİKLER MAH OSMAN GAZİ CAD DEMİRCİLER SİT B BLOK K:1', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1367, '11237426242', 'EBRAR', 'TOYOĞLU', '12.SINIF', NULL, 'HAKAN TOYOĞLU', '05356134954', 'SEMİHA TOYOĞLU', '05313600675', 'SİNPAŞ KONUTLARI', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1368, NULL, 'CEMİL BURAK', 'KARAEL', '12.SINIF', NULL, 'HALİL KARAEL', '05325037860', 'PAKİZE KARAEL', '05342032382', '', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1369, '37639189882', 'FURKAN', 'KESER', '12.SINIF', '2008-08-04', 'NECİP KESER', '05322231214', 'YASEMİN KESER', '05523329422', 'YENİMAHALLE 5022 SOK NO:3 K:2', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1370, '12656021698', 'BERRA', 'AYYILDIZ', '12.SINIF', '2008-02-14', 'MUSTAFA AYYILDIZ', '05058054626', 'AYSUN AYYILDIZ', '05058054636', 'ŞİRİNKÖY MAH 12213 SOK NO:11 K:2', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1371, '24682620202', 'NAHİDE NUR', 'TOKAT', '12.SINIF', '2008-05-23', 'MEHMET TOKAT', '05326147366', 'HATİCE TOKAT', '05545829543', 'ŞEMİKLER MAH 3003 SOK UZUN YAŞAM KONUTLARI A BLOK D :15', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1372, '36364233112', 'REYYAN', 'ÇALIŞKAN', '12.SINIF', NULL, 'ALİ CEVAT ÇALIŞKAN', '05324308580', 'ASUMAN ÇALIŞKAN', '05372670403', 'KARAHASANLI MAH 2018 SOK ANADOLU KASRI SİTESİ B BLOK K:1 D:4', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1373, '14407964476', 'ZEYNEP', 'YILDIZ', '12.SINIF', '2008-09-01', 'ÖZGÜR YILDIZ', '05339309119', 'YEŞİM YILDIZ', '05061450239', 'GÜMÜŞÇAY MAH ÇİĞDEM CAD 4217 SOK NO:3', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1374, '31450394302', 'İREM', 'CEYHAN', '12.SINIF', '2008-04-24', 'DURALİ CEYHAN', '05354511221', 'YASEMİN CEYHAN', '05531702216', 'FATİH MAH 1920/1 NO:4 K:2', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1375, '34249301994', 'ÜMMÜHAN', 'ÖZMEN', '12.SINIF', '2009-01-23', 'TARIK ÖZMEN', '05424664196', 'YAŞAGÜL ÖZMEN', '05422268208', 'DOKUZKAVAKLAR MAH 2033 SOK NO:14', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1376, NULL, 'AYŞEGÜL', 'AKINCI', '12.SINIF', NULL, 'AHMET AKINCI', '05543026672', 'NAZİFE AKINCI', '05303226983', 'DEĞİRMENÖNÜ MAH 1425 SOK NO:9 K:2 D:7 CANEVLER D BLOK', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1377, '26545565132', 'RUKİYE FEYZA', 'DEMİRBİLEK', '12.SINIF', '2008-08-27', 'MUSTAFA DEMİRBİLEK', '05057970922', 'AYŞE DEMİRBİLEK', '05057970921', 'ADALET MAH VATAN CAD SEVGİ SİTESİ 7B K:3 D:10', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1378, '12518027578', 'FATMA ZEHRA', 'KARABULUT', '12.SINIF', '2007-11-30', 'ALİ RIZA KARABULUT', '05352995828', 'SEMRA KARABULUT', '05358668549', '1200 EVLER MAH 2011 SOK NO:21 SU APT K: D:6', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1379, NULL, 'İREM', 'ÇIRACI', '12.SINIF', '2008-07-16', 'SERKAN ÇIRACI', '05358188093', 'AYSUN ÇIRACI', '05305234022', 'SERLÇUKBEY MAH ŞEHİT PİYD KOMNDO ER MEHMET AVCI CAD NO:33', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1380, '10984741240', 'ÖMER YAVUZ', 'ERKOÇ', '12.SINIF', '2008-04-04', 'SELİM ERKOÇ', '05053936630', 'ÇİĞDEM ERKOÇ', '05056745936', 'SELÇUKBEY MH. Ş.P. ER MEHMET AVCI CD. NO:25 CANTÜRK', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1381, '39901113502', 'BÜŞRA', 'ASLAN', '12.SINIF', '2008-07-01', 'İZZET ASLAN', '05352299027', 'HÜLYA ASLAN', '05383511156', 'MERKEZEFENDİ MAH 226 SOK NO:98 K:3', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1382, '12380530800', 'MEHMET ADİL', 'KALKINÇ', '12.SINIF', '2007-08-14', 'ÖMER FARUK KALKINÇ', '05447661944', 'SEHER KALKINÇ', '05065056278', 'MEHMETÇİK MH. 2561 SK. NO:11 K:3', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1383, '18955217558', 'SERRA GÜLSÜM', 'GÜLTEKİN', '12.SINIF', '2008-11-07', 'MUSTAFA GÜLTEKİN', '05423420152', 'NESRİN GÜLTEKİN', '05448140413', 'KARAHASANLI MAH 2008 SOK ADLİYE LOJMANLARI NO:16 D4 BLOK N19', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1384, '31759385360', 'AYSİMA', 'KAYA', '12.SINIF', '2008-06-27', 'SIRACETTİN TALİ', '05423449401', 'FATMA TALİ', '05453441395', 'GÜLTEPE MAH N.KEMAL CAD ELİFSU SİTESİ2 K:2 D:4 H90 GÜL APT', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1385, '12752017588', 'CEYLİN', 'ULUTAŞ', '12.SINIF', '2008-04-02', 'MİKAİL ULUTAŞ', '05325435105', 'HATİCE ULUTAŞ', '05053783385', 'GÜLTEPE MAH 4833 SOK NO:3/ K:4', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1386, '19651788588', 'EMİN BURAK', 'GÖKGÖZ', '12.SINIF', '2008-07-01', 'ALİ GÖKGÖZ', '05327991716', 'FATMA GÖKGÖZ', '05336476084', 'GERZELE MAH YEŞİLVADİ CAD NO:43 B BLOK K:5', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1387, '12767019812', 'FATIMA', 'KOCAMAN', '12.SINIF', '2008-04-09', 'İSMAİL KOCAMAN', '05322506208', 'NUR KOCAMAN', '05368559780', 'GERZELE MAH GERİZ CA NO:49 1000 YIL SİTESİ K:3 D:7', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1388, '39769117766', 'MAHMUT SAMİ', 'ŞAHİN', '12.SINIF', '2003-05-10', 'MUSTAFA ŞAHİN', '05326957347', 'UMMAHAN ŞAHİN', '05353935474', 'FATİH MH. 1896 SK. NO:2', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1389, NULL, 'ZÜHRE', 'BALTA', '12.SINIF', '2008-04-11', 'FERHAT BALTA', '05318828222', 'FİKRİYE BALTA', '05556346687', 'FATİH MAH 2028 SOK NO:17 K:1', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1390, '20074775260', 'CEMAL PEKDEMİR', 'DAĞ', '12.SINIF', '2008-08-18', 'YUSUF DAĞ', '05333084849', 'SANİYE DAĞ', '05362921925', 'ÇAMLARALTI MAH.MEVLANA CAD.NO:23 KINIKLI /PAMUKKALE', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1391, '33154339214', 'LEYLA', 'BAYSAL', '12.SINIF', '2008-07-18', 'VEDAT BAYSAL', '05325678374', 'ÇİĞDEM', '05496266265', 'CUMHURİYET MH. 3394 SK. NO:31', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1392, '16186028776', 'MÜGE BETÜL', 'ÇELİKER', '12.SINIF', '2008-02-01', 'BEKİR ÇELİKER', '05055499183', 'TÜLİN ÇELİKER', '05055014813', 'ALPARSLAN TÜRKEŞ BULV. YENİŞAFAK MH. NO:59 FİRUZE SİT.D:10', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1393, '32980346410', 'ECE ZEYNEP', 'İM', '12.SINIF', '2008-11-04', 'YUSUF İM', '05055811512', 'ASİYE İM', '05055811513', 'AKTEPE MAH 2408 SOK NO:26 K:2', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1394, '20545760122', 'SÜMEYYE ZEYNEP', 'ÖZER', '12.SINIF', '2008-09-18', 'BEKİR ÖZER', '05383757777', 'EMEL ÖZER', '05377738928', 'ADALET MAH 10053 SOK NO:20/1 AYYILDIZ APT', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1395, NULL, 'MUSA SOYDAN', 'ŞAHİN', '11.SINIF', NULL, 'SERKAN ESMER', '05322137917', 'HÜLYA ŞAHİN', '05373650144', '', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1396, NULL, 'TUĞSEM', 'UZUN', '11.SINIF', NULL, 'EMRE UZUN', '05322559461', 'TUĞBA UZUN', '05066775545', 'SELÇUKBEY MAH.551 SOK. KARDELEN SİTESİ K:4 D:9 B BLOK', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1397, NULL, 'ZEYNEP EBRAR', 'ŞAHAN', '11.SINIF', NULL, 'ÇETİN ŞAHAN', '05366307080', 'FİLİZ ŞAHAN', '05396522026', '', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1398, NULL, 'ÖMER', 'KARABABA', '11.SINIF', NULL, 'MEHMET KARABABA', '05324121128', 'MÜRÜVVET KARABABA', '05304627344', 'GERZELE MAH.585 SOK.N:1 VADİ APT K:3 D:9', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1399, '37928287398', 'ZEYNEP DİLARA', 'GÜLER', '11.SINIF', NULL, 'SELMAN GÜLER', '05326767511', 'AYŞE GÜLER', '05326711813', '', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1400, '25838267562', 'MUHAMMED BEDİRHAN', 'ÖZSÜZER', '11.SINIF', '2008-12-28', 'YILMAZ ÖZSÜZER', '05352644989', 'NURCAN ÖZSÜZER', '05353603833', 'GÜLTEPE MAH 4309 SOK/1 NO:17 K:1 D:1', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1401, '27157539504', 'CEYLİN', 'SİNKİL', '11.SINIF', '2009-04-13', 'MUSTAFA ALİ SİNKİL', '05324076507', 'SULTAN SİNKİL', '05333761220', '', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1402, '18940813970', 'BERİL', 'ÖNCAN', '11.SINIF', NULL, 'MURAT ÖNCAN', '05369711921', 'EVREN ÇAĞLAYAN ÖNCAN', '05323743122', 'SELÇUKBEY MAH. MEHMET AVCI CAD. 638 SOK. NO:23/G SERHAN SİTE', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1403, '36112241178', 'MELİH EREN', 'ESKİN', '11.SINIF', '2009-04-16', 'RAMAZAN ESKİN', '05055974225', 'KÜBRA ESKİN', '05556865122', 'MERKEZEFENDİ MH. 1700/6 SK. NO:8 ÇINAR APT. K:1', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1404, '26872549508', 'ELMAS SU', 'ŞEMSİOĞLU', '11.SINIF', '2009-05-13', 'HASAN ŞEMSİOĞLU', '05349722695', 'MERYEM ŞEMSİOĞLU', '05349830097', 'KERVANSARAY MAH 3124 SOKAK 2D BİNA', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1405, '15709920474', 'BEYZA', 'ÇİFTÇİ', '11.SINIF', '2009-12-01', 'SAYİM ÇİFTÇİ', '05332204981', 'UMMAHAN ÇİFTÇİ', '05301550695', 'ZEYTİNKÖY MAH BARBAROS BULV NO:59 K:2 D:7', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1406, '21760717854', 'ZEYNEP', 'ERSOY', '11.SINIF', '2009-10-15', 'YILDIRAY ERSOY', '05323216378', 'SEMRA', '05309231157', 'YENİŞEHİR MH. 80 SK. NO:22', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1407, '15325939546', 'ESLEM', 'YİĞİT', '11.SINIF', '2009-08-19', 'ÖZCAN YİĞİT', '05301550593', 'GÜLİZAR', '05301550592', 'YENİŞEHİR MAH. 21 SOK. NO:1', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1408, NULL, 'PELİNSU', 'DEMİR', '11.SINIF', NULL, 'CEMİL DEMİR', '05053573633', 'TÜRKAN DEMİR', '05053573622', 'YENİŞEHİR MAH IHLAMUR KONAKLARI E BLOK D:5', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1409, NULL, 'HALİL İBRAHİM', 'GÜRSOY', '11.SINIF', NULL, 'OSMAN GÜRSOY', '05337339475', 'HATİCE KÜBRA GÜRSOY', '05359295540', 'YENİŞEHİR MAH ADEM BURAN CAD YENİANADOLU SİT NO:8', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1410, '33052348356', 'SUAT', 'AVCIK', '11.SINIF', '2008-10-06', 'SEDAT AVCIK', '05050372004', 'VİLDAN AVCIK', '05050382004', 'YENİŞAFAK MH. 1017 SK. NO:13 UZUNKENT SİT. A3 BLK. K:1 D:6', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1411, NULL, 'ECRİN', 'AKTAŞ', '11.SINIF', NULL, 'HAKAN AKTAŞ', '05327400524', 'ZEKİYE AKTAŞ', '05353277328', '', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1412, '32863348456', 'SULTAN', 'GAYIRHAN', '11.SINIF', '2009-07-13', 'FATİH GAYIRHAN', '05339257880', 'BEDİHA GAYIRHAN', '05384341791', 'YENİŞAFAK MAH 1034 SOK NO:9 A2 BLOK D:7', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1413, '37546192760', 'SÜMEYYE GÜL', 'YILDIRIM', '11.SINIF', '2009-12-18', 'ASIM YILDIRIM', '05492587258', 'GÜLNUR YILDIRIM', '05373586802', 'YENİŞAFAK MAH 1018 SOK ÇAĞIN 2 APT NO:18 K:4 D:9', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1414, '11993039656', 'MELİKE', 'IŞIK', '11.SINIF', '2009-01-12', 'MÜRSELİN IŞIK', '05383398618', 'MEDİNE IŞIK', '05378707510', 'TOPRAKLIK MAH HALK CAD NO:10 D:7', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1415, '35782253016', 'EFE GÜVEN', 'ERCAN', '11.SINIF', '2009-07-17', '', '', 'SEVAL ARAÇ', '05445282153', 'ŞEMİKLER MAH CİNKAYA BULV EGEPARK EVLERİ NO:105', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1416, '26114681256', 'GÖZDE', 'ERMİŞ', '11.SINIF', '2010-01-15', 'YÜKSEL ERMİŞ', '05322806864', 'SEVDA ERMİŞ', '05301222067', 'ŞEMİKLER MAH 3066 SOK NO:21 D:1', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1417, '53119457364', 'DEFNE SU', 'BOZTEPE', '11.SINIF', '2008-12-23', 'REMZİ BOZTEPE', '05352802311', 'NURŞEN BOZTEPE', '05332263098', 'ŞEMİKLER MAH 3047 SOK NO:3 D:6', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1418, '40138105574', 'ECRİN', 'UTANGAÇ', '11.SINIF', '2009-01-15', 'HÜSEYİN UTANGAÇ', '05331990980', 'MÜLKİYE UTANGAÇ', '05337631660', 'SİNPAŞ EVLERİ', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1419, '17374863896', 'AHMET', 'KARAMAN', '11.SINIF', '2009-06-15', 'HALİL KARAMAN', '05325764766', 'SİBEL KARAMAN', '05333546313', 'SİNPAŞ', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1420, '33058339734', 'BÜŞRA ZEYNEP', 'BİLDİRİCİ', '11.SINIF', NULL, 'AYKUT BİLDİRİCİ', '05326457804', 'AYŞE BİLDİRİCİ', '05559717979', 'SELÇUKBEY MH 557 SOK NO:4 D:8', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1421, NULL, 'HATİCE', 'AKKUŞ', '11.SINIF', NULL, 'MUSTAFA AKKUŞ', '05333538938', 'SONGÜL AKKUŞ', '05427367257', 'İLBADI MAH HÜDAİ ORAL CAD NO:79 K:2 - ANANE ADRES', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1422, '37039211220', 'RAMAZAN ENES', 'ÖZYILMAZ', '11.SINIF', '2009-07-25', 'SERHAN ÖZYILMAZ', '05055864230', 'SEVİM', '05055864231', 'PELİTLİBAĞ MH. 1108 SK. NO:1', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1423, '21100741006', 'TUĞÇE', 'SABAH', '11.SINIF', '2009-03-15', 'MUHAMMET SABAH', '05052514486', 'BETÜL SABAH', '05362408134', 'PELİTLİBAĞ MAH. HÜRRİYET CAD. NO:57 D:12', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1424, NULL, 'MERT EMİR', 'DURMAZ', '11.SINIF', NULL, 'TAHSİN DURMAZ', '05326484072', 'AYGÜL DURMAZ', '05317764883', 'KAYAKÖY TOKİ 6019 SOK GA 32 BLOK K:5 D:22', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1425, '29215469952', 'MEHMET ZEKİ', 'KIRBOĞA', '11.SINIF', '2009-09-12', 'İBRAHİM KIRBOĞA', '05356255935', 'SEVİM KIRBOĞA', '05318829356', 'KARŞIYAKA MAH 2364 SOK NO:3 K:2', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1426, '10562791810', 'AYŞE NUR', 'DOĞAN', '11.SINIF', '2007-05-02', 'YUNUS DOĞAN', '05354763470', 'ALİME DOĞAN', '05362974656', 'KARAHASANLI MAH. 2031 SOK NO:12 K:3 D:7', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1427, '19843781618', 'TAHSİN', 'KISAN', '11.SINIF', NULL, 'YUSUF KISAN', '05337662591', 'AYLİN KISAN', '05057513002', 'KARAHASANLI MAH. 2008 SOK. NO:16 D3 BLOK LOJMAN', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1428, NULL, 'MERYEM SUDE', 'TİMUR', '11.SINIF', NULL, 'ERCAN TİMUR', '05304039095', 'EMEL TİMUR', '05442920368', 'KARAHASANLI MAH 2018/2 SOK NO:11 B BLOK K:3', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1429, '43222002544', 'CEYLİN', 'GÖKSU', '11.SINIF', '2009-07-11', 'HASAN ALİ GÖKSU', '05437467680', 'MERYEM GÖKSU', '05464342517', 'HACIEYÜPLÜ MAH 3022 SOK NO:14', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1430, '20965746256', 'AYŞENİL', 'ÖZÜN', '11.SINIF', '2009-04-30', 'OZAN ÖZÜN', '05374765365', 'DERYA NUR ÖZÜN', '05374735365', 'GERZELE MAH 534 SOK NO:14 KANYON KONAKLARI F BLOK K:1 D:1', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1431, '14476960096', 'GÜLSÜM', 'KOÇER', '11.SINIF', '2009-08-06', 'RESUL KOÇER', '05323462864', 'ZEYNEP KOÇER', '05345159245', 'ESKİHİSAR MAH ATATÜRK CAD NO:17 K:1', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1432, '38914146118', 'NAZİK', 'GÖĞEBAKAN', '11.SINIF', '2009-10-28', 'ÜMİT GÖĞEBAKAN', '05334273028', 'ÜMİT GÖĞEBAKAN', '05350639044', 'ESKİHİSAR MAH ATATÜRK CAD 25 SOK NO:2', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1433, '41350065486', 'MERAL NAZ', 'ÇAYAN', '11.SINIF', '2009-09-11', 'MUHSİN ÇAYAN', '05556686262', 'AYŞEN ÇAYAN', '05556686252', 'ÇAKMAK MAH 172 SOK YAKUT KONUTLARI B BLOK K:4 D:11', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1434, '23824651562', 'ERVA NİL', 'AYDIN', '11.SINIF', '2009-07-03', 'NAZMİ AYDIN', '05325683312', 'KADRİYE AYDIN', '05378694738', 'BAŞKARCI MH. YENİYOL CD. NO:51', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1435, '19972784658', 'YAVUZ SELİM', 'DİLMAÇ', '11.SINIF', '2009-11-17', 'KEMAL DİLMAÇ', '05324292053', 'MEDİNE DİLMAÇ', '05426256258', 'ASMALIEVLER ESKİ ACIPAYAM YOLU NO:90 K:2', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1436, '26248569024', 'KAAN', 'DEMİR', '11.SINIF', '2009-10-22', 'MELİH DEMİR', '05426822244', 'FATMA DEMİR', '05448356261', 'ADALET MAH 10130 SOK NO:18 GÖRGÜLÜ APT', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1437, '36553232032', 'HACER NAZ', 'TOPUCAR', '10.SINIF', NULL, 'HALİL TOPUCAR', '05340237213', 'RUKİYE TOPUCAR', '05436904237', '', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1438, '15208938062', 'AHMET SERDAR', 'TOPUZAYAK', '10.SINIF', '2010-04-29', 'TAHSİN TOPUZAYAK', '05322954467', 'ÇİLER ÜNAL', '05423813814', 'İSTİKLAL CAD. LENA APT. NO:102 K:2', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1439, '25291601590', 'ECRİNSU', 'ALANCI', '10.SINIF', '2010-02-16', 'RAMAZAN ALANCI', '05358818017', 'MELEK', '05338179432', 'ADALET MH. ORHANGAZİ CD. NO:23 HANDE SİT. A BLK. D:9', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1440, '38734153914', 'ERDEM', 'KESEN', '10.SINIF', '2010-06-19', 'İLKER KESEN', '05325006793', 'DEMET KESEN', '05388706145', 'ZEYTİNKÖY MAH KARACAOĞLAN SOK NO:8 SÜMERLER APT D:2', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1441, '16147904620', 'BETÜL ZİŞAN', 'YILMAZ', '10.SINIF', '2008-12-19', 'HÜSREV YILMAZ', '05367865820', 'AYŞEGÜL YILMAZ', '05376951058', 'ZEYTİNKÖY MAH 5088 SOK NO:8', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1442, '31546378940', 'LEYLA NAZ', 'GÖLCÜ', '10.SINIF', '2010-05-13', 'İBRAHİM GÖLCÜ', '05544547134', 'GÜRCAN GÖLCÜ', '05528890959', 'YURT ADRESİ GELECEK', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1443, '15904915206', 'ELİF', 'URKAY', '10.SINIF', NULL, 'MEHMET URKAY', '05331517070', 'FATMA URKAY', '05417842432', 'YUNUS EMRE MAH 6418 SOK NO:5 K:1 DESTAN APT', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1444, '21526727158', 'MUSTAFA MELİH', 'ŞAHİN', '10.SINIF', '2010-03-30', 'HİMMET ŞAHİN', '05333043661', 'YASEMİN ŞAHİN', '05454287308', 'YENİŞAFAK MAH. 1142 SOK. NO:4 K:2 D:3', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1445, NULL, 'YUSUF CEVDET', 'TAŞTAN', '10.SINIF', NULL, 'MEHMET TAŞTAN', '05324831449', 'HÜLYA TAŞTAN', '05542433303', 'YENİŞAFAK MAH 1056 SOK NO:2/A A BLOK K:3 D:12 ANADOLU İNCİSİ', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1446, '27781518234', 'CEMRE', 'SELVİ', '10.SINIF', NULL, 'SAMİ SELVİ', '05326238267', 'YÜSRA SELVİ', '05308460286', 'ŞİRİNKÖY MAH 12222 SOK NO:15-ABALIOĞLU ARKASI', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1447, NULL, 'ZEYNEP EDA', 'ALABACAK', '10.SINIF', NULL, 'YILMAZ ALABACAK', '05449093482', 'SERMİN ALABACAK', '05058185209', 'ŞEMİKLER MAH.3006 SK. DEMİRCİ KONUTLARI B2 BLOK D:8', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1448, '32881349176', 'ALİ ESAD', 'ÖZTÜRK', '10.SINIF', '2010-05-14', 'İSMAİL ÖZTÜRK', '05324097325', 'ŞERİFE ÖZTÜRK', '05394225353', 'ŞEMİKLER MAH 3008 SOK GÜMÜŞKONUT SİT 367 ADA 4 BLOK K:5 D:21', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1449, NULL, 'AHSEN AZRA', 'KESERLİOĞLU', '10.SINIF', NULL, 'SERHAN KESERLİOĞLU', '05553790203', 'NİLGÜN KESERLİOĞLU', '05552880743', 'YENİŞEHİR MAH FERAH EVLER SİTESİ D ADASI 11 SOK NO:5', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1450, NULL, 'ELİF', 'ER', '10.SINIF', NULL, 'NECATİ ER', '05304081601', 'FATMA ER', '05056180495', 'YENİŞAFAK MAH 571 SOK NO:14', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1451, NULL, 'ZEYNEP', 'ZOROĞLU', '10.SINIF', NULL, 'MÜCAHİT ZOROĞLU', '05326075930', 'AKİFE ZOROĞLU', '05379790793', 'ŞİRİNKÖY MAH 12218 SOK NO:13', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1452, '15151937440', 'BERAT', 'KARTAL', '10.SINIF', NULL, 'ÇETİN KARTAL', '05330545570', 'SEVDA KARTAL', '05368938922', 'ŞEMİKLER MAH CİNKAYA BULV NO:92 ŞEHRİAL SİTESİ', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1453, '39359120282', 'ADEM BERK', 'IŞIK', '10.SINIF', NULL, 'ABDULHALİK IŞIK', '05366780020', 'MÜNİBE IŞIK', '05366532700', 'ŞEMİKLER MAH 3137 SOK NO:2/B SEYİR KONUTLARI B BLOK', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1454, '43498982298', 'MELİKE BERRA', 'IŞIK', '10.SINIF', '2010-05-09', 'HASAN IŞIK', '05336528482', 'BETÜL IŞIK', '05374115162', 'ŞEMİKLER MAH 3137 SOK NO:2/B SEYİR KONUTLARI A BLOK', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1455, '18046843352', 'NİSA', 'ÇANKAYA', '10.SINIF', '2010-04-21', 'BEHLÜL GÜVEN', '05321362626', 'HAVVA GÜVEN', '05356686655', 'ŞEMİKLER MAH 3125 SOK NO:42 K:3', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1456, '38416164306', 'VELİ EGE', 'TOKKAYA', '10.SINIF', '2010-04-14', 'NİHAT TOKKAYA', '05387033860', 'SEDEFNUR TOKKAYA', '05368710926', '', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1457, '15184528936', 'GÜLİSTAN MEDİNE', 'KAYA', '10.SINIF', '2009-04-22', 'YAŞAR KAYA', '05365259496', 'NURŞEN KAYA', '05462349734', 'KERVANSARAY MAH 3052 SOK NO:60 K:2', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1458, '33406330204', 'ABDULLAH FERİT', 'ÇETİN', '10.SINIF', '2011-01-09', 'HİKMET ÇETİN', '05077458555', 'CEYLAN ÇETİN', '05394458577', 'GÜMÜŞÇAY MAH 4171 SOK NO:31', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1459, '41878050902', 'PETEK NUR', 'UZAL', '10.SINIF', '2010-08-11', 'ALİ UZAL', '', 'NURSEL UZAL', '05424455443', 'BEYLERBEYİ MAH', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1460, '35440243410', 'ELİF', 'GEZGİN', '10.SINIF', '2010-01-22', 'MUSTAFA GEZGİN', '05325917995', 'BETÜL GEZGİN', '05058176092', 'BABADAĞ', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1461, '13456997374', 'ALİ', 'ÖZTÜRK', '10.SINIF', '2010-07-21', 'MEHMET ÖZTÜRK', '05384125697', 'KADRİYE ÖZTÜRK', '05532767745', 'AKTEPE MAH 2390 SOK 5C1-5 BLOK D:26', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1462, '32794351600', 'ZEYNEP', 'ÇAVDAR', '10.SINIF', '2010-04-06', 'ALİ ÇAVDAR', '05354040643', 'NADİRE ÇAVDAR', '05352424534', 'KARŞIYAKA MH. 2439/1 SK. NO:14', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1463, '42556024154', 'ENES', 'KARAKUŞ', '10.SINIF', '2010-07-20', 'ÜMİT KARAKUŞ', '05493893898', 'KÜBRA KARAKUŞ', '05423255664', 'KARŞIYAKA MAH. 2439/3 SOK. NO:17', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1464, '42598023474', 'DENİZ', 'MUCAN', '10.SINIF', '2009-11-05', 'MUSTAFA MUCAN', '05373211104', 'DİLEK MUCAN', '05435329938', 'KARAHASANLI MAH 2247/1 SOK NO:23 K:4 D:13', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1465, '19591790382', 'ORHAN MEHMET', 'TARIMER', '10.SINIF', '2009-11-01', 'FERDİ TARIMER', '05063008605', 'FATMA TARIMER', '05462042044', 'KARAHASANLI MAH 2101 SOK NO:4 ASİL APT', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1466, '36139241076', 'ASUDE YETER', 'KOÇER', '10.SINIF', '2010-06-29', 'OSMAN KOÇER', '05322724713', 'ANNE 3 YIL ÖNCE VEFAT ETTI', '', 'ESKİHSAR MH. ATATÜRK CD. NO:17/3', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1467, '29635455504', 'KEVSER NUR', 'ARIKAN', '10.SINIF', '2009-11-05', 'TUNCAY ARIKAN', '05324747651', 'HANIM ARIKAN', '05346822834', 'DELİKTAŞ MAH 1989 SOK NO:13', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1468, '33853317070', 'SÜLEYMAN TUNAHAN', 'ŞEKER', '10.SINIF', '2009-05-20', 'İLKER ŞEKER', '05556098820', 'SEVİLAY ŞEKER', '05541995259', 'DEĞİRMENÖNÜ MAH 1374 SOK NO:6 GÜNEŞ APT D:1 B BLOK', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1469, '14110975288', 'HİRA NUR', 'SELVİ', '10.SINIF', NULL, 'TAYYAR SELVİ', '05327313967', 'YILDIZ SELVİ', '05365050576', 'ÇAKMAK MAH 175 SOK NO:2 K:4', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1470, '37282200194', 'ZEYNEP BERRA', 'SEYLAN', '10.SINIF', '2010-08-18', 'SELMAN SEYLAN', '05336446769', 'SEDA SEYLAN', '05327735130', 'BEREKETLİ MH. 10171 SK. NO:2', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1471, NULL, 'ALİM ONUR', 'AKÇOVA', '10.SINIF', NULL, 'UĞUR AKÇOVA-AÇMIYORSA WP ARA', '05306606004', 'KSENİYA AKÇOVA', '', 'BEREKETLER MAH 10214 SOK NO:2', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1472, '21175739342', 'GALİP', 'KARCILI', '10.SINIF', '2010-08-04', 'SALİH KARCILI', '05413777040', 'HATİCE KARCILI', '05443361685', 'AKÇEŞME MAH. STAD CAD. NO:3 K:2', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1473, '12944014050', 'ELİF', 'GÖR', '10.SINIF', '2010-09-09', 'AZİZ GÖR', '05388416525', 'YASEMİN GÖR', '05071247924', 'AKÇEŞME MAH. ERTUĞRUL GAZİ CD. N:89 K:3', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1474, '39529127592', 'ENES', 'ÇINAR', '10.SINIF', '2010-05-15', 'BÜLENT ÇINAR', '05366774107', 'HACER ÇINAR', '05393990532', 'ADALET MH. 10152  SK. NO:22 KAT:1 DA:1 İLGÜN APT.', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1475, '24791015166', 'MUSTAFA KORALP', 'KÖSE', '10.SINIF', '2009-10-07', 'SERDAR KÖSE', '05522157794', 'DURU KÖSE', '05053907304', 'ADALET MAH 10134 SOK NO:7 D:19', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1476, '18496833866', 'ZELİHA NUR', 'TOPUCAR', '9.SINIF', NULL, 'HALİL YOPUCAR', '05340237213', 'RUKİYE TOPUCAR', '05436904237', '', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1477, NULL, 'CEMRE NUR', 'YOLLU', '9.SINIF', NULL, 'İSMAİL YOLLU', '05518385866', 'NURTEN YOLLU', '05058171323', '', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1478, '22534694678', 'EMİR', 'DEMİRCAN', '9.SINIF', NULL, 'UĞUR DEMİRCAN', '05337773785', 'GÖNÜL DEMİRCAN', '05343621180', '', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1479, '41443061272', 'GAYE', 'YAPAR', '9.SINIF', NULL, 'BEHÇET SERDAR YAPAR', '05364136523', 'ŞEYMA YAPAR', '05345410377', '', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1480, NULL, 'ELİF SU', 'UNCU', '9.SINIF', NULL, 'HASAN UNCU', '05337440063', 'HATİCE UNCU', '05374386756', 'MEHMET AKİF ERSOY MAH. 65/2 SOK. N:74-A D:4 MANİSA', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1481, NULL, 'HAYRUNNİSA', 'ŞENTÜRK', '9.SINIF', NULL, 'SEMİH ŞENTÜRK', '05053882663', 'HURİYE ŞENTÜRK', '05052660763', 'MEHMET AKİF ERSOY MAH. 65/2 SOK. N:74-A D:4 MANİSA', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1482, '15805916500', 'EYLÜL SİDA', 'TOKSÖZ', '9.SINIF', NULL, 'MEHMET ÖZDEMİR', '05078482171', 'SEVİLAY ÖZDEMİR', '05354950789', '', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1483, '19300801552', 'SEMİH', 'BOZOĞLU', '9.SINIF', NULL, 'MUHAMMED SAİD BOZOĞLU', '05335684876', 'SEMA BOZOĞLU', '05357687859', 'BAŞKARCI MAH.1072 SK. KAZANOĞLU SİT.J BLOK K:1 D:1', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1484, '43282003192', 'EYÜP', 'DÖLDÖŞ', '9.SINIF', NULL, 'EMRULLAH DÖLDÖŞ', '05464250319', 'NURAN DÖLDÖŞ', '05415334625', 'BAHÇELİEVLER MAH.3093 SK. NO:25/C', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1485, '55477644226', 'ŞEVVAL', 'CİTER', '9.SINIF', NULL, 'HUZEYFE CİTER', '05468083174', 'PINAR GÖK CİTER', '05334248150', '', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1486, '23149673454', 'ZEYNEP VERDA', 'KURU', '9.SINIF', '2011-04-07', 'RAMAZAN KURU', '05334216254', 'FATMA BETÜL KURU', '05079893277', 'MEHMETCİK MAH. 1296 SOK. NO:11 K:3', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1487, '42736019642', 'ZEYNEP NAZ', 'KOYUNCU', '9.SINIF', NULL, 'İSMAİL KOYUNCU', '05363443015', 'DİLEK KOYUNCU', '05384341791', 'GONCALI MAH. 2.SOK NO:172', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1488, '20108883022', 'ZEYNEP BEGÜM', 'ÇELİKER', '9.SINIF', '2011-04-26', 'BEKİR ÇELİKER', '05055499183', 'TÜLİN ÇELİKER', '05055014813', 'ALPARSLAN TÜRKEŞ BULV. YENİŞAFAK MH. NO:59 FİRUZE SİT.D:10', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1489, '22270702248', 'ZEKİYE EDA', 'KAYAN', '9.SINIF', '2010-03-12', 'SERDAR KAYAN', '05377778474', 'GAMZE KAYAN', '05519729626', 'GÜMÜŞÇAY MAH. 4169 SK. NO:6', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1490, '11945041386', 'YÜSRA', 'İBİŞ', '9.SINIF', NULL, 'DURSUN İBİŞ', '05301423104', 'SELMA İBİŞ', '05065368763', 'ZEYTİNKÖY MH. 4029 SK. NO:7 D:2', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1491, '26716552060', 'YUSUF KEREM', 'EKİM', '9.SINIF', '2011-06-30', 'MUSTAFA EKİM', '05326380894', 'GÜLSEREN EKİM', '05374266199', 'SEVİNDİK MAH. 2305 SOK. NO:30', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1492, '29284466876', 'TİMURHAN', 'KABUL', '9.SINIF', NULL, 'MEHMET KABUL', '05322875818', 'NURSEL KABUL', '05545068394', 'HALLAÇLAR MAH 5021 SOK NO:357', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1493, '21661728126', 'SÜLEYMAN', 'KAYTA', '9.SINIF', '2011-04-27', 'ADEM KAYTA', '05466626176', 'HATİCE KAYTA', '', 'DEĞİRMENÖNÜ MAH LOZAN CAD YOLDAŞOĞLU APT', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1494, '39334133504', 'SAMET', 'IŞIKTAŞ', '9.SINIF', '2011-03-04', 'AHMET IŞIKTAŞ', '05333154458', 'AYNUR IŞIKTAŞ', '05415415262', 'ŞEMİKLER MHA 4868 SOK NO:20 ELİT YAŞAM SİTESİ A BLOK K:9 D33', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1495, '17443862140', 'RAMAZAN AKAY', 'BAĞ', '9.SINIF', '2011-01-01', 'ORHAN BAĞ', '05305470011', 'ÖZLEM BAĞ', '05426468154', 'TOPRAKLIK MH. TURAN GÜNEŞ CD. NO:29 K:5 D:19', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1496, '23239670912', 'OSMAN METE', 'AKAR', '9.SINIF', NULL, 'ABDURRAHMAN AKAR', '05058116243', 'EMİNE AKAR', '05544755849', 'PINARKENT MAH. 130 SOK. NO:3/2', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1497, '40519092202', 'MELEK NUR', 'ATA', '9.SINIF', NULL, 'HAMİDULLAH ATA', '05075812374', 'ARZU ATA', '05075812374', 'KARAHASANLI MAH.KASIMBEY SİTESİ D BLOK', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1498, '41887047672', 'LALEZAR', 'ATASOY', '9.SINIF', '2011-01-14', 'RAMAZAN ATASOY', '05332123540', 'BETÜL ATASOY', '05327619656', 'KARAHASANLI MAH 2184 SOK NO:1/A', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1499, '38305173280', 'KAMİL OĞUZ', 'KARAKAŞ', '9.SINIF', '2010-11-27', 'İBRAHİM KARAKAŞ', '05325152016', 'RAZİYE KARAKAŞ', '05326847535', '1200 EVLER MAH 2017 SOK NO:16 D:2', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1500, '35629075030', 'HATİCE', 'ÖZLÜ', '9.SINIF', '2011-01-01', 'COŞKUN ÖZLÜ', '05462321716', 'FİDAN ÖZLÜ', '05526770710', 'ÇAMLARALTI MAH 6006 SOK NO:22 D:2 VEFA APARTMANI', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1501, '40015109954', 'HASAN EYMEN', 'ÖNAVCI', '9.SINIF', '2011-01-05', 'AHMET ÖNAVCI', '05442051986', 'KIYMET ÖNAVCI', '05465727360', 'ANAFARTALAR MAH 1121/1 SOK NO:12 D:5', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1502, NULL, 'ETEM KERİM', 'KAPUKIRAN', '9.SINIF', NULL, 'İBRAHİM KAPUKIRAN', '05367111929', 'GÜLCAN KAPUKIRAN', '05394033344', 'YENİŞEHİR MAH 100. SOK NO:9/A IHLAMUR KONAKLARI', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1503, '36634791762', 'ENES', 'TEKELİOĞLU', '9.SINIF', '2011-05-17', 'TURGAY TEKELİOĞLU', '05065354315', 'FATMANA TEKELİOĞLU', '05056056318', 'SELÇUKBEY MAH 1050 SOK NO:16 B BLOK D:3', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1504, '18142839938', 'EMRULLAH MERT', 'BÜYÜKDAĞ', '9.SINIF', '2011-03-03', 'ABDULLAH BÜYÜKDAĞ', '05309538571', 'GÖKÇE ÇİÇEK BÜYÜKDAĞ', '05525842239', 'ÇAMLAR ALTI MAH BASSAVCI MUSTAFA ALPER CAD NİLAY APT NO:5 D2', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1505, '13172003502', 'EMİNE BELİNAY', 'SATILMIŞ', '9.SINIF', '2011-07-06', 'ÖZGÜR SATILMIŞ', '05337604221', 'SULTAN SATILMIŞ', '05333033628', 'KARAHASANLI MAH 2008 SOK B9 BLOK KARDELEN APT K:3', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1506, '18949811854', 'ELİF EYLEM', 'KANDEMİR', '9.SINIF', '2011-08-18', 'ERDİN KANDEMİR', '05369605099', 'ROJGÜL KANDEMİR', '05317410651', 'YENİŞAFAK MAH 1194 SOK NO:12 D:2', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1507, '35551257788', 'EFE', 'BECEREN', '9.SINIF', '2010-08-12', 'BARIŞ BECEREN', '05056234156', 'MİNE BECEREN', '05054933439', 'ÇAKMAK MH. 127 SK. MUSA ÇAVUŞ APT. NO:2 D:31', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1508, '29566458476', 'ECE', 'ORHAN', '9.SINIF', '2011-01-21', 'MUTLU ORHAN', '05468496940', 'SEÇİL ORHAN', '05359827178', 'ŞEMİKLER MAH. OSMAN GAZİ CAD. DEMİRCİLER SİT A BLOK K:2', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1509, '20641757798', 'BUSE', 'KOÇ', '9.SINIF', '2011-06-06', 'TACETTİN KOÇ', '05382690043', 'HAKİME KOÇ', '05398273234', 'YENİŞAFAK MAH 1133 SOK NO:6 D:5', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1510, '13040008578', 'BELİNAY ZEHRA', 'ÇUNKAR', '9.SINIF', NULL, 'CUMHUR ÇUNKAR', '05445770777', 'SEMRA ÇUNKAR', '05464576271', 'ŞEMİKLER MAH 3101 SOK NO:21/1', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1511, '26158555872', 'AHMED ZAHİD', 'AKYÜZ', '9.SINIF', '2010-11-05', 'ALİ AKYÜZ', '05058745115', 'NERİMAN AKYÜZ', '05445186571', 'ŞEMİKLER MAH 3121 SOK NO:15 K:5 D:10', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1512, '30505427118', 'ADEM BURAK', 'BAYRAKDAROĞLU', '9.SINIF', '2011-01-20', 'AZİZ BAYRAKDAROĞLU', '05072023835', 'ÜMRAN BAYRAKDAROĞLU', '05416706705', 'SÜLEYMAN ŞAH CAD. NO:15 K:3 D:6 BAĞBAŞI', 'POLAT ÜLKER', '05445501418', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1513, '26440562908', 'SAYHAN METE', 'YILDIZ', '8.SINIF', NULL, 'EMRE YILDIZ', '05320532681', 'ŞAHİKA YILDIZ', '05321538097', '', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1514, '32665355140', 'ATİLA', 'AKÇAGÜL', '8.SINIF', NULL, 'AYKUT AKÇAGÜL', '05326036945', 'RAFİYE AKÇAGÜL', '05436036945', '', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1515, '37849182192', 'HALİL', 'ÇELİK', '8.SINIF', NULL, 'FARUK ÇELİK', '05558000577', 'KÜBRA ÇELİK', '05321396403', 'KAYIHAN MAH. MİMAR SİNAN CAD. 3001/1 SOK. NO:3', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1516, '40387096388', 'ORHAN BEDİ', 'AKYOL', '8.SINIF', '2012-01-18', '', '', 'ARZU BACANLI', '05312752673', 'YENİŞAFAK MH. 1037 SK. NO:6 E BLK.K:4 D:18', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1517, '31264402002', 'ESLEM ZEHRA', 'SARI', '8.SINIF', '2012-05-05', 'ÖMER', '05359444962', 'SÜMEYYA SARI', '05057055753', 'KERVANSARAY MH. ZARRAFLAR SİT. 3101 SK. D BLK. K:2 BAĞBAŞI', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1518, '20486888060', 'SEYİT EMİN', 'ATAMAN', '8.SINIF', NULL, '', '', 'TÜRKAN ATAMAN', '05382111657', 'ADALET MAH. 10014 SOK. NO:5', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1519, '17338866406', 'NİSANUR', 'KAHRAMAN', '8.SINIF', '2012-05-20', 'AHMET KAHRAMAN', '05354343660', 'AYŞE KAHRAMAN', '05392582684', 'ZÜMRÜT MH. VATAN BULV. NO:209 K:3  BAĞBAŞI', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1520, '15085940226', 'SAİD ABİDİN', 'YILDIRIM', '8.SINIF', '2012-07-12', 'OKAN YILDIRIM', '05548810888', 'KERİMAN YILDIRIM', '05323782676', 'ZÜMRÜT MH. HUZUR CD. SOYLU SİT. A BLK. K:5 D:12', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1521, '25366600312', 'VELİ EFE', 'CEYLAN', '8.SINIF', '2012-12-31', 'ALİ CEYLAN', '05398544974', 'SELVER CEYLAN', '05386595675', 'ZÜMRÜT MAH 2004 SOK NO:17 D:3', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1522, '42757018082', 'EMİR', 'ERSOY', '8.SINIF', NULL, 'YILDIRAY ERSOY', '05323216378', 'SEMRA', '05309231157', 'YENİŞEHİR MH. 80 SK. NO:22', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1523, '23740653572', 'NURSİMA', 'TAŞ', '8.SINIF', '2011-11-11', 'NURİ TAŞ', '05326538897', 'BEÜL TAŞ', '05558267444', 'YENİŞEHİR MAH.48 SOK. NO:19', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1524, NULL, 'YAVUZ SELİM', 'ÇELEN', '8.SINIF', '2011-04-26', 'ABDULLAH ÇELEN', '05326181349', 'NECLA ÇELEN', '05536071507', 'YENİŞAFAK MAH. 1153 SOK. NO:27 D:9 GÜNEŞ APT', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1525, '19079468598', 'HÜSEYİN', 'KARAKULA', '8.SINIF', '2012-08-03', 'BAHADIR KARAKULA', '05057681688', 'HAMİYET KARAKULA', '05548034228', 'YENİŞAFAK MAH. 1102 SOK. NO:9 ANADOLU APT. K:2 D:3', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1526, '24052640878', 'SALİH', 'ORDU', '8.SINIF', '2011-07-04', 'OSMAN ORDU', '05432253835', 'AYŞE ORDU', '05432269760', 'YENİŞAFAK MAH 1034 SOK NO:14 ITIR VİLLALARI', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1527, '18763818596', 'ASLI', 'KARABABA', '8.SINIF', '2012-08-31', 'SAİT KARABABA', '05558410025', 'NEJLA KARABA', '05558410024', 'VATAN CD. NO:126 D:5 BAĞBAŞI', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09');
INSERT INTO `vp_students` (`id`, `tc_no`, `first_name`, `last_name`, `class`, `birth_date`, `father_name`, `father_phone`, `mother_name`, `mother_phone`, `address`, `teacher_name`, `teacher_phone`, `notes`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(1528, '18574824380', 'MUHSİN ÖMER', 'GENCER', '8.SINIF', '2011-08-21', 'YASİN GENCER', '05355586527', 'GÜLDEN GENCER', '05355515722', 'ŞEMİKLER MH. 3007 SK. NO:9 ÖZER-2 APT. K:3 D:15', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1529, '37027211362', 'RAFET YEKTA', 'ERTUNA', '8.SINIF', '2011-12-25', 'MUSTAFA ERTUNA', '05337308170', 'NALAN ERTUNA', '05432082103', 'ŞEMİKLER MAH. 3006 SK. NO:2 GOKKUSAĞI KONUTLARI  A BLOK D:16', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1530, NULL, 'MUHAMMED BAHADIR', 'GÜLEÇ', '8.SINIF', NULL, 'HİKMET GÜLEÇ', '05071915854', 'BÜŞRA GÜLEÇ', '05418598629', 'ŞEMİKLER MAH 3102 SOK NO:74 K:2 D:8', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1531, '38044176856', 'EMİR', 'AKTAŞ', '8.SINIF', '2012-11-02', 'MÜMTAZ AKTAŞ', '05534748148', 'FATMA AKTAŞ', '05077956235', 'ŞEMİKLER MAH 3008 SOK GÜMÜŞKONUTLARI KARDELEN APT D:9', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1532, '28657488682', 'ERTUĞRUL', 'ÖZDEMİR', '8.SINIF', '2012-04-13', 'HAKAN ÖZDEMİR', '05076969653', 'HANİFE ÖZDEMİR', '05367016512', 'YUNUS EMRE MAH BARBAROS CAD NO:50 ABACI APT K:2 D:2', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1533, '32170370508', 'ZEYNEP', 'AYYILDIZ', '8.SINIF', '2012-06-28', 'MUSTAFA AYYILDIZ', '05058054626', 'AYSUN AYYILDIZ', '05058054636', 'ŞİRİNKÖY MAH 12213 SOK NO:11 K:2', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1534, '14191899114', 'ELİF ÖYKÜ', 'ASILTÜRK', '8.SINIF', '2014-03-19', 'OSMAN ASILTÜRK', '05074721817', 'ÖMÜR MÜNEVVER ASILTÜRK', '05065813521', 'ŞEMİKLER MAH 3116 SOK NO:4 EKSTRA LİFE2 SİTESİ D:21', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1535, '33445330678', 'HÜSEYİN EYMEN', 'KAYHAN', '8.SINIF', '2011-12-13', 'ALİ KAYHAN', '05374272400', 'KEVSER KAYHAN', '05354372414', 'KARAHASANLI MH. 2027 SK. NO:4 A BLK. D:13', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1536, '26677554394', 'MUHAMMED BUĞRA', 'AKBULUT', '8.SINIF', '2012-08-05', 'İBRAHİM AKBULUT', '05532954052', 'CENNET AKBULUT', '05549909552', 'KARAHASANLI MAH AKÇA CAD', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1537, '38545158672', 'ESLEM', 'ER', '8.SINIF', '2012-01-10', 'FİKRET ER', '05558573501', 'VİLDAN', '05558573502', 'KARAHASANLI MAH 2031 SOK NO:4 K:2 D:6', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1538, NULL, 'GÖKTUĞ', 'ORTAKARACA', '8.SINIF', NULL, 'GÖKHAN ORTAKARACA', '05558410473', 'TUĞBA KARACA', '05544884158', 'KARAHASANLI MAH 2025 SOK NO:23 BERRAK EVLER SİTESİ B2 BLOK', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1539, '13693988456', 'ALEYNA NUR', 'DEDE', '8.SINIF', '2012-10-30', 'AHMET DEDE', '05538811368', 'EBRU DEDE', '05457305567', 'GÜMÜŞÇAY MAH 4227 SOK NO:12 D:2', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1540, '22288702292', 'NİSA', 'MİRAN', '8.SINIF', '2011-11-30', 'OSMAN MİRAN', '05312155565', 'GÜL MİRAN', '05312155566', 'FESLEĞEN MAH 992 SOK NO:26 K:1 D:2', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1541, '17992843980', 'YAĞMUR', 'AYBAY', '8.SINIF', '2012-04-02', 'ALİ AYBAY', '05458026565', 'HİLAL AYBAY', '05556143312', 'ÇAKMAK MAH 133/2 SOK AY APT NO:7 D:10', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1542, '37090206760', 'BERRA SU', 'ERCAN', '8.SINIF', '2012-07-04', 'HASAN ERCAN', '05413207930', 'AYŞE ERCAN', '05384511057', 'AKHAN MAH 190 SOK NO:8 K:2 D:4', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1543, '75892024434', 'GÜLBAHAR ZÜMRA', 'DURMUŞ', '8.SINIF', '2012-08-28', 'ALPER DURMUŞ', '05325704290', 'ÖZLEM DURMUŞ', '05334379332', 'PELİTLİBAĞ MAH 1132 SOK KAYNAK APT NO:7 K:4 D:17', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1544, '21931711612', 'AHMET', 'VURAL', '8.SINIF', '2012-01-30', 'ERCAN VURAL', '05323118026', 'NURİYE VURAL', '05438431398', 'MERKEZEFENDİ MH. 71 SK. NO:23', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1545, '33505327402', 'MUHAMMED MUSTAFA', 'DEMİR', '8.SINIF', '2011-10-01', 'HAKAN DEMİR', '05333606499', 'FATMA DEMİR', '05558796499', 'MERKEZEFENDİ MH. 450 SK.NO:10 YAVRU APARTMAN KAT:2', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1546, '19228804420', 'MİRAÇ ALİ', 'YOLCU', '8.SINIF', '2011-11-28', 'NASRULLAH YOLCU', '05337400388', 'MÜNASİB YOLCU', '05378266277', 'MERKEZEFENDİ MAH. 1762 SOK. NO:10 K:3', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1547, '13552992556', 'BARAN', 'KARAKUŞ', '8.SINIF', '2012-09-23', 'EVREN KARAKUŞ', '05495466848', 'HACER KARAKUŞ', '05427668660', 'KARŞIYAKA MH. 2439/3 SK. NO:15', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1548, '33949312262', 'AZRA', 'KARAKUŞ', '8.SINIF', '2012-04-11', 'İDRİS KARAKUŞ', '05464004036', 'NURCAN KARAKUŞ', '05432683397', 'KARŞIYAKA MH. 2439 SK. NO:22/2', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1549, '35836249910', 'DAMLA', 'MUCAN', '8.SINIF', '2012-05-16', 'MUSTAFA MUCAN', '05373211104', 'DİLEK MUCAN', '05435329938', 'KARAHASANLI MAH 2247/1 SOK NO:23 K:4 D:13', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1550, '28252502950', 'NAİL MELİH', 'ÖZTABAK', '8.SINIF', '2012-10-11', 'SALİH ÖZTABAK', '05057572397', 'AYŞEGÜL ÖZTABAK', '05438918450', 'KARAHASANLI MAH 2006 SOK MİLSU SİTESİ F BLOK D:10', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1551, '26596556486', 'ELİF RANA', 'ÖZTÜRK', '8.SINIF', '2012-02-16', 'HAMZA ÖZTÜRK', '05354372847', 'AYŞE ÖZTÜRK', '05052782859', 'İNCİRLİPINAR MAH.TOKAT CAD.ÖZKAN APT.NO:19/9', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1552, '45829533896', 'BEREN MİNA', 'ÜNAL', '8.SINIF', NULL, 'GÖKHAN ÜNAL', '05492419149', 'RUMEYSA ÜNAL', '05455500155', 'GÜLTEPE MAH. 4828 SOK. NO:3 / 20 ADAPARK KONUTLARI', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1553, '14461961664', 'RUKİYE', 'TARAKÇI', '8.SINIF', '2012-08-17', 'MURAT TARAKÇI', '05539602414', 'MERYEM', '05369737942', 'GERZELE MH. Y. SULTAN SELİM SK. NO:45 ELİF SİT. B BLOK D:10', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1554, '29575458252', 'ABDULLAH KEREM', 'NAZLIGÜL', '8.SINIF', '2012-02-28', 'MUHAMMET NAZLIGÜL', '05332721565', 'MUHAMMET NAZLIGÜL', '05334377403', 'GERZELE MH. 529 SK. AKDAĞ APT. NO:16/2', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1555, '24178636984', 'KEREM YİĞİT', 'ÖZKAL', '8.SINIF', '2012-06-19', 'GÖKHAN ÖZKAL', '05056036019', 'AYŞE ÖZKAL', '05304736049', 'GERZELE MAH 510 SOK NO:4 KÜLTÜR KENT SİTESİ A BLOK', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1556, '34771286592', 'KERİM', 'KOÇER', '8.SINIF', '2012-12-12', 'OSMAN KOÇER', '05322724713', 'ESMA KOÇER', '', 'ESKİHSAR MH. ATATÜRK CD. NO:17/3', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1557, '18487826846', 'MUSTAFA', 'DERE', '8.SINIF', '2012-07-11', 'SÜLEYMAN DERE', '05325653726', 'MEHLİKA DERE', '05412354842', 'DELİKTAŞ MAH.1946 SK. NO:29', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1558, '35020277286', 'RAMAZAN', 'ÖZLÜ', '8.SINIF', '2011-10-24', 'COŞKUN ÖZLÜ', '05462321716', 'FİDAN ÖZLÜ', '05526770710', 'ÇAMLARALTI MAH 6006 SOK NO:22 D:2 VEFA APARTMANI', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1559, '25918580390', 'EFLİN', 'AYER', '8.SINIF', NULL, 'CAHİT AYER', '05327493005', 'AYŞE AYER', '05426120303', 'ÇAKMAK MH. 145 SK. NO:1 A BLK. K:2 D:7', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1560, '25171605748', 'FATIMA YÜSRA', 'BAYSAL', '8.SINIF', '2012-06-08', 'VEDAT BAYSAL', '05325678374', 'ÇİĞDEM', '05496266265', 'CUMHURİYET MH. 3394 SK. NO:31', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1561, '14329968002', 'ELİF NİMET', 'SEYLAN', '8.SINIF', '2012-01-01', 'SELMAN SEYLAN', '05336446769', 'SEDA SEYLAN', '05327735130', 'BEREKETLİ MH. 10171 SK. NO:2', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1562, '27238537962', 'ZEYNEP', 'SEYLAN', '8.SINIF', '2012-12-26', 'MUSTAFA SEYLAN', '05323330016', 'NAZİRE SEYLAN', '05364995525', 'BEREKETLİ MAH.GAZİLER CAD.NO:1K:3', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1563, '32557359010', 'YAŞAR DOĞU', 'ŞİBİR', '8.SINIF', '2012-09-17', 'MEHMET ŞİBİR', '05062460008', 'BURCU ŞİBİR', '05549100220', 'BAĞBAŞI MAH 1125 SOK NO:5 K:4 D:10', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1564, '38233170580', 'AYŞE SENA', 'TURGUT', '8.SINIF', '2012-10-24', 'ENVER TURGUT', '05414526887', 'GÜLSÜM TURGUT', '05342009633', 'ASMALIEVLER MH. 6607 SK. NO:11', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1565, '15763047216', 'MELİKE', 'SEREZ', '8.SINIF', '2012-10-03', 'HÜSEYİN SEREZ(DEDE)', '05056719260', 'HURİYE SEREZ(BABANNE)', '05056290673', 'ANAFARTALAR MAH 2132 SOK NO:63', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1566, '30070441212', 'YUSUF SAFFET', 'ŞANOL', '8.SINIF', '2012-08-04', 'BERKAN ŞANOL', '05412590390', 'SİNEM ŞANOL', '05078872636', 'AKTEPE MH. 2421/7 SK. NO:3 C1-2 D:29', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1567, '26377564808', 'ECRİN', 'ORHAN', '8.SINIF', '2012-09-06', 'İSMAİL ORHAN', '05373660042', 'MELEK ORHAN', '05453120175', 'AKTEPE MAH 2420 SOK/2 NO:4', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1568, '32485361024', 'AYŞENAZ', 'YILMAZ', '8.SINIF', '2012-08-13', 'İSMAİL YILMAZ', '05327178666', 'HACER YILMAZ', '05303424352', 'AKÇA CAD. NO:43 KARAHASANLI', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1569, '34342300986', 'ELİF BEYZA', 'EKİNCİ', '8.SINIF', '2012-01-02', 'YASİN EKİNCİ', '05058953558', 'MERYEM EKİNCİ', '05066213545', 'ADALET MH. K.KARABEKİR CD. NO:11 K:2 D:5', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1570, '35863248122', 'SEFER DENİZ', 'SÖKEL', '7.SINIF', NULL, '', '', 'DİLEK ÖZSARITAŞ', '05494604601', 'GÜMÜŞÇAY MAH.AHMET YESEVİ CAD.NO:2/B B BLOK K:2 D.10', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1571, '34528291688', 'BUĞRA', 'KOPARAN', '7.SINIF', NULL, 'CEYHUN KOPARAN', '05330559610', 'AYSEL KOPARAN', '05544393462', '', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1572, '16159905950', 'YAKUP BERK', 'AHRAZ', '7.SINIF', '2012-05-30', 'İBRAHİM AHRAZ', '05363522056', 'HATİCE AHRAZ', '05366074570', '0536 607 45 70', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1573, '30313433752', 'HATİCE NAZ', 'BOYACI', '7.SINIF', NULL, 'OSMAN BOYACI', '05375826829', 'PELİN BOYACI', '05380836995', '0538 083 69 95', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1574, '35249380330', 'FATİH EREN', 'KARAEL', '7.SINIF', NULL, 'HALİL KARAEL', '05325037860', 'PAKİZE KARAEL', '05342032382', '0534 203 23 82', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1575, '24463629646', 'ECE NAZ', 'KARADAĞ', '7.SINIF', '2012-11-10', 'İDRİS KARADAĞ', '05354523343', 'ELİF', '05415592938', '0541 559 29 38', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1576, '45269061812', 'AYLİN', 'DANIK', '7.SINIF', '2011-10-11', '', '', 'AYŞE DANIK', '05398551374', '0539 855 13 74', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1577, '36682421710', 'ÖMER FARUK', 'BAŞARAN', '7.SINIF', '2013-02-12', '', '', 'ELİF DURMAZ', '05530090074', 'YENİŞEHİR MAH. 26. SOKAK ÖZEL EVLER SİTESİ A5 BLOK D:12 MERK', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1578, '31936381116', 'EYÜP', 'SİNKİL', '7.SINIF', '2013-07-15', 'MUSTAFA ALİ SİNKİL', '05324076507', 'SULTAN SİNKİL', '05333761220', '', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1579, '40669088360', 'AYŞEGÜL', 'ÖZEN', '7.SINIF', '2013-03-28', '', '', 'NAZİRE ÖZEN', '05054432379', 'SELÇUKBEY MH. 686 SK. NO:6 K:1 HUZUR APT.', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1580, '41020077214', 'YUSUF SAİD', 'BAZ', '7.SINIF', '2012-10-02', 'NEVZAT BAZ', '05337815199', 'KÜBRA BAZ', '05337815188', 'KARAMAN MH. FATİH CD. SÖYLEMEZ APT. NO:14', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1581, '22531694282', 'İSMAİL', 'CANLI', '7.SINIF', '2013-06-10', 'MEHMET CANLI', '05334708275', 'SİNEM CANLI', '05326201636', 'GERZELE MAH 1.CADDE NO:18/A TOWER LİFE', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1582, '15538926920', 'HÜSEYİN', 'ULU', '7.SINIF', '2013-05-31', 'UKBE ULU', '', 'GÜLPERİ YILMAZ', '05355198741', 'AKHAN MH. 179 SK. NO:6', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1583, '39766117998', 'İPEK', 'KAYA', '7.SINIF', NULL, 'TAMER KAYA', '05355939901', 'AYŞE', '05358572357', 'ZÜMRÜT MAH. 2079 SOK. NO:1 DAİRE:11', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1584, '31250513606', 'EGE', 'ERİK', '7.SINIF', '2013-06-07', 'SERDAR ERİK', '05301121301', 'GAMZE ERİK', '05452917266', 'ZÜMRÜT MAH 2082 SOK NO:12 K:1 D:4', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1585, '36658220866', 'ERHAN', 'PEKKAYA', '7.SINIF', '2013-08-06', 'MUSTAFA PEKKAYA', '05551651292', 'SEVİL PEKKAYA', '05425422554', 'ZEYTİNKÖY MAH 5022 SOK NO:16 D:3', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1586, '39997112786', 'REYHAN SERRA', 'ÖZMEN', '7.SINIF', '2013-07-23', 'OSMAN BAHADIR ÖZMEN', '05337749099', 'AYŞE SERENAY ÖZMEN', '05376106909', 'YUNUS EMRE MAH YUNUS EMRE CAD 6429 SOK NO:22 K:1 D:2', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1587, '22930686758', 'EYMEN EBRAR', 'YİĞİT', '7.SINIF', '2013-06-09', 'ÖZCAN YİĞİT', '05301550593', 'GÜLİZAR', '05301550592', 'YENİŞEHİR MAH. 19 SOK. NO:27', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1588, '26518567156', 'TAYLAN', 'AVCIK', '7.SINIF', '2013-05-14', 'SEDAT AVCIK', '05050372004', 'VİLDAN AVCIK', '05050382004', 'YENİŞAFAK MH. 1017 SK. NO:13 UZUNKENT SİT. A3 BLK. K:1 D:6', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1589, '53080157256', 'KUZEY', 'ACAR', '7.SINIF', '2012-12-11', 'AZİZ ACAR', '05425932335', 'SELEN ACAR', '05436114716', 'YENİŞAFAK MAH. 1017 SOK. NO:13 A5 BLOK D:8', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1590, '21151738840', 'ÇAĞIL', 'DEDEOĞLU', '7.SINIF', '2013-10-11', 'SERKAN DEDEOĞLU', '05066632170', 'BAHAR DEDEOĞLU', '05062198922', 'ŞEMİKLER MAH 3131 SOK NO:2 DEDELAVİLA', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1591, '50278797690', 'HAYRUNNİSA', 'ŞİRİN', '7.SINIF', '2013-03-08', 'FERHAT ŞİRİN', '05322114051', 'TUĞBA ŞİRİN', '05417346463', 'SİNPAŞ KONUTLARI', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1592, '17413863924', 'MUSTAFA UMUT', 'BOZTAŞ', '7.SINIF', '2013-04-30', 'BAYRAM BOZTAŞ', '05075283880', 'SIDDIKA BOZTAŞ', '05057479822', 'SİNPAŞ EVLERİ B1 B BLOK D:5', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1593, '15128339956', 'MUHLİSE SENA', 'KILINÇ', '7.SINIF', '2013-05-08', 'EYÜP İSMAİL KILINÇ', '05415592273', 'MUMTEHİNE KILINÇ', '05325568392', '', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1594, '19315801424', 'BELİNAY', 'KARTAL', '7.SINIF', '2013-06-22', 'ÇETİN KARTAL', '05330545570', 'SEVDA KARTAL', '05368938922', 'ŞEMİKLER MAH CİNKAYA BULV NO:92 ŞEHRİAL SİTESİ', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1595, '43978966428', 'HAYRUNNİSA', 'IŞIK', '7.SINIF', '2013-09-24', 'ABDULHALİK IŞIK', '05366780020', 'MÜNİBE IŞIK', '05366532700', 'ŞEMİKLER MAH 3137 SOK NO:2/B SEYİR KONUTLARI B BLOK', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1596, '41164071598', 'MEHMET TARIK', 'KİRAZ', '7.SINIF', '2013-03-09', 'ÖMER FARUK KİRAZ', '05055035676', 'AYŞE KİRAZ', '05078016242', 'SÜMER MAH 2482 SOK SÜMERPARK EVLERİ A6 BLOK D:40', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1597, '36578348756', 'NİHAN MİRA', 'TOKKAYA', '7.SINIF', '2014-01-16', 'NİHAT TOKKAYA', '05387033860', 'SEDEFNUR TOKKAYA', '05368710926', '', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1598, '32098373308', 'SERRA NURGÜL', 'SOLAK', '7.SINIF', '2013-02-13', 'HASAN ALİ SOLAK', '05322881645', 'SERVER SOLAK', '05325208790', 'SELÇUKBEY MAH 767 SOK NO:1A/12', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1599, '27523528026', 'MÜŞERREF EDA', 'BAŞKAYA', '7.SINIF', '2013-07-03', 'CAFER BAŞKAYA', '05335537396', 'LATİFE BAŞKAYA', '05515553272', 'HALLAÇLAR MAH 3050 SOK NO:16 D:4', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1600, '38191171988', 'NAİL EFE', 'YAMAN', '7.SINIF', '2013-06-03', 'YASİN YAMAN', '05367067878', 'EMİNE YAMAN', '05545427567', 'HALLAÇLAR MAH 3012 SOK NO:15', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1601, '39526035272', 'ALİ', 'BÜYÜKDAĞ', '7.SINIF', '2013-06-06', 'ABDULLAH BÜYÜKDAĞ', '05309538571', 'GÖKÇE ÇİÇEK BÜYÜKDAĞ', '05525842239', 'ÇAMLAR ALTI MAH BASSAVCI MUSTAFA ALPER CAD NİLAY APT NO:5 D2', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1602, '38626156962', 'AHMET POLAT', 'ÇIRACI', '7.SINIF', '2013-05-04', 'SERKAN ÇIRACI', '05358188093', 'AYSUN ÇIRACI', '05305234022', 'SERLÇUKBEY MAH ŞEHİT PİYD KOMNDO ER MEHMET AVCI CAD NO:33', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1603, '13001010466', 'ALİ', 'MERCAN', '7.SINIF', '2013-10-07', 'MEHMET MERCAN', '05359734206', 'HATİCE MERCAN', '05546891552', 'SELÇUKBEY MH. 745 SK. UZUNON APART. 13 A1 BLK. K:2 D:9', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1604, '21661452434', 'İBRAHİM EMİN', 'BAŞAR', '7.SINIF', '2013-01-02', 'MEHMET BAŞAR', '05467409946', 'HAVVA BAŞAR', '05538438582', 'SELÇUKBEY MH. 557 SK. NO:3 AYIŞIĞI SİT. C BLK. K:5 D:9', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1605, '17155873434', 'EMİR', 'NAMLI', '7.SINIF', '2013-06-24', 'FERHAT NAMLI', '05435429543', 'HACER NAMLI', '05438354836', 'SELÇUKBEY MAH 555 SOK ALTIN ANAHTAR SİTESİ 22B K:2 D:3', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1606, '38947145034', 'AHMET EREN', 'SABAH', '7.SINIF', '2013-02-11', 'MUHAMMET SABAH', '05052514486', 'BETÜL SABAH', '05362408134', 'PELİTLİBAĞ MAH. HÜRRİYET CAD. NO:57 D:12', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1607, '31798383440', 'ÖMER RAFET', 'TEKİN', '7.SINIF', '2013-09-12', 'FATİH TEKİN', '05346439900', 'SERPİL TEKİN', '05541977670', 'OYAK SİTESİ D3 1/C BLOK NO:7 K:2 1200 EVLER', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1608, '23473663374', 'MAHMUD SAMİ', 'BERKEZ', '7.SINIF', '2013-07-15', 'HÜSEYİN BERKEZ', '05365220460', 'FATMAGÜL', '05396416865', 'MERKEZEFENDİ MH.430 SK. 29 EKİM SİT. NO:1 K:4 D:14', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1609, '28870481064', 'FATMA NUR', 'DEMİR', '7.SINIF', '2013-02-08', 'HAKAN DEMİR', '05333606499', 'FATMA DEMİR', '05558796499', 'MERKEZEFENDİ MH. 450 SK.NO:10 YAVRU APARTMAN KAT:2', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1610, '38539159418', 'YUSUF', 'TEMİZ', '7.SINIF', NULL, 'MUHAMMET TEMİZ', '05326881337', 'GÜLSÜM TEMİZ', '05412405566', 'MERKEZ EFENDİ MAH 1736 SOK NO:110', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1611, '32182373290', 'EYMEN', 'GÖLCÜK', '7.SINIF', '2013-01-01', 'ETHEM GÖLCÜK', '05327784917', 'KADRİYE GÖLCÜK', '05373146028', 'MEHMETÇİK MAH 2554 SOK NO:14', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1612, '40030110638', 'ÖMER ASAF', 'KAYIKCI', '7.SINIF', '2013-05-29', 'ERHAN KAYIKCI', '05415039290', 'KÜBRA KAYIKCI', '05532171419', 'KERVANSARAY MAH. 3077 SOK. NO:8', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1613, '32698354752', 'TUĞSEM', 'AFŞAR', '7.SINIF', '2012-10-11', 'HALİL AFŞAR', '05333239515', 'ÜMMÜ AFŞAR', '05448789228', 'KERVANSARAY MAH. 3009 SOK. NO:17', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1614, '40006109246', 'BERAT', 'KARAKUŞ', '7.SINIF', '2013-01-10', 'NEDİM KARAKUŞ', '05559648581', 'AYNUR KARAKUŞ', '05437249373', 'KARŞIYAKA MH. 2439 SOKAK', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1615, '29632457570', 'İNCİ', 'VELA', '7.SINIF', '2022-11-20', 'GÖKHAN VELA', '05396654408', 'HATİCE HASRET VELA', '05547817599', 'KARAHASANLI MAH 2025 SOK NO:23 B1 D:5 (SERVİS YOK)', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1616, '40756086668', 'FERAYE BEYZA', 'ALTUNCU', '7.SINIF', '2012-11-12', 'YASİN ERTUNÇ ALTUNCU', '05544616325', 'ÜMMÜ ALTUNCU', '05544616324', 'İSTİKLAL MAH. 1178 SOK. NO:7 D:7', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1617, '42220037190', 'ERKAN', 'ERGUN', '7.SINIF', '2013-05-03', 'EMİN ERGUN', '05057094570', 'NURAY ERGUN', '05056961497', 'İNCİLİPINAR MAH ULUS CAD SELİNAY SİTESİ K:3 D:5', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1618, '20839749024', 'İBRAHİM CİHAT', 'ÖZER', '7.SINIF', '2013-03-13', 'ALİ ÖZER', '05358885850', 'SEMA ÖZER', '05325255589', 'DEĞİRMENÖNÜ MH. 1598 SK. NO:37/6', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1619, '39811124100', 'AHSEN ELA', 'KANIK', '7.SINIF', '2013-08-24', 'İSMET KANIK', '05452417085', 'MERYEM KANIK', '05064952632', 'ÇAKMAK MAH. 145. SOK. NUREVLER SİTESİ A BLOK K:3/12', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1620, '37222203360', 'ELİF', 'TURGUT', '7.SINIF', '2013-10-23', 'ENVER TURGUT', '05414526887', 'GÜLSÜM TURGUT', '05342009633', 'ASMALIEVLER MH. 6607 SK. NO:11', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1621, '12803019004', 'ZEYNEP SÜMEYYE', 'YILMAZ', '7.SINIF', NULL, 'ERSOY YILMAZ', '05374001242', 'SEMRA YILMAZ', '05362408148', 'ADALET MAH ALPARSLAN CAD NO:17 D:5', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1622, '25559716066', 'EYMEN', 'ŞAHİN', '6.SINIF', NULL, 'SERKAN ESMER', '05322137917', 'HÜLYA ŞAHİN', '05373650144', '', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1623, '38215985102', 'EGE BARAN', 'ADAY', '6.SINIF', NULL, 'YUSUF ADAY', '05323560177', 'PINAR ADAY', '05437842219', 'ZEYTİNKÖY MAH. VATAN BLV.N:47 D:1 MERYEM APT.', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1624, '40975058446', 'AZRA NUR', 'YEŞİL', '6.SINIF', NULL, 'MEDET YEŞİL', '05335666695', 'ELİF YEŞİL', '05548722497', 'BAĞBAŞI ZÜMRÜT MAH.HUZUR CAD. NO:60 SOYLU SİTESİ D BLOK K:4 D:10', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1625, '38459275632', 'MEHMET EFE', 'SÜYÜR', '6.SINIF', '2013-10-19', 'MUHAMMET AYCAN SÜYÜR', '05538313196', 'VİLDAN SÜYÜR', '05520207737', 'İNCİLİPINAR MAH 1247 SOK NO:3 K:2 D:7 BİRLİK APT', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1626, '14317947040', 'BERKİN URAS', 'ÇÖMLEKÇİ', '6.SINIF', '2014-03-25', 'İBRAHİM ÇÖMLEKÇİ', '05526302069', 'BİLGE KORKMAZ', '05066804058', 'CİNKAYA BULV ATLANTİS', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1627, '27428653748', 'FEYYAZ EFE', 'ÖZER', '6.SINIF', '2014-08-08', 'YUSUF ÖZER', '05325576728', 'RUKİYE ÖZER', '05327357663', 'YENİŞAFAK MAH. 1113 SOK NO:6 C1 BLOK D:20', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1628, '35311247264', 'AFRA BETÜL', 'KARATOPÇU', '6.SINIF', '2014-02-22', 'ALİ KARATOPÇU', '05549280088', 'NAZLI KARATOPÇU', '05469280088', 'YENİŞAFAK MAH. 1017 SOK. UZUNKENT SİTESİ A1 BLOK', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1629, '21139740018', 'MEHMET AKİF', 'YAĞMUR', '6.SINIF', '2013-12-03', 'MAHMUT SAMİ YAĞMUR', '05072615787', 'GÜLÇİN YAĞMUR', '05057046083', 'YENİŞAFAK MAH 1050 SOK NO:31 GOLDEN LİFE SİTESİ C BLOK K3 D9', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1630, '30923537260', 'EREN', 'FENERCİOĞLU', '6.SINIF', '2014-06-04', 'CEM FENERCİOĞLU', '05333446336', 'BEYZA FENERCİOĞLU', '05393759718', 'YENİŞAFAK MAH 1020 SOK NO:16 F-5', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1631, '35375388866', 'YUSUF ARDA', 'BALAY', '6.SINIF', '2014-02-10', 'UĞUR BALAY', '05326067186', 'FİLİZ BALAY', '05427353762', 'YENİŞAFAK MAH 1017 SOK UZUNKENT SİTESİ A3 BLOK D:14', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1632, '33676301874', 'İKRA NEBİYE', 'ÜLKER', '6.SINIF', '2014-04-16', 'POLAT ÜLKER', '05445501418', 'ÇİĞDEM', '05468700112', 'TOPRAKLIK MAH.675 SK. NO:8 D:4 ÖZEL APT.', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1633, '32957469432', 'GÜLŞEN NİSA', 'ERTEN', '6.SINIF', '2014-04-14', 'ERCAN ERTEN', '05334920225', 'MÜRÜVVET ERTEN', '05073320454', 'ŞEMİKLER MAH. CİNKAYA BULV. SAHRA PARK EVLERİ A BLOK', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1634, '17713833788', 'BUĞLEM', 'TÜLÜBAŞLI', '6.SINIF', '2014-05-08', 'MEHMET TÜLÜBAŞLI', '05532182458', 'AYŞE TÜLÜBAŞLI', '05073817905', 'ŞEMİKLER MAH. 3060 SOK. NO:18 K:1', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1635, '41077055098', 'MUHAMMED MAHİR', 'ÇELİK', '6.SINIF', '2014-12-22', 'MÜFİT ÇELİK', '05363488963', '', '05422198448', '', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1636, '32806337462', 'EMİRHAN', 'ÖZCAN', '6.SINIF', '2014-03-26', 'KAMİL ÖZCAN', '05055745535', 'ÜMRAN ÖZCAN', '05524745536', '', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1637, '43427118392', 'ELİF ERVA', 'İBAR', '6.SINIF', '2013-12-19', 'HÜSEYİN İBAR', '05304099410', 'SENA İBAR', '05514105764', '', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1638, '18100841294', 'AVNİ', 'TAŞBAŞ', '6.SINIF', NULL, 'İBRAHİM TAŞBAŞ', '05317306756', 'NESRİN TAŞBAŞ', '05535578606', '', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1639, '31397521472', 'FATİH SELİM', 'DAVARCI', '6.SINIF', '2014-05-22', 'FATİH DAVARCI', '05435550981', 'AYŞE DAVARCI', '05342245101', 'YENİŞAFAK MAH 1181 SOK NO:1 HAN APT', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1640, '17668856108', 'HÜSEYİN AHMET', 'YANAR', '6.SINIF', '2014-01-01', 'ESVET YANAR', '05335766689', 'DEFNE YANAR', '05064588833', 'YENİŞAFAK MAH 1106 SOK NO:10 ÇINAR APT', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1641, '61288430484', 'SEDAT', 'ŞİRİN', '6.SINIF', '2014-05-27', 'MURAT ŞİRİN', '05321796664', 'NURSEN ŞİRİN', '05323049220', 'SİNPAŞ KONUTLARI', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1642, '35296247716', 'EYMEN', 'TEKİN', '6.SINIF', NULL, 'MUSTAFA TEKİN', '05322369707', 'DUDU', '05452813936', 'MERKEZ MH. FEVZİ ÇAKMAK CD. NO:111', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1643, '28802607968', 'METE', 'AKBULUT', '6.SINIF', '2014-07-18', 'METİN AKBULUT', '05392153864', 'ŞEYDA AKBULUT', '05392153865', 'KARAHASANLI MAH 2020 SOK NO:10/B D:8 SADE YAŞAM DEMİRTEN', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1644, '23258792794', 'ONUR', 'YAMAN', '6.SINIF', '2014-11-25', 'YASİN YAMAN', '05367067878', 'EMİNE YAMAN', '05545427567', 'HALLAÇLAR MAH 3012 SOK NO:15', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1645, NULL, 'HAMZA', 'ÇETİN', '6.SINIF', NULL, 'HİKMET ÇETİN', '05077458555', 'CEYLAN ÇETİN', '05394458577', 'GÜMÜŞÇAY MAH 4171 SOK NO:31', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1646, '99019845320', 'AHMED', 'ÖZDAĞ', '6.SINIF', '2014-06-20', 'ZAFER ÖZDAĞ', '05445511942', 'SAMİYE ÖZDAĞ', '05449065577', 'GÜLTEPE MAH 4572 SOK NO:7', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1647, '26672228016', 'AHMET KEREM', 'ÖZSÜZER', '6.SINIF', '2014-06-20', 'YILMAZ ÖZSÜZER', '05352644989', 'NURCAN ÖZSÜZER', '05353603833', 'GÜLTEPE MAH 4309 SOK/1 NO:17 K:1 D:1', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1648, '23107237780', 'BETÜL ŞENAY', 'DİNCER', '6.SINIF', '2013-02-06', 'ALİ DİNCER', '', 'MİRAY DİNCER', '05432253969', 'GÖKPINAR MAH 251 SOK NO:2 K:5 D:11', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1649, '99469499234', 'AZİZ ÖMER', 'ŞÜKÜN', '6.SINIF', '2014-03-24', 'MUSA ŞÜKÜN', '05368356426', 'MEMNUNİYE ŞÜKÜN', '05515198922', 'BEREKETLER MAH 10220 SOK NO:36 D:4', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1650, '27937513816', 'EYMEN', 'BAYAR', '6.SINIF', NULL, 'YÜCEL BAYAR', '05065063398', 'SANİYE BAYAR', '05054095445', 'SERVİS KARAR VERECEKLER', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1651, '38431143300', 'ÖMER FAİK', 'ÖZYILMAZ', '6.SINIF', '2014-03-24', 'SERHAN ÖZYILMAZ', '05055864230', 'SEVİM', '05055864231', 'PELİTLİBAĞ MH. 1108 SK. NO:1', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1652, '15892914830', 'BELİS NİSA', 'IŞIK', '6.SINIF', '2013-11-21', 'ALAATTİN IŞIK', '05325902943', 'RAZİYE IŞIK', '05465902943', 'MERKEZEFENDİ GÖVEÇLİK MAH 190 SOK NO:6 K:2', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1653, '37900160916', 'RÜZGAR', 'KARAKUŞ', '6.SINIF', '2014-10-22', 'İDRİS KARAKUŞ', '05464004036', 'NURCAN KARAKUŞ', '05432683397', 'KARŞIYAKA MH. 2439 SK. NO:22/2', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1654, '29384432016', 'ADEM BERK', 'SANCAR', '6.SINIF', '2014-06-28', 'ERDAL SANCAR', '05533292818', 'YASEMİN SANCAR', '05375998882', 'KARAHASANLI MAH AKÇA CAD KARDELEN APT NO:39 K:2 D:6', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1655, '33311457614', 'MUSA KAAN', 'YILMAZ', '6.SINIF', NULL, 'MEHMET YILMAZ', '05324020840', 'BİLGE YILMAZ', '05417223409', 'HALLAÇLAR MAH 3035 SOK NO:31', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1656, '32036500100', 'EREN', 'CALP', '6.SINIF', '2014-05-07', 'SUAT CALP', '05337700849', 'TUĞBA AKIN CALP', '05545747679', 'HALLAÇLAR MAH 3001 SOK NO:4', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1657, '41362065894', 'KEMAL SALİH', 'UYANIK', '6.SINIF', '2013-11-19', 'MUHAMMED UYANIK', '05318533335', 'AYŞEN UYANIK', '05013369039', 'GÜLTEPE MAH. 4838 SOK. NO:3 C BLOK D:5', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1658, '24244616100', 'YAĞMUR', 'KOYUNCU', '6.SINIF', '2014-07-10', 'MURAT KOYUNCU', '05331360648', 'NEŞE KOYUNCU', '05384675931', 'GONCALI MH. 2. SK. NO:168/1', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1659, '29129591244', 'ÖMER', 'ÖZDİN', '6.SINIF', '2014-04-22', 'ALİ ÖZDİN', '05302805065', 'AHUNUR ÖZDİN', '05544542721', 'GERZELE MAH M KEMAL AYKURT CAD NO:30 A1 BLOK D:4', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1660, '31445519888', 'MERT', 'GENCER', '6.SINIF', '2014-05-14', 'HAKAN GENCER', '05317744645', 'NEZAHAT GENCER', '05416020358', 'DEĞİRMENÖNÜ MAH 1386 SOK NO:12/1', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1661, '32921470648', 'ŞEVVAL ZÜLAL', 'BOZOĞLU', '6.SINIF', '2014-04-11', 'MUHAMMET SAİD BOZOĞLU', '05335684876', 'SEMA BOZOĞLU', '05357687859', 'BAŞKARCI MH. 1072 SK. KAZANOĞLU SİT. J BLK. K:1 D:1', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1662, '33115320480', 'ZEYNEP DURU', 'KOÇER', '6.SINIF', NULL, 'OSMAN KOÇER', '05325111727', 'SÜEDA', '05052175986', 'ANAFARTALAR MH. 2041/1 SK. NO:8 K:3', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1663, '21604704026', 'YUSUF FURKAN', 'KOCAKULAK', '6.SINIF', '2014-05-05', 'İSMAİL KOCAKULAK', '05323425445', 'SEHER KOCAKULAK', '05412809602', 'AKTEPE MH. 2411 SK. NO:6', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1664, '15031923228', 'NİHAT ANIL', 'KILINÇ', '6.SINIF', '2014-03-21', 'AKIN KILINÇ', '05326485234', 'GÜLŞAH KILINÇ', '05349596078', 'AKKONAK MH. 1723 SK. NO:81 K:2', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1665, '23267792402', 'ESMA', 'GÖR', '6.SINIF', '2018-11-26', 'AZİZ GÖR', '05388416525', 'YASEMİN GÖR', '05071247924', 'AKÇEŞME MAH. ERTUĞRUL GAZİ CD. N:89 K:3', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1666, '30242559998', 'DEREN', 'DEMİR', '6.SINIF', '2014-06-18', 'MELİH DEMİR', '05426822244', 'FATMA DEMİR', '05448356261', 'ADALET MAH 10130 SOK NO:18 GÖRGÜLÜ APT', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1667, '11734139638', 'YILDIZ', 'BECEREN', '5.SINIF', NULL, 'N', '', 'NEVRUZ AL', '05524047808', '', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1668, '11960048582', 'CANSU', 'HANÇAR', '5.SINIF', NULL, 'FEHMİ HANÇAR', '05322436164', 'SELDA HANÇAR', '05559827778', '', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1669, '39382126060', 'OĞUZHAN', 'EKİCİ', '5.SINIF', NULL, 'OĞUZ EKİCİ', '05067721515', 'MİHRİBAN EKİCİ', '05064661414', '', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1670, '24656746112', 'ŞEYMA', 'YURTTÜRK', '5.SINIF', '2014-10-13', 'HÜSEYİN CEM YURTTÜRK', '05325143070', 'SEZANUR YURTTÜRK', '05549100001', 'SELÇUKBEY MAH.658 SK.NO:5 A1 BLK.D:5', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1671, '13773108910', 'ZEYNEP FARAH', 'ÖZKAN', '5.SINIF', NULL, '', '', 'FİGEN ERSOY', '05411440409', '', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1672, '19979902062', 'MERYEM', 'ARSLAN', '5.SINIF', '2015-02-17', 'ALİ ARSLAN', '05362869600', 'GÜLSEREN ARSLAN', '05375084455', '', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1673, '18938936704', 'FATMA SARE', 'KARADEMİR', '5.SINIF', NULL, 'HİLMİ KARADEMİR', '05357436210', 'BÜŞRA KARADEMİR', '05314395290', '', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1674, '32722333500', 'AYGÜL', 'YAPAR', '5.SINIF', '2013-12-23', 'BEHÇET SERDAR YAPAR', '05364136523', 'ŞEYMA YAPAR', '05345140377', '', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1675, '37426176804', 'SÜMEYRA', 'GÜLCÜ', '5.SINIF', '2014-10-24', 'MEHMET GÜLCÜ', '05333480720', 'GÜLER GÜLCÜ', '05423190304', 'ZEYTİNKÖY MAH 4069 SOK NO:3 D:1', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1676, '15072065680', 'SEDEF', 'ÖNCAN', '5.SINIF', '2015-07-03', 'MURAT ÖNCAN', '05369711921', 'EVREN ÇAĞLAYAN ÖNCAN', '05323743122', 'SELÇUKBEY MAH. MEHMET AVCI CAD. 638 SOK. NO:23/G SERHAN SİTE', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1677, '17743832836', 'SALİH SELİM', 'AKYOL', '5.SINIF', '2015-09-28', '', '', 'TÜLAY CİNTEMİR', '05495475473', 'HACIKAPLANLAR MAH HÜRRİYET CAD NO:29 K:6 D:24', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1678, '40045089448', 'EMİR NURİ', 'MAK', '5.SINIF', '2015-06-06', 'AZİZ MAHMUT HÜDAİ MAK', '05069096381', 'MERYEM MAK', '05069096380', 'ZÜMRÜT MAH. 2018 SOK. NO:3 D:4', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1679, '18329957004', 'AYNUR ASYA', 'COŞKUN', '5.SINIF', '2015-04-06', 'UĞUR COŞKUN', '05375084451', 'ELİF COŞKUN', '05368780760', 'ZEYTİNKÖY MAH 4028 SK NO 1 D 1', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1680, '13935103500', 'ELİF SU', 'ÖZDEMİR', '5.SINIF', '2015-07-29', 'FATİH ÖZDEMİR', '05536784020', 'ÜMRAN ÖZDEMİR', '05532725691', 'YENİŞAFAK MAH 1075/1 SOK N0:7 MEHMET BEY KONUTLARI', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1681, '16858862318', 'YUSUF KAĞAN', 'GÜLDÜN', '5.SINIF', '2015-09-07', 'EROL GÜLDÜN', '05532680781', 'AYŞE MERVE GÜLDÜN', '05545588495', 'YENİŞAFAK MAH 1025 SOK NO:5 D:8 GÜLERKENT APT', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1682, '16719010780', 'YUSUF TAHA', 'AYDEMİR', '5.SINIF', '2015-05-25', 'BİLAL AYDEMİR', '05335219141', 'DUYGU AYDEMİR', '05558615398', 'YENİ ŞAFAK MAH 1034 SOK NO:3', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1683, '34357279178', 'ESİN', 'TURHAN', '5.SINIF', '2015-08-25', 'HASAN TURHAN', '05393513087', 'HUMA TURHAN', '05075051215', 'TOPRAKLIK MAH HALK CAD NO:6 IŞIK APT', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1684, '16237758560', 'HÜMA SULTAN', 'ALABACAK', '5.SINIF', '2015-06-12', 'YILMAZ ALABACAK', '05449093482', 'SERMİN ALABACAK', '05058185209', 'ŞEMİKLER MAH.3006 SK. DEMİRCİ KONUTLARI B2 BLOK D:8', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1685, '17678978722', 'YİĞİT', 'ORHAN', '5.SINIF', '2015-04-24', 'MUTLU ORHAN', '05468496940', 'SEÇİL ORHAN', '05359827178', 'ŞEMİKLER MAH. OSMAN GAZİ CAD. DEMİRCİLER SİT A BLOK K:2', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1686, '19795764356', 'HAMZA', 'DEDEOĞLU', '5.SINIF', '2015-08-08', 'SERKAN DEDEOĞLU', '05066632170', 'BAHAR DEDEOĞLU', '05062198922', 'ŞEMİKLER MAH 3131 SOK NO:2 DEDELAVİLA', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1687, '18899938038', 'SERRA', 'KOCAYİĞİT', '5.SINIF', '2015-03-20', 'SAMET KOCAYİĞİT', '05374276063', 'MERVE KOCAYİĞİT', '05387021714', '', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1688, '30068565722', 'FATİH', 'KURAN', '5.SINIF', '2014-06-20', 'ERDAL KURAN', '05359269670', 'ŞERİFE KURAN', '05372410456', 'YENİŞAFAK MAH ALPARSLAN TÜRKEŞ BULV NO:57/2', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1689, '35833229960', 'ZEYNEP BEYZA', 'ÇAKMAK', '5.SINIF', '2014-11-03', 'ERDEN ÇAKMAK', '05366790785', 'MÜCAHİDE ÇAKMAK', '05062566226', 'ŞEMİKLER MAH. 3122 SOKAK ALTINEVLER SİTESİ D BLOK NO:28', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1690, '15486051842', 'NERİS', 'KAYNAK', '5.SINIF', '2015-06-24', 'MEHMET KAYNAK', '05056688272', 'AYŞE KAYNAK', '05066056642', 'ŞEMİKLER MAH 4804 SOK NO:112 DENİZKENET APT K:1 D:1', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1691, '25591585224', 'YİĞİT ALP', 'ULUTAŞ', '5.SINIF', '2015-04-29', 'MUSTAFA ULUTAŞ', '05355440404', 'BUŞRA ULUTAŞ', '05059379674', 'ŞEMİKLER MAH 3131 SOK NO:22 B BLOK D:6', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1692, '18092964964', 'ESLEM', 'KANDEMİR', '5.SINIF', '2015-04-15', 'ALİ KANDEMİR', '05327763980', 'ZUHAL KANDEMİR', '05447763980', 'MEHMET AKİF ERSOY MAH 57 SOK NO:10/1 A BLOK D:7 IHLAMIR EVLR', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1693, '12210161082', 'RÜVEYDA REYYAN', 'ŞAHİN', '5.SINIF', '2015-09-04', 'İBRAHİM SERDAR ŞAHİN', '05323031165', 'HACER ŞAHİN', '05064066589', 'KARAHASANLI MH. 2095 SK. NO:18 GÖMCE SİT. B BLK. ÜÇLER', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1694, '34783264816', 'ERSOY', 'GÖLCÜK', '5.SINIF', NULL, 'FATİH GÖLCÜK', '05327784919', '', '', 'HALICI GÖLCÜKLER', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1695, '26627680456', 'NURSEL', 'KABÜK', '5.SINIF', '2014-09-03', 'MUHAMMET KABÜK', '05076926216', 'PINAR KABÜK', '05436926216', 'ÇAKMAK MAH 129 SOK NO:25 D:5 GÜL SİTESİ B BLOK', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1696, '27350656374', 'YAREN', 'TATAR', '5.SINIF', '2014-08-18', 'AHMET TATAR', '05302440317', 'SEDA TATAR', '05301400317', 'ALTINTOP MAH 1591 SOK YAZICI SİT B2 BLOK NO:14 D:2', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1697, '24031623236', 'MURAT EYMEN', 'AYKAÇ', '5.SINIF', '2015-05-24', 'TUNCAY AYKAÇ', '05552663309', 'KEVSER AYKAÇ', '05544205368', 'AKÇEŞME MAH 2527 SOK NO:24/A', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1698, '25070732354', 'ELİF HİFA', 'KARAÇAY', '5.SINIF', '2014-10-08', 'CEMİLE KARAÇAY', '05448104652', 'İBRAHİM KARAÇAY', '05364354652', 'ADALET MAH 10109 SOK KAYAN APT NO:9 K:2', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1699, '24113348354', 'AHMET', 'HABER', '5.SINIF', '2015-04-09', 'ALİ HABER', '05336788572', 'HİLAL HABER', '05309051694', 'SERVİS ADRESTEN SONRA BELLİ OLACAK', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1700, '17846973184', 'ASRIN MERT', 'KUZU', '5.SINIF', '2015-04-20', 'RAMAZAN KUZU', '05327433864', 'HAVVA SELCEN KUZU', '05493863869', 'SELÇUKBEY MH. 703 SK. ADAKENT SİT. C BLK. K:2', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1701, '22850806304', 'EMEL MİRA', 'KARACA', '5.SINIF', '2014-12-06', 'MUHAMMET TAHA KARACA', '05327226935', 'NAZİRE KARACA', '05300379848', 'SELÇUKBEY MAH. 645 SK. NO:1 D:11 DORUK APT.', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1702, '12931993276', 'AHMET EREN', 'HAYAT', '5.SINIF', '2015-05-11', 'İSMAİL HAYAT', '05326758680', 'AYŞE HAYAT', '05055811665', 'PELİTLİBAĞ MH. 1124 SK. NO:10 ŞÜKÜR APT. K:3', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1703, '21056866150', 'MEHMET MUSA', 'BARDAKCI', '5.SINIF', '2015-01-27', 'İBRAHİM HAKKI BARDAKCI', '05327259881', 'SÜMEYYE BARDAKCI', '05309764908', 'MEHMET AKİF ERSOY MAH HÜDAİ ORAL CAD 57 SOK NO:1/A BAŞAK APT', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1704, '39490608908', 'OĞUZ KAĞAN', 'ATA', '5.SINIF', '2015-01-22', 'RAMAZAN ATA', '05334700755', 'HAMİYET ATA', '05334611549', 'KERVANSARAY MAH. 3072 SOK. NO:6 K:3', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1705, '21541706152', 'ZEYNEP SARE', 'ERSARI', '5.SINIF', '2015-02-28', 'AHMET ERSARI', '05334208320', 'FERAH DİBA ERSARI', '05322112061', 'KERVANSARAY MAH 3123 SOK NO:1', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1706, '12236016472', 'DENİZ', 'ERDOĞAN', '5.SINIF', '2014-08-12', 'NEVİN ERDOĞAN', '05437803403', 'MUSTAFA CEM ERDOĞAN', '05332855780', 'KAYIHAN MAH 3003 SOK NO:10 K:3', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1707, '22007834488', 'HİRANUR SARE', 'ŞAHAL', '5.SINIF', '2015-01-01', 'BÜLENT ŞAHAL', '05324646303', 'HAYRİYE', '05357890989', 'KARAMAN MH. 1755 SK. NO:16 K:2', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1708, '16549895366', 'FATIMA ZEHRA', 'ASLAN', '5.SINIF', '2015-02-11', 'ALPAY ASLAN', '05053251112', 'GÜLİSTAN ASLAN', '05057405330', 'KARAHASANLI MAH 2048 SOK NO:8/5', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1709, '15730913346', 'MEHMET', 'SANCAR', '5.SINIF', '2015-10-10', 'SALİH EGEMEN SANCAR', '05383513237', 'HAVVA SANCAR', '05078513597', 'KARAHASANLI MAH 2018 SOK ANADOLU KASRI A BLOK', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1710, '12881632102', 'ESLEM ERVA', 'ÜNAL', '5.SINIF', '2015-07-06', 'GÖKHAN ÜNAL', '05492419149', 'RUMEYSA ÜNAL', '05455500155', 'GÜLTEPE MAH. 4828 SOK. NO:3 / 20 ADAPARK KONUTLARI', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1711, '16884005260', 'EYMEN BERA', 'GÜN', '5.SINIF', '2015-05-06', 'AYKUT GÜN', '05435266462', 'PINAR GÜN', '05419779064', 'GÜLTEPE MAH 4580 SOK NO:85 K:1', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1712, '20984868588', 'TUĞÇE NAZ', 'KOCAMAN', '5.SINIF', '2015-01-27', 'İSMAİL KOCAMAN', '05322506208', 'NUR KOCAMAN', '05368559780', 'GERZELE MAH GERİZ CA NO:49 1000 YIL SİTESİ K:3 D:7', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1713, '23789775020', 'KEREM ZEKİ', 'İNCE', '5.SINIF', '2014-11-06', 'AHMET İNCE', '05327779080', 'MERVE İNCE', '05552938868', 'ÇAKMAK MAH 5600 SOK AYA REZİDANS C BLOK K:4 NO:7', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1714, '12873138902', 'YUSUF BUĞRA', 'IŞIKLI', '5.SINIF', '2015-08-22', 'YUNUS IŞIKLI', '05548706256', 'ESRA YILDIZ IŞIKLI', '05076472244', 'ÇAKMAK MAH 129 SOK NO:25 GÜL SİTESİ A BLOK D:6', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1715, '25478718776', 'MUSAB ENSAR', 'ÖZVEREN', '5.SINIF', '2014-09-25', 'ÖNDER ÖZVEREN', '05424741981', 'BELKIS ÖZVEREN', '05444420205', 'BARUTÇULAR MAH 10217 SOK NO:7 K:2', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1716, '38770132034', 'ADİL KAĞAN', 'SAVAŞ', '5.SINIF', '2015-02-25', 'BİLAL SAVAŞ', '05053244843', 'BİLGE ÖNDER SAVAŞ', '05057796939', 'BAĞBAŞI MAH 1026 SOK BAĞBAŞI KONUTLARI D BLOK K:2 D:7', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1717, '36274215178', 'FEYZA', 'ELMAS', '5.SINIF', '2015-07-28', 'ERCAN ELMAS', '05359826261', 'RUKİYE ELMAS', '05356801514', 'ASMALIEVLER MAH. 6616 SOK. NO:12 D:4', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1718, '35890227916', 'ZEYNEP ESRA', 'DEMİRTAŞ', '5.SINIF', '2015-08-12', 'AHMET DEMİRTAŞ', '05332626525', 'MUNİSE DEMİRTAŞ', '05304116034', 'ANAFARTALAR MAH. 2164 SOK. NO:16 K:4 D:3', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1719, '22799808094', 'MUHAMMET TAHİR', 'CEYHUN', '5.SINIF', '2014-12-09', 'MURAT CEYHUN', '05412975764', 'HATİCE CEYHUN', '05316350994', 'AKTEPE MAH 2411 SOK NO:10 KAT:4', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1720, '28183484806', 'ZEYNEP', 'EKİNCİ', '5.SINIF', '2015-01-02', 'YASİN EKİNCİ', '05058953558', 'MERYEM EKİNCİ', '05066213545', 'ADALET MH. K.KARABEKİR CD. NO:11 K:2 D:5', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09');
INSERT INTO `vp_students` (`id`, `tc_no`, `first_name`, `last_name`, `class`, `birth_date`, `father_name`, `father_phone`, `mother_name`, `mother_phone`, `address`, `teacher_name`, `teacher_phone`, `notes`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(1721, '20720877362', 'ELİF FEYZA', 'İÇEN', '5.SINIF', '2015-02-04', '', '', 'SARE İÇEN', '05064578688', 'ADALET MAH KAZIM KARABEKİR CAD NO:9 YUNUS APT', 'EMİNE YILDIZ', '05454549732', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1722, '31162228780', 'KAVİN', 'TİLEYİ', '4-D', '2016-06-17', 'MUHAMMET TİLEYİ', '05324456229', 'NAZAN TİLEYİ', '05066068009', '', 'NİĞMET DEMİREL', '05446134717', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1723, '54064771284', 'ERTUĞRUL', 'DEMİR', '4-D', NULL, 'ALİ DEMİR', '05352850209', 'SEVDE DEMİR', '05543101193', '', 'NİĞMET DEMİREL', '05446134717', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1724, '74248093186', 'ALİN NİSA', 'ERSOY', '4-D', NULL, 'AHMET ERSOY', '05304134616', 'GÜLİZAR ERSOY', '05331270021', '', 'NİĞMET DEMİREL', '05446134717', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1725, '76630013758', 'ESİLA NUR', 'UZUN', '4-D', '2015-11-15', 'ZAFER UZUN', '05325628925', 'ARİFE UZUN', '05418105243', 'YENİŞAFAK MAH 1183 SOK NO:2 D:1 AYDEMİR APT', 'NİĞMET DEMİREL', '05446134717', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1726, '22283825248', 'ELİF ALYA', 'ERTEKİN', '4-D', '2014-11-26', 'UTKU ERTEKİN', '05320652035', 'AYBİKE ERTEKİN', '05326300594', 'SERVERGAZİ MAH 215 SOK NO:11 VİZYON PREMIUM KONT A2', 'NİĞMET DEMİREL', '05446134717', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1727, '36677340590', 'YAĞMUR SULTAN', 'SÜRMELİOĞLU', '4-D', '2015-06-20', 'SALAHATTİN SÜRMELİOĞLU', '05520127873', 'ESMA SÜRMELİOĞLU', '05529263223', 'SABAH VELİ BIRAKACAK AKŞAM İSMAİL REKLAM', 'NİĞMET DEMİREL', '05446134717', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1728, '54484757240', 'MUSAB', 'ŞEMSİOĞLU', '4-D', '2016-04-17', 'HASAN ŞEMSİOĞLU', '05349722695', 'MERYEM ŞEMSİOĞLU', '05349830097', 'KERVANSARAY MAH 3124 SOKAK 2D BİNA', 'NİĞMET DEMİREL', '05446134717', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1729, '28657489504', 'EYLÜL ZEHRA', 'ÖZTÜRK', '4-D', NULL, 'ÖZKAN ÖZTÜRK', '05433540530', 'SULTAN ÖZTÜRK', '05468959111', 'ŞEMİKLER MH. CİNKAYA BULV. DİVA KONUTLARI K:3 D:9', 'NİĞMET DEMİREL', '05446134717', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1730, '76390021772', 'YUSUF', 'ÇOBANOĞLU', '4-D', '2015-11-19', 'MUSAB NURİ ÇOBANOĞLU', '05525025056', 'HATİCE ÇOBANOĞLU', '05415020056', 'ŞEMİKLER MAH 3137 SOK NO:2 A11 SEYİR KONUTLARI', 'NİĞMET DEMİREL', '05446134717', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1731, '99249922624', 'NAZAR', 'SHAKROUF', '4-D', '2016-04-02', 'MOHAMAD FERAS SHAKROUF', '05417416063', 'NATALİİA SHAKROUF', '', 'SİNPAŞ KONUTLARI', 'NİĞMET DEMİREL', '05446134717', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1732, '28750487360', 'UHUT NACİ', 'SELEK', '4-D', NULL, 'MUSTAFA SELEK', '05358944789', 'HATİCE SELEK', '05425878528', 'ADALET MH. ALPARSLAN CD. NO:3/1 ALPER TUNGA APT.', 'NİĞMET DEMİREL', '05446134717', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1733, '10947203174', 'ZEYNEP', 'BAKİLER', '4-D', '2015-10-03', 'ÖMER BAKİLER', '05374010484', 'SİNEM BAKİLER', '05415850234', 'İLBADE MAH 297 SOK NO:41', 'NİĞMET DEMİREL', '05446134717', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1734, '51832845680', 'ZEYNEB NUR', 'KOPARAN', '4-D', '2016-06-09', 'MELİH KOPARAN', '05387993858', 'RULA KOPARAN', '05354229665', 'HALLAÇLAR MAH 3005 SOK NO:3 K:2 D:6 SEYİTHAN APT', 'NİĞMET DEMİREL', '05446134717', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1735, '33847739802', 'MERT AKİF', 'ALTINTAŞ', '4-D', '2015-11-03', 'EYÜP ALTINTAŞ', '05369266704', 'BANU ALTINTAŞ', '05517102363', 'AKÇEŞME MAH 2522 SOK NO:9 K:1 D:1', 'NİĞMET DEMİREL', '05446134717', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1736, '28987458034', 'ELİF', 'DEMET', '4-D', '2015-07-16', 'NİYAZİ DEMET', '05308928984', 'GÜLSÜM ZEYBEKOĞLU', '05060475984', '15 MAYIS MAH 719 SOK NO:1', 'NİĞMET DEMİREL', '05446134717', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1737, '12572005192', 'DORUK EMRE', 'YILDIZ', '4-C', NULL, 'EMRE YILDIZ', '05320532681', 'ŞAHİKA YILDIZ', '05321538097', '', 'MEHMET ZAHİD YILMAZ', '05465511553', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1738, '28483495564', 'MUHAMMED EMİR', 'ÖZ', '4-C', NULL, 'MUSTAFA ÖZ', '05336012796', 'GÜLSEREN ÖZ', '05359754297', '', 'MEHMET ZAHİD YILMAZ', '05465511553', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1739, '46214026948', 'HALİD', 'ERGÜL', '4-C', NULL, 'BAYRAM ERGÜL', '05465492541', 'HAYRİYE ERGÜL', '05545734762', 'KERVANSARAY MAH.3016 SOK.NO:8 D:7', 'MEHMET ZAHİD YILMAZ', '05465511553', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1740, '15875698986', 'ENES FURKAN', 'ATA', '4-C', NULL, 'HAMİDULLAH ATA', '05075812374', 'ARZU ATA', '05077592374', 'KARAHASANLI MAH.KASIMBEY SİTESİ D BLOK', 'MEHMET ZAHİD YILMAZ', '05465511553', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1741, '44849078486', 'ZEYNEP EDA', 'DEDE', '4-C', '2016-11-16', '', '', 'FATMA ÇALHAN', '05064345541', 'İNCİLİPINAR MAH 1232 SOK. NO:21/2', 'MEHMET ZAHİD YILMAZ', '05465511553', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1742, '64768410174', 'CENNET SENA', 'MANDAL', '4-C', '2015-12-10', 'OSMAN MANDAL', '05077167004', 'FATMA MANDAL', '05077041326', 'YENİŞAFAK MAH 1020 SOK LAVANTA SİTESİ NO:2 D BLOK', 'MEHMET ZAHİD YILMAZ', '05465511553', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1743, '26908363276', 'ERTUĞRUL', 'DEMİRALAY', '4-C', '2016-06-29', 'MEHMET DEMİRALAY', '05056658704', 'HÜLYA DEMİRALAY', '05056618705', 'ŞEMİKLER MAH. 3008 SOK. NO:1 HİLAL EVLER SİT. B BLOK K:7', 'MEHMET ZAHİD YILMAZ', '05465511553', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1744, '28579492590', 'RÜVEYDA', 'AYDOĞDU', '4-C', '2016-07-21', 'RECEP AYDOĞDU', '05356129945', 'AYŞE AYDOĞDU', '05342739953', 'SIRAKAPILAR MAH. LOZAN CAD. NO:31/6', 'MEHMET ZAHİD YILMAZ', '05465511553', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1745, '53122802632', 'ALİ EKREM', 'DENİZ', '4-C', '2016-03-23', 'SALİH DENİZ', '05334858624', 'AYŞE PELİN DENİZ', '05354703340', 'SIRAKAPILAR MAH SALTAK CAD AKAN APT NO:36 K:3 NO:3', 'MEHMET ZAHİD YILMAZ', '05465511553', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1746, '28693487748', 'NEFES EMET', 'ŞAHAN', '4-C', '2016-10-10', 'ALPER GÖKHAN ŞAHAN', '05323040401', 'İLKNUR SOYLU ŞAHAN', '05321742744', 'ÇAKMAK MAH 260 SOK NO:7/2 GÜNEYSU SİTESİ B3 BLOK K:3', 'MEHMET ZAHİD YILMAZ', '05465511553', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1747, '48196966830', 'YİĞİT EREN', 'ÇIPLAK', '4-C', '2016-08-22', 'SÜLEYMAN ÇIPLAK', '05324258341', 'NURTEN ÇIPLAK', '05469558190', 'PINARKENT 141 SOK NO:7', 'MEHMET ZAHİD YILMAZ', '05465511553', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1748, '28855481348', 'MUSTAFA SELİM', 'DOĞRUÖZ', '4-C', '2017-02-06', 'MUHAMMED İHSAN DOĞRUÖZ', '05319495347', 'KÜBRA GÜLTEN', '', 'MEHMET AKİF ERSOY MAH. 10010 SOK. NO:23 K:4 D:17', 'MEHMET ZAHİD YILMAZ', '05465511553', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1749, '48484957212', 'MEHMET AKİF', 'KAYIKCI', '4-C', '2016-08-23', 'ERHAN KAYIKCI', '05415039290', 'KÜBRA KAYIKCI', '05532171419', 'KERVANSARAY MAH. 3077 SOK. NO:8', 'MEHMET ZAHİD YILMAZ', '05465511553', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1750, '38290147914', 'CELAL', 'AFŞAR', '4-C', '2016-01-12', 'HALİL AFŞAR', '05333239515', 'ÜMMÜ AFŞAR', '05448789228', 'KERVANSARAY MAH. 3009 SOK. NO:17', 'MEHMET ZAHİD YILMAZ', '05465511553', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1751, '28636488252', 'MUSTAFA TALHA', 'ÇİÇEK', '4-C', '2016-08-18', 'NESRULLAH ÇİÇEK', '05322536052', 'SULTAN ÇİÇEK', '05496261980', 'GÜLTEPE MAH UZUNKENT YAŞAM KONUTLARI(2) D BLOK D:34', 'MEHMET ZAHİD YILMAZ', '05465511553', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1752, '75718044196', 'YUSUF', 'ADAY', '4-C', '2016-12-04', 'HASAN HÜSEYİN ADAY', '05552048234', 'GÖNÜL', '05552608824', 'GERZELE MH. 525 SK. NO:17 LÜTUF KONAĞI K:1 D:2', 'MEHMET ZAHİD YILMAZ', '05465511553', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1753, '48487957158', 'YUSUF ÖMER', 'ÇELİKBİLEK', '4-B', '2016-08-24', 'YUNUS ÇELİKBİLEK', '05347764624', 'HATİCE KÜBRA ÇELİKBİLEK', '05397261960', 'ZEYTİNKÖY MAH 4084 SOK NO:23 K:D:2', 'ELİF COŞKUN', '05368780760', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1754, '15724004968', 'SEFA ENES', 'DEMİRAY', '4-B', '2016-04-15', 'MEHMET DEMİRAY', '05414945811', 'ZAHİDE DEMİRAY', '05427331516', 'BAĞBAŞI MAH ACIPAYAM BULV AKTAY SİTESİ NO:1', 'ELİF COŞKUN', '05368780760', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1755, '53173800984', 'EMRE KAAN', 'ER', '4-B', '2016-05-19', 'YUNUS EMRE ER', '', 'EBRU ER', '05337960962', '', 'ELİF COŞKUN', '05368780760', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1756, '28438497476', 'BERAT', 'AHRAZ', '4-B', '2016-04-06', 'İBRAHİM AHRAZ', '05363522056', 'HATİCE AHRAZ', '05366074570', '', 'ELİF COŞKUN', '05368780760', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1757, '76447019806', 'HALİL SADİ', 'ÇİMENTEPE', '4-B', '2015-11-20', 'SAVAŞ ÇİMENTEPE', '05364705284', 'NİHAL ÇİMENTEPE', '05383365451', 'YENİŞAFAK MH. 1029 SK. NO:6 K:2 D:8', 'ELİF COŞKUN', '05368780760', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1758, '74083098650', 'ÇAĞRI', 'ÇAPAR', '4-B', '2016-01-24', 'RAMAZAN ÇAPAR', '05333833118', 'ASLI ÇAPAR', '05469649000', 'ŞEMİKLER MAH. 2005 SOK. ELVAN APT. K:4 D:22 GÜMÜŞ KONUTLAR', 'ELİF COŞKUN', '05368780760', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1759, '10896204864', 'EMİR ASLAN', 'ÖĞMEN', '4-B', '2015-10-07', 'SELİM ÖĞMEN', '05074084507', 'SEDEF ZELİHA ÖĞMEN', '05423145752', 'ŞEMİKLER MAH CİNKAYA BULV NO:92 ŞEHRİ ALA APT', 'ELİF COŞKUN', '05368780760', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1760, '51547855134', 'SELMA', 'ÇALHAN', '4-B', '2016-06-22', 'İBRAHİM SAİT ÇALHAN', '05068495728', 'HİLAL ÇALHAN', '05547775190', 'ATALAR MAH 932 SOK NO:1', 'ELİF COŞKUN', '05368780760', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1761, '49492923664', 'GÜLRANA', 'ÇELİK', '4-B', '2016-07-30', 'FARUK ÇELİK', '05558000577', 'KÜBRA ÇELİK', '05321396403', 'KAYIHAN MAH. MİMAR SİNAN CAD. 3001/1 SOK. NO:3', 'ELİF COŞKUN', '05368780760', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1762, '28642490690', 'MUSA KERİM', 'ARIK', '4-B', '2016-09-05', 'OSMAN ARIK', '05056973683', 'ZELİHA ARIK', '05324690530', 'KARAMAN MAH. FATİH CAD NO:152 ONUR APT', 'ELİF COŞKUN', '05368780760', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1763, '18056439286', 'BERRİN', 'ÇAKIR', '4-B', '2016-01-28', 'SELÇUK ÇAKIR', '05306977656', 'GÜLSEREN ÇAKIR', '05312368869', 'KARAHASANLI MAH 2093 SOK NO:6 PARKVADİ SİTESİ D BLOK D:4', 'ELİF COŞKUN', '05368780760', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1764, '28426498294', 'EFLİN YÜSRA', 'ARIKAN', '4-B', '2016-03-28', 'NAİL ARIKAN', '05417153753', 'ESRA ARIKAN', '05454568267', 'GÜLTEPE MAH 4835 SOK NO:3 K:5 D:10 NERGİZ KONUTLARI', 'ELİF COŞKUN', '05368780760', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1765, '55984707230', 'HÜSEYİN', 'KOYUNCU', '4-B', '2016-03-10', 'İSMAİL KOYUNCU', '05363443015', 'DİLEK KOYUNCU', '05551834930', 'GONCALI MAH. 2.SOK NO:172', 'ELİF COŞKUN', '05368780760', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1766, '28720486478', 'YUSUF', 'NAZLIGÜL', '4-B', '2016-09-28', 'MUHAMMET NAZLIGÜL', '05332721565', 'ASLIHAN NAZLIGÜL', '05334377403', 'GERZELE MH. 529 SK. AKDAĞ APT. NO:16/2', 'ELİF COŞKUN', '05368780760', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1767, '99219943512', 'MUHAMMED ÖMER TALHA', 'ÇİFTÇİ', '4-B', '2016-07-25', 'BAYRAM ÇİFTÇİ', '05314342250', 'ŞERİFE ÇİFTÇİ', '', 'ÇAKMAK MAH 175 SOK NO:5 K:4', 'ELİF COŞKUN', '05368780760', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1768, '99273941790', 'HAMZA RAHMETULLAH', 'ÇİFTÇİ', '4-B', '2015-05-16', 'BAYRAM ÇİFTÇİ', '05314342250', 'ŞERİFE ÇİFTÇİ', '', 'ÇAKMAK MAH 175 SOK NO:5 K:4', 'ELİF COŞKUN', '05368780760', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1769, '48208966452', 'MERYEM BETÜL', 'KIRBOĞA', '4-B', NULL, 'ADEM KIRBOĞA', '05373959247', 'ESRA KIRBOĞA', '05537915104', 'BAHÇELİEVLER 4000 SK. NO:21', 'ELİF COŞKUN', '05368780760', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1770, '50344206026', 'ELİF BAHAR', 'ÇETİNKÜNAR', '4-A', NULL, '', '', 'YELDA YAZIR', '05539889478', 'PELİTLİBAĞ MAH.3406 SOK NO:8 K:2 YAZIR APT.', 'EMEL TOPAL', '05343986207', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1771, '28591489646', 'ZEYNEP AZRA', 'FIRINCI', '4-A', NULL, 'FATİH FIRINCI', '05537979742', 'ŞULE FIRINCI', '05052858567', 'GERZELE MAH.YAVUZ SULTAN SELİM CAD.NO:45/4', 'EMEL TOPAL', '05343986207', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1772, '49663917904', 'SALİH FATİH', 'ERDEM', '4-A', NULL, 'EMİN ERCAN ERDEM', '05415150850', 'AYŞE ERDEM', '05511581859', 'KUŞPINAR MAH. 1285 SOK. N:10 K:3 D:8', 'EMEL TOPAL', '05343986207', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1773, '28849483006', 'OSMAN', 'KOÇER', '4-A', '2016-12-12', 'OSMAN KOÇER', '05322724713', 'ESMA KOÇER', '05385145153', 'ESKİHSAR MH. ATATÜRK CD. NO:17/3', 'EMEL TOPAL', '05343986207', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1774, '28480494324', 'ZEYNEP SILA', 'DÜNDAR', '4-A', NULL, 'TURGUT DÜNDAR', '05058139878', '', '', '', 'EMEL TOPAL', '05343986207', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1775, '28612489358', 'BEYZA', 'TEMELTAŞ', '4-A', '2016-08-14', 'SİNAN TEMELTAŞ', '05317426876', 'KAMİLE TEMELTAŞ', '05538055076', '', 'EMEL TOPAL', '05343986207', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1776, '28390497484', 'YAĞMUR', 'YARENOĞLU', '4-A', '2016-02-01', 'YASİN YARENOĞLU', '05332172056', 'AYLA YARENOĞLU', '05348903739', 'SİNPAŞ', 'EMEL TOPAL', '05343986207', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1777, '73840106706', 'ZEYNEP İDİL', 'ESKİN', '4-A', '2016-01-28', 'RAMAZAN ESKİN', '05055974225', 'KÜBRA ESKİN', '05556865122', 'MERKEZEFENDİ MH. 1700/6 SK. NO:8 ÇINAR APT. K:1', 'EMEL TOPAL', '05343986207', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1778, '48250965076', 'ESMA', 'BAYRAKDAROĞLU', '4-A', '2016-08-27', 'AZİZ BAYRAKDAROĞLU', '05072023835', 'ÜMRAN BAYRAKDAROĞLU', '05416706705', 'SÜLEYMAN ŞAH CAD. NO:15 K:3 D:6 BAĞBAŞI', 'EMEL TOPAL', '05343986207', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1779, '10554216284', 'EYMEN', 'TAŞBAŞ', '4-A', '2015-10-20', 'İBRAHİM TAŞBAŞ', '05317306756', 'NESRİN TAŞBAŞ', '05535578606', '', 'EMEL TOPAL', '05343986207', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1780, '49906909826', 'YUSUF İHSAN', 'TANRIÖĞEN', '4-A', '2016-07-16', 'CİHAN TANRIÖĞEN', '05325737470', 'SÜMEYRA TANRIÖĞEN', '05344687171', 'YENİŞAFAK MAH 1017 SOK NO:13 UZUNKENT SİTESİ A5 BLOK D:9', 'EMEL TOPAL', '05343986207', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1781, '99803465734', 'ZÜMRA', 'ŞÜKÜN', '4-A', '2016-01-09', 'MUSA ŞÜKÜN', '05368356426', 'MEMNUNİYE ŞÜKÜN', '05515198922', 'BEREKETLER MAH 10220 SOK NO:36 D:4', 'EMEL TOPAL', '05343986207', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1782, '28420498344', 'ESMA ESLEM', 'CABAR', '4-A', '2016-03-22', 'MUHAMMED ALPEREN CABAR', '05552612078', 'FATMA CABAR', '05462032036', 'ADALET MAH 10023 SOK NO:14 BAŞAK APT K:1', 'EMEL TOPAL', '05343986207', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1783, '47872977696', 'MEHMET', 'TARAKÇI', '4-A', '2016-09-05', 'MUSTAFA TARAKÇI', '05466725768', 'ZEHRA TARAKÇI', '05425523947', 'SELÇUKBEY MAH 768 SOK NO:10 B14', 'EMEL TOPAL', '05343986207', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1784, '62170492764', 'YAĞMUR', 'REYHAN', '4-A', '2016-03-22', 'UĞUR REYHAN', '05556534223', 'VESİLE REYHAN', '05365542595', 'KERVANSARAY MAH 3006 SOK KORUKENT SİTESİ NO:24', 'EMEL TOPAL', '05343986207', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1785, '73579115468', 'ERTUĞRUL', 'ÇİFTÇİ', '4-A', '2016-02-07', 'CELİL ÇİFTÇİ', '05448409508', 'NAZMİYE NUR ÇİFTÇİ', '05446630983', 'GÜLTEPE MAH 4838 SOK UZUNYAŞAM KONUTLARI 2 3/C NO:19', 'EMEL TOPAL', '05343986207', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1786, '28648490922', 'MİNA', 'KOCABAY', '4-A', '2016-09-07', 'ALİ KOCABAY', '05549369797', 'HACER MERVE KOCABAY', '05070542222', 'GÜLTEPE MAH 4804 SOK NO:50 PANAROMA SİTESİ C BLOK', 'EMEL TOPAL', '05343986207', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1787, '23800630956', 'AYSİMA MİNA', 'DURMUŞ', '4-A', '2015-09-06', 'YUSUF DURMUŞ', '05395012125', 'CANAN DURMUŞ', '05533960713', 'GERZELE MAH 535 SOK AKİS PARK KONUTLARI A BLOK D:12', 'EMEL TOPAL', '05343986207', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1788, '10640069534', 'MÜÇTEBA', 'DERE', '4-A', NULL, 'SÜLEYMAN DERE', '05325653726', 'MEHLİKA DERE', '05412354842', 'DELİKTAŞ MAH.1946 SK. NO:29', 'EMEL TOPAL', '05343986207', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1789, '51454858280', 'TUĞSEM İKRA', 'EGE', '4-A', '2016-06-23', 'TOLGA EGE', '05398147260', 'TUĞBA EGE', '05383397608', '1200 EVLER MAH 2012/1 SOK MANOLYA APT NO:17 D:6', 'EMEL TOPAL', '05343986207', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1790, '40133235696', 'ÖMER HALİS', 'BAYSAL', '3-C', '2017-03-18', 'MÜJDAT BAYSAL', '05067276003', 'BÜŞRA BAYSAL', '05496266263', 'CUMHURİYET MAH 3394 SOK NO:29', 'ZEHRA TÜCAN', '05536069125', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1791, '28996478092', 'BAHADIR', 'DEMİRCİ', '3-C', '2017-06-03', 'AHMET DEMİRCİ', '05064841143', 'HACER DEMİRCİ', '05064841142', '', 'ZEHRA TÜCAN', '05536069125', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1792, '28957477492', 'KEMAL', 'EMEK', '3-C', '2017-05-05', 'KENAN EMEK', '05301734318', 'AYSEL EMEK', '05312674999', 'TEK YÖN KULLANACAK REFORM YAPI ADRESİNE GİDECEK', 'ZEHRA TÜCAN', '05536069125', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1793, '29125471922', 'ELİFSU', 'ŞİRİN', '3-C', '2017-08-24', 'FERHAT ŞİRİN', '05322114051', 'TUĞBA ŞİRİN', '05417346463', 'SİNPAŞ KONUTLARI', 'ZEHRA TÜCAN', '05536069125', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1794, '68515280454', 'HÜSEYİN ENSAR', 'İBAR', '3-C', '2017-08-24', 'HÜSEYİN İBAR', '05304099410', 'SENA İBAR', '05514105764', '', 'ZEHRA TÜCAN', '05536069125', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1795, '43535021676', 'BERAT', 'ŞİRİN', '3-C', '2017-05-15', 'MURAT ŞİRİN', '05321796664', 'NURSEN ŞİRİN', '05323049220', 'SİNPAŞ KONUTLARI', 'ZEHRA TÜCAN', '05536069125', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1796, '22127821498', 'BEKİR GİRAY', 'AYAN', '3-C', '2017-02-27', 'ARİF YASİN AYAN', '05056290585', 'FEYZANUR AYAN', '05426492360', 'FATURAYI ANNEYE KES ?', 'ZEHRA TÜCAN', '05536069125', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1797, '44420092702', 'ASYA', 'KADIOĞLU', '3-C', '2016-11-28', 'HAKAN KADIOĞLU', '05321331135', 'RAZİYE KADIOĞLU', '05337625436', 'DELİKTAŞ MAH 1983 SOK NO:12', 'ZEHRA TÜCAN', '05536069125', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1798, '28801484454', 'DİLA SUDE', 'ÇAMUR', '3-C', '2017-01-01', 'MUHAMMET ÇAMUR', '05057172061', 'FATMA ÇAMUR', '05067285976', 'ÇAKMAK MAH 153 SOK NO:11 K:3 D:7', 'ZEHRA TÜCAN', '05536069125', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1799, '28807482638', 'FURKAN', 'AYDOĞMUŞ', '3-C', '2017-01-01', 'SÜLEYMAN AYDOĞMUŞ', '05462244002', 'GÜLŞAH AYDOĞMUŞ', '05462244005', 'KARAHASANLI MAH 2019 SOK NO:9 KASIMBEY SİTESİ B BLOK D:4', 'ZEHRA TÜCAN', '05536069125', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1800, '59980111138', 'BEGÜM HANZADE', 'ÇETİN', '3-C', '2017-09-24', 'MUSTAFA ÇETİN', '05332141661', 'NİMET ÇETİN', '05443729256', 'İSTİKLAL MAH ZÜBEYDE HANIM CAD NO:66 GÜL APT K:2 D:6', 'ZEHRA TÜCAN', '05536069125', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1801, '42245165212', 'ERVA', 'SALDIROĞLU', '3-C', '2017-01-22', 'MUHAMMET SALDIROĞLU', '05423206900', 'ZEYNEP SALDIROĞLU', '05465936744', 'İNCİLİPINAR MAH 3377 SOK NO:10', 'ZEHRA TÜCAN', '05536069125', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1802, '28957479618', 'ŞÜHEDA', 'ÇAKIR', '3-C', '2017-05-05', 'MÜCAHİT ÇAKIR', '05468434130', 'MEDİHA ÇAKIR', '05530252228', 'ADALET MAH 10016 SOK NO:1 B BLOK K:1 D:1 KARAKOÇ SİTESİ', 'ZEHRA TÜCAN', '05536069125', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1803, '28774486646', 'LİNA', 'DEMİRKAN', '3-B', NULL, 'CÜNEYT DEMİRKAN', '05322919421', 'HALİME DEMİRKAN', '05417369009', '', 'RUKİYE BERKMAN', '05368302701', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1804, '29110472736', 'KUZEY', 'AKÇAGÜL', '3-B', NULL, 'AYKUT AKÇAGÜL', '05326036945', 'RAFİYE AKÇAGÜL', '05436036945', '', 'RUKİYE BERKMAN', '05368302701', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1805, '29035477434', 'SÜMEYYE RANA', 'ÖNER', '3-B', '2017-06-13', 'MUHAMMET ÖNER', '05345469883', 'NERİMAN ÖNER', '05438441399', '', 'RUKİYE BERKMAN', '05368302701', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1806, '28927478412', 'ELİF MİNA', 'SALAR', '3-B', '2017-04-06', 'BURAK SALAR', '05074349968', 'VİLDAN SALAR', '05532502599', '', 'RUKİYE BERKMAN', '05368302701', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1807, '18626213032', 'AZRA BEREN', 'KELEŞ', '3-B', '2017-01-15', 'KASIM MURAT KELEŞ', '05067181158', 'FADİME KURU', '05544202897', '', 'RUKİYE BERKMAN', '05368302701', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1808, '28759487088', 'HÜMEYRA', 'ELİŞ', '3-B', '2016-11-28', 'HAMZA ELİŞ', '05076552484', 'MÜŞERREF FATMA ELİŞ', '05534186151', 'YENİŞAFAK MAH 1091 SOK AZKALE APT NO:18', 'RUKİYE BERKMAN', '05368302701', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1809, '55933225600', 'HAFSA AMİNE', 'POLAT', '3-B', '2017-05-30', 'MURAT POLAT', '05073099889', 'AYŞE POLAT', '05382923399', '', 'RUKİYE BERKMAN', '05368302701', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1810, '28576492890', 'MEHMET FIRAT', 'BALKAYA', '3-B', '2016-07-23', 'OSMAN BALKAYA', '05324315329', 'HATİCE BALKAYA', '05057234928', 'YENİŞAFAK MAH. 1020 SOK. NOP:6 D:8', 'RUKİYE BERKMAN', '05368302701', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1811, '42797146820', 'ÖMER TARIK', 'ÇAKMAK', '3-B', '2017-01-10', 'ERDEN ÇAKMAK', '05366790785', 'MÜCAHİDE ÇAKMAK', '05062566226', 'ŞEMİKLER MAH. 3122 SOKAK ALTINEVLER SİTESİ D BLOK NO:28', 'RUKİYE BERKMAN', '05368302701', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1812, '28696487134', 'ERVA', 'GÜVEN', '3-B', '2016-10-07', 'ÖMER GÜVEN', '05412432076', 'EBRU GÜVEN', '05412432276', 'ŞEMİKLER MAH 3131 SOK DEDA LAVİDA SİT A BLOK K:11 NO:25', 'RUKİYE BERKMAN', '05368302701', NULL, 1, 1, '2025-10-23 22:15:09', '2025-10-23 22:15:09'),
(1813, '28870461950', 'ZÜMRÜT ERVA', 'AKYÜZ', '3-B', '2017-07-25', 'UMUT AKYÜZ', '05365950007', 'ÖZGE AKYÜZ', '05536153737', '', 'RUKİYE BERKMAN', '05368302701', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1814, '28672487790', 'TUĞRUL ŞAMİL', 'IŞIK', '3-B', '2016-09-22', 'MÜRSELİN IŞIK', '05383398618', 'MEDİNE IŞIK', '05378707510', 'TOPRAKLIK MAH HALK CAD NO:10 D:7', 'RUKİYE BERKMAN', '05368302701', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1815, '29020478170', 'AHSEN İKRA', 'ÖZDEMİR', '3-B', '2017-06-24', 'BAHTİYAR ÖZDEMİR', '05358685351', 'AYŞE ÖZDEMİR', '05545483748', 'ŞEMİKLER MAH 3028 SOK NO:48 D:5', 'RUKİYE BERKMAN', '05368302701', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1816, '28687488584', 'YUSUF EYMEN', 'UTANGAÇ', '3-B', '2016-10-05', 'HÜSEYİN UTANGAÇ', '05331990980', 'MÜLKİYE UTANGAÇ', '05337631660', 'SİNPAŞ EVLERİ', 'RUKİYE BERKMAN', '05368302701', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1817, '42440158750', 'MUHAMMED YUSUF', 'ÖZTÜRK', '3-B', '2017-01-20', 'HAMZA ÖZTÜRK', '05354372847', 'AYŞE ÖZTÜRK', '05052782858', 'İNCİRLİPINAR MAH.TOKAT CAD.ÖZKAN APT.NO:19/9', 'RUKİYE BERKMAN', '05368302701', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1818, '29056474412', 'ERVA', 'MERCAN', '3-B', '2017-07-18', 'MEHMET MERCAN', '05359734206', 'HATİCE MERCAN', '05546891552', 'SELÇUKBEY MH. 745 SK. UZUNON APART. 13 A1 BLK. K:2 D:9', 'RUKİYE BERKMAN', '05368302701', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1819, '41354194922', 'DOĞU ARAS', 'KAYA', '3-B', '2017-02-17', 'MAHMUT KAYA', '05321530879', 'ELİF KAYA', '05413073075', 'MERKEZEFENDİ MAH 424 SOK NO:64 K:3 D:6', 'RUKİYE BERKMAN', '05368302701', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1820, '46253031644', 'PAKİZE DURU', 'SAĞLIK', '3-B', '2016-10-15', 'ERGÜN SAĞLIK', '05074893689', 'FİLİZ SAĞLIK', '05335735352', 'KINIKLI MAH 6081 SOK NO:6', 'RUKİYE BERKMAN', '05368302701', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1821, '39653251644', 'ZEYNEP', 'BOYACI', '3-B', '2017-03-30', 'FATİH ALİ BOYACI', '05358157654', 'YÜKSEL BOYACI', '05061462762', 'KARAHASANLI MAH.2052 SK. NO:5 D:2', 'RUKİYE BERKMAN', '05368302701', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1822, '29050477464', 'YUSUF SELİM', 'KARABAY', '3-B', '2017-07-12', 'SÜREYYA KARABAY', '05425061949', 'HACER KARABAY', '05558128608', 'ÇAKMAK MAH. 186 SOK. NO:1', 'RUKİYE BERKMAN', '05368302701', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1823, '29002476156', 'HAYRİYE SELİN', 'YILMAZ', '3-B', '2017-06-09', 'ERSOY YILMAZ', '05374001242', 'SEMRA YILMAZ', '05362408148', 'ADALET MAH ALPARSLAN CAD NO:17 D:5', 'RUKİYE BERKMAN', '05368302701', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1824, '44984073964', 'MUSTAFA ALİ', 'GENCER', '3-A', NULL, 'İBRAHİM GENCER', '05543497559', 'MELTEM GENCER', '05077812509', 'YENİŞAFAK MAH.1037 SOK. N:6 YAŞAMKONAKLARI SİTESİ D BLOK D:1', 'GÜLŞAH KILINÇ', '05349596078', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1825, '28678487190', 'ZEYNEP ERVA', 'KANDEMİR', '3-A', '2016-09-18', 'MUSTAFA KANDEMİR', '05363556986', 'EMİNE KANDEMİR', '05377990463', '', 'GÜLŞAH KILINÇ', '05349596078', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1826, '28771650118', 'ÇAĞRI', 'ÖZEV', '3-A', '2017-07-01', 'HÜSEYİN ÖZEV', '05533131484', 'SEHER ÖZEV', '05544344216', '', 'GÜLŞAH KILINÇ', '05349596078', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1827, '28879482840', 'MUHAMMET HASAN', 'AVCIK', '3-A', '2017-02-11', 'SEDAT AVCIK', '05050372004', 'VİLDAN AVCIK', '05050382004', 'YENİŞAFAK MH. 1017 SK. NO:13 UZUNKENT SİT. A3 BLK. K:1 D:6', 'GÜLŞAH KILINÇ', '05349596078', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1828, '29143473700', 'ÖMER ASAF', 'ÇALHAN', '3-A', '2017-09-18', 'AYDIN ÇALHAN', '05337763270', 'ZÜBEYDE MELAHAT ÇALHAN', '05532265733', 'ŞİRİNKÖY MAH. KÖYALTI SOK. NO:12 D:2', 'GÜLŞAH KILINÇ', '05349596078', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1829, '22289090800', 'ÖMER HALİS', 'GÜLEÇ', '3-A', '2016-12-02', 'HİKMET GÜLEÇ', '05071915854', 'BÜŞRA GÜLEÇ', '05418598629', 'ŞEMİKLER MAH 3102 SOK NO:74 K:2 D:8', 'GÜLŞAH KILINÇ', '05349596078', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1830, '28663487914', 'MEVLÜT EYMEN', 'YAŞAR', '3-A', '2016-09-16', 'HASAN YAŞAR', '05368225357', 'ELİF YAŞAR', '05454431582', 'ŞEMİKLER MAH 3053 SOK NO:9 K:3', 'GÜLŞAH KILINÇ', '05349596078', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1831, '28945479200', 'MEHMET ARAS', 'TURKAT', '3-A', '2017-04-25', 'HALİL TURKAT', '05418122489', 'HURİYE TURKAT', '05536437152', 'YENİŞAFAK MAH 1183 SOK NO:2 K:3 D:6', 'GÜLŞAH KILINÇ', '05349596078', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1832, '41876177540', 'UMUT', 'AKBULUT', '3-A', '2017-02-01', 'İBRAHİM AKBULUT', '05532954052', 'CENNET AKBULUT', '05549909552', 'KARAHASANLI MAH AKÇA CAD', 'GÜLŞAH KILINÇ', '05349596078', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1833, '40441076264', 'ERVA', 'EKER', '3-A', '2017-10-19', 'GÜLSÜM EKER', '05079613173', 'YASİR EKER', '05434585135', 'PINARKENT MAH 165 SOK DEDAKENT SİTESİ E BLOK K:3 D:13', 'GÜLŞAH KILINÇ', '05349596078', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1834, '41435192212', 'MUHAMMED BURAK', 'KÖLEOĞLU', '3-A', '2017-02-14', 'KADİR KÖLEOĞLU', '05427122035', '', '', 'KUŞPINAR MAH KIBRIS ŞEHİTLER CAD NO :12 D:1', 'GÜLŞAH KILINÇ', '05349596078', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1835, '11735033058', 'AHMET ARDA', 'ÖZTÜRK', '3-A', '2017-08-09', 'HAKAN ÖZTÜRK', '05062838470', 'FERİDE ÖZTÜRK', '05426376379', 'KUŞPINAR MAH 1309 SOK NO:10 D:3', 'GÜLŞAH KILINÇ', '05349596078', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1836, '42814006724', 'METEHAN', 'TOPAL', '3-A', NULL, 'İBRAHİM TOPAL', '05313979895', 'BÜŞRA TOPAL', '05078814719', 'GÜLTEPE MAH HÜSEYİN ÇOKAL CAD. KULE APT NO 3 D:33', 'GÜLŞAH KILINÇ', '05349596078', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1837, '29077474224', 'MİRAÇ KEMAL', 'KARTA', '3-A', '2017-07-28', 'SAİD KARTA', '05365993940', 'İREM KARTA', '05432020726', 'ÇAKMAK MAH 5611 SOK NO:2 B BLOK D:1 SELVİ VİSTA KONUTLARI', 'GÜLŞAH KILINÇ', '05349596078', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1838, '28804484604', 'OĞUZ SELİM', 'HANKULU', '3-A', '2017-01-01', 'VELİ HANKULU', '05072475523', 'EMİNE HANKULU', '05316309823', 'ÇAKMAK MAH 173 SOK NO:2', 'GÜLŞAH KILINÇ', '05349596078', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1839, '44915076286', 'ATLAS EMİR', 'AKILLI', '3-A', '2016-11-15', 'ERDİNÇ AKILLI', '05077133196', 'MELTEM YILDIRIM AKILLI', '05074425626', 'AKHAN MAH 186 SOK NO:5 D:3', 'GÜLŞAH KILINÇ', '05349596078', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1840, '28342499832', 'SİNEM', 'NAZ', '2-C', NULL, 'MUSTAFA NAZ', '05427280860', 'AYSEL NAZ', '05417280860', 'GÜMÜŞÇAY MAH.ERBAKIR CAD.NO:13 D:9', 'HACER BAYKARA', '05452019236', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1841, '29248469318', 'ZEHRA', 'TOSUN', '2-C', NULL, 'MAHMUT TOSUN', '05530068813', 'BAHAR TOSUN', '05387921851', '', 'HACER BAYKARA', '05452019236', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1842, '13795964464', 'MUHSİN EREN', 'ARABACI', '2-C', '2018-02-07', 'YASİN ARABACI', '05056973610', 'ŞÜKRAN ARABACI', '05055810232', 'FESLEĞEN MAH 1004 SOK NO:12 K:2 D:5', 'HACER BAYKARA', '05452019236', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1843, '41242313718', 'TUĞRA', 'ÖZDEMİR', '2-C', '2018-02-25', 'OSMAN ÖZDEMİR', '05337136129', 'HACER ÖZDEMİR', '05397740290', 'SÜMER MAH SÜMERPARK EVLERİ A6 BLOK K:3 D:17', 'HACER BAYKARA', '05452019236', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1844, '39455258360', 'YÜSRA', 'TANRIÖĞEN', '2-C', '2018-09-18', 'CİHAN TANRIÖĞEN', '05325737470', 'SÜMEYRA TANRIÖĞEN', '05344687171', 'YENİŞAFAK MAH 1017 SOK NO:13 UZUNKENT SİTESİ A5 BLOK D:9', 'HACER BAYKARA', '05452019236', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1845, '43433125858', 'ÖMER FARUK', 'YAĞDI', '2-C', '2018-05-07', 'MİTHAT DENİZ YAĞDI', '05367111988', 'RABİA YAĞDI', '05320840982', 'ŞEMİKLER MAH 4868 SOK NO:18/A A BLOK NO:26', 'HACER BAYKARA', '05452019236', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1846, '10476227054', 'REYHAN DİLA', 'KERMAN', '2-C', '2018-07-13', 'MUHAMMED MELİH KERMAN', '05425487997', 'BEYZA KERMAN', '05397435607', 'ŞEMİKLER MAH 3003 SOK ATLANTİS SİTESİ E BLOK D:10', 'HACER BAYKARA', '05452019236', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1847, '29422464856', 'GÜLCE', 'ÖZDAL', '2-C', '2018-11-27', 'RECEP ÖZDAL', '05369945691', 'MEDİNE ÖZDAL', '05369540419', 'SELÇUKBEY MAH 657 SOK NO:2 PRİME REZİDANS B BLOK K:4 D:15', 'HACER BAYKARA', '05452019236', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1848, '13240989028', 'ŞÜKRÜ KEREM', 'ALIŞAN', '2-C', '2018-05-14', 'MURAT ALIŞAN', '05462015160', 'YASEMİN ALIŞAN', '05349227810', 'KARAHASANLI MAH SADE YAŞAM KONUTLARI NO:10E', 'HACER BAYKARA', '05452019236', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1849, '28969479880', 'ZEYNEP NUR', 'DEMİR', '2-C', NULL, 'HAKAN DEMİR', '05333606499', 'FATMA DEMİR', '05558796499', 'MERKEZEFENDİ MH. 450 SK.NO:10 YAVRU APARTMAN KAT:2', 'HACER BAYKARA', '05452019236', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1850, '19744766004', 'AMİNE HAFSA', 'BAYSAL', '2-C', '2017-08-01', 'VEDAT BAYSAL', '05325678374', 'ÇİĞDEM', '05496266265', 'CUMHURİYET MH. 3394 SK. NO:31', 'HACER BAYKARA', '05452019236', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1851, '28162506000', 'MUHAMMED KEREM', 'KANDEMİR', '2-B', '2018-04-08', 'MUSTAFA KANDEMİR', '05363556986', 'EMİNE KANDEMİR', '05377990463', '', 'KADİR AŞIK', '05069641420', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1852, '29338466012', 'ÜMMÜ GÜLSÜM', 'ORUCHAN', '2-B', '2018-02-14', 'MEHMET ALİ ORUCHAN', '05443774765', 'EMİNE ORUCHAN', '05445836371', '', 'KADİR AŞIK', '05069641420', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1853, '28252503636', 'KADİR ERDEM', 'DEĞİRMEN', '2-B', '2018-07-02', 'CELAL DEĞİRMEN', '05322951042', 'ISMAHAN DEĞİRMEN', '05364802222', '', 'KADİR AŞIK', '05069641420', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1854, '15559905638', 'HATİCE REYYAN', 'BAYRAM', '2-B', '2017-09-25', 'ERKAN BAYRAM', '05319742552', 'KADRİYE BAYRAM', '05528955520', '', 'KADİR AŞIK', '05069641420', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1855, '29230471312', 'HASAN', 'ARSLAN', '2-B', '2017-11-24', 'ALİ ARSLAN', '05362869600', 'GÜLSEREN ARSLAN', '05375084455', '', 'KADİR AŞIK', '05069641420', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1856, '28195504486', 'EYMEN BAKİ', 'KARA', '2-B', '2018-05-14', 'KENAN KARA', '05367148998', 'MELEK KARA', '05073814961', '', 'KADİR AŞIK', '05069641420', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1857, '45812046594', 'HASAN HÜSEYİN', 'BAYSAL', '2-B', '2018-02-26', 'MÜJDAT BAYSAL', '05067276003', 'BÜŞRA BAYSAL', '05496266263', 'CUMHURİYET MAH 3394 SOK NO:29', 'KADİR AŞIK', '05069641420', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1858, '45215066458', 'EYLÜL SU', 'BİRGİ', '2-B', '2018-03-20', 'MUHARREM BİRGİ', '05548919123', 'HATİCE BİRGİ', '05545440565', 'TOPRAKLIK MAH TURAN GÜNEŞ CAD NO:25', 'KADİR AŞIK', '05069641420', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1859, '29239468788', 'ZEHRA', 'BAYAR', '2-B', NULL, 'YÜCEL BAYAR', '05065063398', 'SANİYE BAYAR', '05054095445', '', 'KADİR AŞIK', '05069641420', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1860, '21403734576', 'TURGUT KAĞAN', 'ÇOĞAN', '2-B', '2018-07-09', 'HASAN ÇOĞAN', '05010735529', 'AYFER ÇOĞAN', '05374576620', 'ŞEMİKLER MAH 3066 SOK NO:30 K:3 D:12', 'KADİR AŞIK', '05069641420', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1861, '34567272006', 'AKİF EYMEN', 'YILDIZ', '2-B', '2018-01-31', 'ERKAN YILDIZ', '05076962981', 'BETÜL YILDIZ', '05079276474', 'PELİTLİBAĞ MAH İSTİKLAL CAD AYDIN APT NO:49 K:5/5', 'KADİR AŞIK', '05069641420', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1862, '29353466042', 'ELİF EYLÜL', 'SAVRAN', '2-B', '2018-09-26', 'AYŞE SAVRAN', '05393976102', 'YUNUS SAVRAN', '05412826827', 'KARAHASANLI MAH 2066 SOK NO:1 D:1 ORMAN FİDAN YANI', 'KADİR AŞIK', '05069641420', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1863, '44009106622', 'AHMET AKİF', 'GÖNCÜOĞLU', '2-B', '2018-04-20', 'GÖKHAN GÖNCÜOĞLU', '05311049967', 'ELİF GÖNCÜOĞLU', '05064102123', 'HALLAÇLAR MAH 3008/2 SOK NO:8 D:9', 'KADİR AŞIK', '05069641420', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1864, '38783280784', 'LİNA', 'ÖZTÜRK', '2-B', NULL, 'MEHMET ÖZTÜRK', '05384125697', 'KADRİYE ÖZTÜRK', '05532767745', 'AKTEPE MAH 2390 SOK 5C1-5 BLOK D:26', 'KADİR AŞIK', '05069641420', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1865, '29194472806', 'ÖMER ASAF', 'AYDOĞMUŞ', '2-B', '2017-10-25', 'DOĞAN AYDOĞMUŞ', '05326724464', 'ARZU AYDOĞMUŞ', '05306640771', 'SELÇUKBEY MH MEHMET AVCI CAD NO:23 SERHAN SİTESİ A7 BLOK K:1', 'KADİR AŞIK', '05069641420', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1866, '41462191424', 'ŞEYMA', 'KARAKUŞ', '2-B', '2018-08-14', 'ÜMİT KARAKUŞ', '05493893898', 'KÜBRA KARAKUŞ', '05423255664', 'KARŞIYAKA MAH. 2439/3 SOK. NO:17', 'KADİR AŞIK', '05069641420', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1867, '26083776830', 'ENES', 'CALP', '2-B', '2017-11-09', 'SUAT CALP', '05337700849', 'TUĞBA AKIN CALP', '05545747679', 'HALLAÇLAR MAH 3001 SOK NO:4', 'KADİR AŞIK', '05069641420', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1868, '28631621784', 'MEHMET ASAF', 'ÖZDİN', '2-B', '2018-02-06', 'ALİ ÖZDİN', '05302805065', 'AHUNUR ÖZDİN', '05544542721', 'GERZELE MAH M KEMAL AYKURT CAD NO:30 A1 BLOK D:4', 'KADİR AŞIK', '05069641420', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1869, '41465191438', 'TUĞÇE DEVA', 'EGE', '2-B', '2018-06-20', 'TOLGA EGE', '05398147260', 'TUĞBA EGE', '05383397608', '1200 EVLER MAH 2012/1 SOK MANOLYA APT NO:17 D:6', 'KADİR AŞIK', '05069641420', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1870, '28192505776', 'AHMET', 'IŞIK', '2-B', '2018-05-14', 'SAMET IŞIK', '05074286820', 'FATMA ÖZLEM IŞIK', '05315908134', 'ÇAKMAK MAH 110 SOK  NO:21 DENİZKENT SİTESİ A BLOK K:3 D:13', 'KADİR AŞIK', '05069641420', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1871, '28330499356', 'ALPEREN İSLAM', 'ÜLKER', '2-A', '2018-08-27', 'POLAT ÜLKER', '05445501418', 'ÇİĞDEM', '05468700112', 'TOPRAKLIK MAH.675 SK. NO:8 D:4 ÖZEL APT.', 'ELİF DURMAZ', '05530090074', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1872, '28150506210', 'İPEK', 'KARADEMİR', '2-A', '2018-04-10', 'HİLMİ KARADEMİR', '05357436210', 'BÜŞRA KARADEMİR', '05314395290', '', 'ELİF DURMAZ', '05530090074', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1873, '45917043092', 'ELİF EYLÜL', 'ER', '2-A', '2018-03-01', 'YUNUS EMRE ER', '', 'EBRU ER', '05337960962', '', 'ELİF DURMAZ', '05530090074', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1874, '28342498224', 'EMİNE ERVA', 'UZUN', '2-A', '2018-09-01', 'ZAFER UZUN', '05325628925', 'ARİFE UZUN', '05418105243', 'YENİŞAFAK MAH 1183 SOK NO:2 D:1 AYDEMİR APT', 'ELİF DURMAZ', '05530090074', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1875, '29362465906', 'EFLA NUR', 'ESKİN', '2-A', '2018-10-09', 'RAMAZAN ESKİN', '05055974225', 'KÜBRA ESKİN', '05556865122', 'MERKEZEFENDİ MH. 1700/6 SK. NO:8 ÇINAR APT. K:1', 'ELİF DURMAZ', '05530090074', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1876, '13369978590', 'YUSUF MİRZA', 'SARI', '2-A', NULL, 'ÖMER', '05359444962', 'SÜMEYYA SARI', '05057055753', 'KERVANSARAY MH. ZARRAFLAR SİT. 3101 SK. D BLK. K:2 BAĞBAŞI', 'ELİF DURMAZ', '05530090074', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1877, '38501096574', 'İPEK', 'ACAR', '2-A', '2018-10-12', 'AZİZ ACAR', '05425932335', 'SELEN ACAR', '05436114716', 'YENİŞAFAK MAH. 1017 SOK. NO:13 A5 BLOK D:8', 'ELİF DURMAZ', '05530090074', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1878, '29185470354', 'YÜSRA', 'ERTUNA', '2-A', '2017-10-16', 'MUSTAFA ERTUNA', '05337308170', 'NALAN ERTUNA', '05432082103', 'ŞEMİKLER MAH. 3006 SK. NO:2 GOKKUSAĞI KONUTLARI  A BLOK D:16', 'ELİF DURMAZ', '05530090074', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1879, '10264028924', 'BİLGE', 'ÇİĞDEM', '2-A', '2018-04-05', 'HALİT ÇİĞDEM', '05547549773', 'EMİNE ÇİĞDEM', '05066065903', 'YENİŞAFAK MAH 1055 SOK ÇIRALI MODERN SİTESİ F BLOK D:9', 'ELİF DURMAZ', '05530090074', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1880, '32935153506', 'BEREN', 'ÇİÇEK', '2-A', '2018-05-24', 'MEHMET NURİ ÇİÇEK', '05063587976', 'ESRA ÇİÇEK', '05072873528', 'ŞEMİKLER MAH 3007 SOK NO:10 K:2 D:5', 'ELİF DURMAZ', '05530090074', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1881, '28366499276', 'EMİNE HİFA', 'KAYA', '2-A', '2018-09-25', 'MAHMUT KAYA', '05321530879', 'ELİF KAYA', '05413073075', 'MERKEZEFENDİ MAH 424 SOK NO:64 K:3 D:6', 'ELİF DURMAZ', '05530090074', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1882, '43013139824', 'MUSTAFA ALİ', 'KAYIKCI', '2-A', '2018-04-25', 'ERHAN KAYIKCI', '05415039290', 'KÜBRA KAYIKCI', '05532171419', 'KERVANSARAY MAH. 3077 SOK. NO:8', 'ELİF DURMAZ', '05530090074', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1883, '61729339796', 'AYŞE NUR', 'REYHAN', '2-A', '2018-07-17', 'UĞUR REYHAN', '05556534223', 'VESİLE REYHAN', '05365542595', 'KERVANSARAY MAH 3006 SOK KORUKENT SİTESİ NO:24', 'ELİF DURMAZ', '05530090074', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1884, '44768081390', 'ÇAĞLAR EMİR', 'ÖZDEMİR', '2-A', '2018-03-31', 'ÇAĞLAR ÖZDEMİR', '05078772665', 'ŞEYMA ÖZDEMİR', '05378490242', 'KARAHASANLI 2246 SOK NO:11 K:2 D:6 ÇOBANLAR KONUTLARI', 'ELİF DURMAZ', '05530090074', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1885, '18439809552', 'AYŞE SİMAY', 'ACAR', '2-A', '2017-10-31', 'YUSUF ACAR', '05519575793', 'ÜMRAN ACAR', '05522077789', 'İNCİLİPINAR MAH 3378 SOK NO:33/1', 'ELİF DURMAZ', '05530090074', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1886, '19906760772', 'ZEYNEP BEYZA', 'ÇİFTÇİ', '2-A', '2017-12-07', 'HULUSİ ÇİFTÇİ', '05364931124', 'NURCAN', '05325869452', 'GERZELE MAH. 504 SOK. NO:2 TORUNLAR APT.', 'ELİF DURMAZ', '05530090074', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1887, '22702667596', 'BERİL NİSA', 'DURMUŞ', '2-A', '2017-10-27', 'YUSUF DURMUŞ', '05395012125', 'CANAN DURMUŞ', '05533960713', 'GERZELE MAH 535 SOK AKİS PARK KONUTLARI A BLOK D:12', 'ELİF DURMAZ', '05530090074', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1888, '44012106558', 'HATİCE SERRA', 'YILMAZ', '2-A', '2018-04-22', 'ABDULLAH YILMAZ', '05367963141', 'YASEMİN YILMAZ', '05061367343', 'DELİKTAŞ MAH 1961 SOK NO:15 K:4', 'ELİF DURMAZ', '05530090074', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1889, '28156506924', 'MURAT ALP', 'KOCAİRİ', '2-A', '2018-04-16', 'AHMET KOCAİRİ', '05314216868', 'BÜŞRA KOCAİRİ', '05532137878', 'AKÇEŞME MAH.ERTUĞRULGAZİ CAD.NO:54', 'ELİF DURMAZ', '05530090074', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1890, '28153505920', 'ÖMER', 'GÖR', '2-A', '2018-04-12', 'AZİZ GÖR', '05388416525', 'YASEMİN GÖR', '05071247924', 'AKÇEŞME MAH. ERTUĞRUL GAZİ CD. N:89 K:3', 'ELİF DURMAZ', '05530090074', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1891, '29731454296', 'JİYA', 'NAZ', '1-C', NULL, 'MUSTAFA NAZ', '05427280860', 'AYSEL NAZ', '05417280860', 'GÜMÜŞÇAY MAH. ERBAKIR CAD.NO:13 D:9', 'FİLİZ SAĞLIK', '05335735352', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1892, '29665455710', 'SALİH', 'DEMİRKAN', '1.SINIF', NULL, 'CÜNEYT DEMİRKAN', '05322919421', 'HALİME DEMİRKAN', '05417369009', '', 'FİLİZ SAĞLIK', '05335735352', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1893, '36557354952', 'BEYAZIT ÇINAR', 'ÖZCAN', '1.SINIF', NULL, 'SERKAN ÖZCAN', '05535713399', 'ÖZLEM ÖZCAN', '05534431989', 'ATALAR MAH. EMEK CAD. NO:62 AYGÜL APT. K:7 D:7', 'FİLİZ SAĞLIK', '05335735352', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1894, '36359361578', 'ZEYNEP BETÜL', 'TÜRK', '1.SINIF', '2018-12-24', 'RAMAZAN TÜRK', '05423637381', 'SEVAL TÜRK', '05464060153', 'BAĞBAŞI MAH ŞEHİT HÜSEYİN ÇELİK CAD 1029 SOK YEŞİLVADİ SİT', 'FİLİZ SAĞLIK', '05335735352', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1895, '34298430294', 'YUSUF EMRE', 'KIRBOĞA', '1.SINIF', '2019-02-08', 'ADEM KIRBOĞA', '05373959247', 'ESRA KIRBOĞA', '05537915104', 'BAHÇELİEVLER 4000 SK. NO:21', 'FİLİZ SAĞLIK', '05335735352', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1896, '29575457262', 'YUSUF EGE', 'ÖZER', '1.SINIF', '2019-04-12', 'YUSUF ÖZER', '05325576728', 'RUKİYE', '05327357663', 'DEĞİRMENÖNÜ MH. 1375 SK. ÇAYKENT SİT. C BLK. NO:12 D:5', 'FİLİZ SAĞLIK', '05335735352', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1897, '25811713154', 'SAHRA', 'MUCAN', '1.SINIF', '2019-09-13', 'MUSTAFA MUCAN', '05373211104', 'DİLEK MUCAN', '05435329938', 'KARAHASANLI MAH 2247/1 SOK NO:23 K:4 D:13', 'FİLİZ SAĞLIK', '05335735352', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1898, '27068671232', 'NURSİMA', 'KÜREŞ', '1.SINIF', '2019-08-18', 'ABDULLAH KÜREŞ', '05065080848', 'RABİA KÜREŞ', '05302839440', 'SELÇUKBEY MAH 743 SOK NO:5 D BLOK SUEDA SİTESİ K:2 D:6', 'FİLİZ SAĞLIK', '05335735352', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1899, '29551458740', 'NİSA DURU', 'FENERCİOĞLU', '1.SINIF', '2019-03-20', 'CEM FENERCİOĞLU', '05333446336', 'BEYZA FENERCİOĞLU', '05393759718', 'YENİŞAFAK MAH 1020 SOK NO:16 F-5', 'FİLİZ SAĞLIK', '05335735352', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1900, '28865611322', 'MÜCTEBA ENES', 'UZKAYA', '1.SINIF', '2019-07-03', 'YAKUP UZKAYA', '05462017673', 'ESRA ŞAHİN UZKAYA', '05316969030', 'BAĞBAŞI MAH 1005 SOK NO:10', 'FİLİZ SAĞLIK', '05335735352', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1901, '26167553364', 'MELİSA', 'DURU', '1.SINIF', '2019-08-07', 'MAHMUT DURU', '05549471526', 'MUTEBER ATEŞ DURU', '05392851728', 'ZEYTİNKÖY MAH 5011 SOK NO:7 D:7 NENE HATUN SİTESİ', 'FİLİZ SAĞLIK', '05335735352', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1902, '29488461844', 'MELİKE', 'ÇİFTÇİ', '1.SINIF', '2019-01-23', 'CELİL ÇİFTÇİ', '05448409508', 'NAZMİYE NUR ÇİFTÇİ', '05446630983', 'GÜLTEPE MAH 4838 SOK UZUNYAŞAM KONUTLARI 2 3/C NO:19', 'FİLİZ SAĞLIK', '05335735352', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1903, '34022439456', 'BİLGE ECE', 'SÖKEL', '1.SINIF', '2019-02-17', 'ARİF MÜCAHİT SÖKEL', '05455947004', 'ARİFE SÖKEL', '05434087354', '', 'FİLİZ SAĞLIK', '05335735352', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1904, '25343728756', 'BEREN', 'ÇAKIR', '1.SINIF', '2019-09-24', 'SELÇUK ÇAKIR', '05306977656', 'GÜLSEREN ÇAKIR', '05312368869', 'KARAHASANLI MAH 2093 SOK NO:6 PARKVADİ SİTESİ D BLOK D:4', 'FİLİZ SAĞLIK', '05335735352', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1905, '34568421250', 'AZRANUR SULTAN', 'MAK', '1.SINIF', '2019-02-03', 'AZİZ MAHMUT HÜDAİ MAK', '05069096381', 'MERYEM MAK', '05069096380', 'ZÜMRÜT MAH. 2018 SOK. NO:3 D:4', 'FİLİZ SAĞLIK', '05335735352', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1906, '36035372324', 'UTKU BENAN', 'ZAHRAN', '1.SINIF', '2018-12-24', 'ERKUT ZAHRAN', '05536026336', 'BANU ZAHRAN', '05054308096', 'KUŞPINAR MAH 1285 SOK NO:7 D:4', 'FİLİZ SAĞLIK', '05335735352', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1907, '29377465700', 'HANDE DERİN', 'HIZ', '1.SINIF', NULL, 'İDRİS HIZ', '05305703925', 'FATMA HIZ', '05333853925', 'KARAHASANLI MAH UMUT3 SİTESİ KARDELEN APT BLOK7 K:4 D:18', 'FİLİZ SAĞLIK', '05335735352', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1908, '36083370730', 'EMRE', 'ÇİFTÇİ', '1.SINIF', '2018-12-28', 'HASAN ÇİFTÇİ', '05316202830', 'MERVE ÇİFTÇİ', '05557033963', '', 'FİLİZ SAĞLIK', '05335735352', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1909, '29638456508', 'ELİF HAFSA', 'CABAR', '1.SINIF', '2019-05-29', 'MUHAMMED ALPEREN CABAR', '05552612078', 'FATMA CABAR', '05462032036', 'ADALET MAH 10023 SOK NO:14 BAŞAK APT K:1', 'FİLİZ SAĞLIK', '05335735352', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1910, '32564488000', 'DERİN', 'DOĞRU', '1.SINIF', '2019-03-30', 'YALÇIN DOĞRU', '05320121237', 'KADER ÇELİK DOĞRU', '05321004346', 'ŞEMİKLER MAH 3011 SOK NO:29/A ARYA GARDEN SİTESİ K:5 D:20', 'FİLİZ SAĞLIK', '05335735352', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1911, '29512462236', 'EYMEN', 'KAYA', '1.SINIF', NULL, '', '', 'SÜMEYRA KAYA', '05069287197', '', 'GÜLDEN GENCER', '05355515722', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1912, '29539460840', 'LİNA', 'GÜVEN', '1.SINIF', '2019-03-04', 'ÖMER GÜVEN', '05412432076', 'EBRU GÜVEN', '05412432276', 'ŞEMİKLER MAH 3131 SOK DEDA LAVİDA SİT A BLOK K:11 NO:25', 'GÜLDEN GENCER', '05355515722', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1913, '29413465442', 'YAVUZ SELİM', 'KARATOPÇU', '1.SINIF', '2018-11-14', 'ALİ KARATOPÇU', '05549280088', 'NAZLI KARATOPÇU', '05469280088', 'YENİŞAFAK MAH. 1017 SOK. UZUNKENT SİTESİ A1 BLOK', 'GÜLDEN GENCER', '05355515722', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1914, '26195700358', 'ZEYNEP', 'URKAY', '1.SINIF', '2019-08-14', 'MEHMET URKAY', '05331517070', 'FATMA URKAY', '05417842432', 'YUNUS EMRE MAH 6418 SOK NO:5 K:1 DESTAN APT', 'GÜLDEN GENCER', '05355515722', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1915, '38444292040', 'MELİS', 'ÖZDEL', '1.SINIF', '2018-10-25', 'SEZAİ ÖZDEL', '05062592996', 'NİHAL ÖZDEL', '05063870748', 'YENİŞAFAK MAH 1092 SOK NO:8 K:4 D:14 LADİN APT.', 'GÜLDEN GENCER', '05355515722', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1916, '29437462816', 'MELİH EMRE', 'DURMAZ', '1.SINIF', '2018-12-10', 'TAHSİN DURMAZ', '05326484072', 'AYGÜL DURMAZ', '05317764883', 'KAYAKÖY TOKİ 6019 SOK GA 32 BLOK K:5 D:22', 'GÜLDEN GENCER', '05355515722', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10');
INSERT INTO `vp_students` (`id`, `tc_no`, `first_name`, `last_name`, `class`, `birth_date`, `father_name`, `father_phone`, `mother_name`, `mother_phone`, `address`, `teacher_name`, `teacher_phone`, `notes`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(1917, '26330695846', 'KEREM', 'DEMİRKIRAN', '1.SINIF', '2019-08-28', 'OSMAN DEMİRKIRAN', '05436555751', 'SELMA DEMİRKIRAN', '05437884016', 'YENİŞAFAK MAH 1123 SOK NO:1 D:8', 'GÜLDEN GENCER', '05355515722', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1918, '29761451678', 'HÜMA', 'KESER', '1.SINIF', '2019-09-09', 'RAHMİ KESER', '05546662000', 'ŞULE KSER', '05071184278', 'H.EYÜPLÜ MAH 6012 SOK NO:1/I KAYAKÖY TOKİ 1.BÖLGE B10 BLOK', 'GÜLDEN GENCER', '05355515722', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1919, '28841612114', 'EMİR', 'HAZAR', '1.SINIF', '2019-07-06', 'MEHMET HAZAR', '05059184861', 'SERVER ÇELİK', '05059184860', 'YUNUS EMRE MAH 6456 SOK NO:4 K:2 ÇELİK APT', 'GÜLDEN GENCER', '05355515722', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1920, '28348499782', 'ELİF SARE', 'İNCE', '1.SINIF', '2018-09-10', 'AHMET İNCE', '05327779080', 'MERVE İNCE', '05552938868', 'ÇAKMAK MAH 5600 SOK AYA REZİDANS C BLOK K:4 NO:7', 'GÜLDEN GENCER', '05355515722', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1921, '29782451558', 'ECMEL VERA', 'SOLAK', '1.SINIF', '2019-09-29', 'NEDİM SOLAK', '05375147888', 'HATİCE SOLAK', '05056882096', 'KARAHASANLI MH. 2008 SK. NO:12 K:2 D:9', 'GÜLDEN GENCER', '05355515722', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1922, '29491459890', 'DEFNE', 'AKGÖZ', '1.SINIF', '2019-01-25', 'UFUK AKGÖZ', '05066775574', 'MERYEM AKGÖZ', '05077217024', 'GERZELE MAH 540 SOK NO:10/2 ÇELİK APT', 'GÜLDEN GENCER', '05355515722', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1923, '29680454446', 'CENGİZ ASAF', 'TOSUN', '1.SINIF', '2019-07-07', 'MAHMUT TOSUN', '05530068813', 'BAHAR TOSUN', '05387921851', '', 'GÜLDEN GENCER', '05355515722', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1924, '61246047730', 'BİLGE', 'OĞUL', '1.SINIF', '2019-08-23', 'ABDULLAH OĞUL', '05417955849', 'HALİME OĞUL', '05395741886', 'ŞEMİKLER MAH 3131 SOK NO:22/55 DEDALAVİDA', 'GÜLDEN GENCER', '05355515722', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1925, '31124536038', 'BETÜL', 'TARAKÇI', '1.SINIF', '2019-05-09', 'MUSTAFA TARAKÇI', '05466725768', 'ZEHRA TARAKÇI', '05425523947', 'SELÇUKBEY MAH 768 SOK NO:10 B14', 'GÜLDEN GENCER', '05355515722', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1926, '25790713824', 'AYAZ', 'ERİK', '1.SINIF', '2019-09-09', 'SERDAR ERİK', '05301121301', 'GAMZE ERİK', '05452917266', 'ZÜMRÜT MAH 2082 SOK NO:12 K:1 D:4', 'GÜLDEN GENCER', '05355515722', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1927, '29413462540', 'ARİF', 'SEYLAN', '1.SINIF', '2018-11-19', 'SELMAN SEYLAN', '05336446769', 'SEDA SEYLAN', '05327735130', 'BEREKETLİ MH. 10171 SK. NO:2', 'GÜLDEN GENCER', '05355515722', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1928, '29404462764', 'AMİNE YAĞMUR', 'GENÇ', '1.SINIF', '2018-11-03', 'GÜVENÇ GENÇ', '05050471186', 'ARİFE GENÇ', '05394431854', '', 'GÜLDEN GENCER', '05355515722', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1929, '31337528912', 'AFRA', 'KARAKUŞ', '1.SINIF', '2019-05-02', 'İDRİS KARAKUŞ', '05464004036', 'NURCAN KARAKUŞ', '05432683397', 'KARŞIYAKA MH. 2439 SK. NO:22/2', 'GÜLDEN GENCER', '05355515722', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1930, '26450698550', 'MERT', 'SİVRİ', '1.SINIF', '2019-08-09', 'FERHAT SİVRİ', '05336966918', 'ÜMMÜGÜLSÜM KAŞAL', '05300703816', '', 'GÜLDEN GENCER', '05355515722', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1931, '59146601596', 'TUĞSEM ALÇİN', 'KARAARSLAN', 'ANASINIF-E', NULL, 'CİHAN KARAARSLAN', '05307670923', 'DEMET KARAARSLAN', '05397018871', 'HACIEYÜPLÜ MAH.AKÇACD. 6012 SOK.B8 BLOK K:2 D:14 KAYAKÖY TOKİ', 'MERYEM DOĞAN', '05319412731', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1932, '67555321790', 'DURU REVAN', 'ÖZCAN', 'ANASINIF-E', NULL, 'SERKAN ÖZCAN', '05535713399', 'ÖZLEM ÖZCAN', '05534431989', 'ATALAR MAH. EMEK CAD. NO:62 K:7 D:7', 'MERYEM DOĞAN', '05319412731', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1933, '25736231290', 'AHMET TAHA', 'POLAT', 'ANASINIF-E', '2021-06-15', 'MURAT POLAT', '05073099889', 'AYŞE POLAT', '05382923399', '', 'MERYEM DOĞAN', '05319412731', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1934, NULL, 'SENA', 'SALAR', 'ANASINIF-E', NULL, 'BURAK SALAR', '05074349968', 'VİLDAN SALAR', '05532502599', '', 'MERYEM DOĞAN', '05319412731', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1935, '30961412826', 'GONCA', 'YANAR', 'ANASINIF-E', '2022-10-23', 'SELMAN YANAR', '05337320821', 'ÜMMÜHAN SEVDE YANAR', '05443107924', 'YENİŞAFAK MAH 1102 SOK NO:7 NERGİSPARK SİTESİ', 'MERYEM DOĞAN', '05319412731', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1936, '35113268436', 'METE', 'ULUTAŞ', 'ANASINIF-E', '2020-12-23', 'MUSTAFA ULUTAŞ', '05355440404', 'BUŞRA ULUTAŞ', '05059379674', 'ŞEMİKLER MAH 3131 SOK NO:22 B BLOK D:6', 'MERYEM DOĞAN', '05319412731', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1937, NULL, 'DEFNE', 'ÖZDEL', '30253436950', NULL, 'HÜSEYİN ÖZDEL', '05304009033', 'TUĞBA ÖZDEL', '05370414673', '', 'FATMA CEYLAN', '05432659829', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1938, NULL, 'ESLEM ERVA', 'COŞKUN', '74470091226', '2021-02-25', 'UĞUR COŞKUN', '05375084451', 'ELİF COŞKUN', '05368780760', 'ZEYTİNKÖY MAH 4028 SK NO 1 D 1', 'FATMA CEYLAN', '05432659829', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1939, NULL, 'ZEYNEP', 'YAĞMUR', '30334433564', '2021-02-18', 'MAHMUT SAMİ YAĞMUR', '05072615787', 'GÜLÇİN YAĞMUR', '05057046083', 'YENİŞAFAK MAH 1050 SOK NO:31 GOLDEN LİFE SİTESİ C BLOK K3 D9', 'FATMA CEYLAN', '05432659829', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1940, NULL, 'MERYEM SARE', 'İÇEN', '44692335590', '2021-02-01', 'UMUT İÇEN', '05413745717', 'AYŞEGÜL ÇELİK İÇEN', '05326607262', '', 'FATMA CEYLAN', '05432659829', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1941, NULL, 'BALAMİR', 'ÜNAL', '30616425164', '2021-11-18', 'CİHAN ÜNAL', '05072620406', 'BÜŞRA ÜNAL', '05332765361', '', 'FATMA CEYLAN', '05432659829', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1942, NULL, 'İPEK', 'KAHVECİ', '30646423222', '2021-12-17', 'ALPEREN KAHVECİ', '05345238732', 'HATİCE MERVE KAHVECİ', '05071862990', 'YENİŞAFAK MAH 1019 SOK NO:5 D:7', 'FATMA CEYLAN', '05432659829', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1943, NULL, 'ZEYNEP MEVA', 'BAŞAR', '30301434648', '2021-01-11', 'MEHMET BAŞAR', '05467409946', 'HAVVA BAŞAR', '05538438582', 'SELÇUKBEY MH. 557 SK. NO:3 AYIŞIĞI SİT. C BLK. K:5 D:9', 'FATMA CEYLAN', '05432659829', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1944, NULL, 'BATUHAN', 'ALIŞAN', '14308953654', '2021-02-01', 'MURAT ALIŞAN', '05462015160', 'YASEMİN ALIŞAN', '05349227810', 'KARAHASANLI MAH SADE YAŞAM KONUTLARI NO:10E', 'FATMA CEYLAN', '05432659829', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1945, NULL, 'MUHAMMED ENES', 'KAPLAN', '30274435536', '2020-12-18', 'İBRAHİM KAPLAN', '05077831452', 'CEMİLE KAPLAN', '05058023188', 'GÜLTEPE MAH. HÜSEYİN ÇOKAL CAD. NO:13 K.7 D:32', 'FATMA CEYLAN', '05432659829', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1946, '29764451514', 'EMRE', 'YAĞCI', 'ANASINIF-C', NULL, 'MEHMET YAĞCI', '05412358895', 'AYŞE YAĞCI', '05497192700', 'GÖVEÇLİK MAH.EKREM BAŞAR BULV.NO:13 D:2', 'ŞULE KESER', '05071184278', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1947, '12612153180', 'YİĞİT ALP', 'OKUL', 'ANASINIF-C', NULL, 'SEYHAN OKUL', '05464201301', 'GÜL HATİCE OKUL', '05454201301', '', 'ŞULE KESER', '05071184278', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1948, '29860448876', 'DURU', 'KIYBAR', 'ANASINIF-C', '2019-12-01', 'ALİ KIYBAR', '05074654646', 'DERYA KIYBAR', '05076589350', 'YENİŞAFAK MAH 1088 SOK NO:52', 'ŞULE KESER', '05071184278', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1949, '30175437448', 'FATMA BERRA', 'KOÇ', 'ANASINIF-C', '2020-09-09', 'MEHMET KOÇ', '05352074002', 'BÜŞRA HABİBE KOÇ', '', '', 'ŞULE KESER', '05071184278', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1950, '30025443742', 'LEYLA', 'YANAR', 'ANASINIF-C', '2020-05-12', 'SELMAN YANAR', '05337320821', 'ÜMMÜHAN SEVDE YANAR', '05443107924', 'YENİŞAFAK MAH 1102 SOK NO:7 NERGİSPARK SİTESİ', 'ŞULE KESER', '05071184278', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1951, '30136440258', 'KEREM', 'GÖNÜLLÜ', 'ANASINIF-C', '2020-07-24', 'MUSTAFA GÖNÜLLÜ', '05334339348', 'ÖZGÜL PEMBE GÖNÜLLÜ', '05389231100', 'SELÇUKBEY MAH 648 SOK EVORA B3 BLOK D:30', 'ŞULE KESER', '05071184278', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1952, '29986443724', 'KEREM UTKU', 'ERKOL', 'ANASINIF-C', '2020-04-04', 'İBRAHİM ERKOL', '05538660059', 'MERVE GÖKÇE ERKOL', '05424395236', 'ÇAKMAK MAH 175 SOK NO:3 D:6 KAYA APT', 'ŞULE KESER', '05071184278', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1953, '29767450538', 'MUSA KAĞAN', 'EROL', 'ANASINIF-C', '2019-09-17', 'SERDAR EROL', '05077735224', 'AYŞEGÜL EROL', '05318210725', 'AKKONAK MAH 1681 SOK NO:6', 'ŞULE KESER', '05071184278', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1954, '30193437796', 'BULUT', 'İMANCI', 'ANASINIF-C', '2020-09-29', 'UĞUR İMANCI', '05065665361', 'BETÜL ERGEN', '05535943929', 'SERVERGAZİ MAH 28 SOK DENİZKENT SİTESİ 29 D BLOK K:4 D:10', 'ŞULE KESER', '05071184278', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1955, '30079440186', 'YÜSRA SENA', 'SALUK', 'ANASINIF-C', '2020-06-25', 'YUNUS SEFA SALUK', '05547162515', 'GÜLİSTAN BÜŞRA SALUK', '05070474745', 'GÜMÜŞÇAY MAH GÜMÜŞLER BULV NO:177 K:3 D:3', 'ŞULE KESER', '05071184278', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1956, '17399993516', 'MEYRA', 'DERE', 'ANASINIF-C', '2020-04-30', 'SÜLEYMAN DERE', '05325653726', 'MEHLİKA DERE', '05412354842', 'DELİKTAŞ MAH.1946 SK. NO:29', 'ŞULE KESER', '05071184278', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1957, '18713949776', 'EMİN BERK', 'ÇORUM', 'ANASINIF-C', '2020-03-19', 'HASAN ÇORUM', '05383200068', 'AYŞEGÜL ÇORUM', '05533433896', 'ASMALIEVLER MAH YAVUZ SELİM CAD NO:6', 'ŞULE KESER', '05071184278', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1958, '20291897110', 'SÜLEYMAN EFE', 'ŞENOCAK', 'ANASINIF-B', NULL, 'SÜLEYMAN ŞENOCAK', '05432274425', 'HÜLYA ŞENOCAK', '05330274425', '', 'SEVGİ ÖZDEMİR', '05347603727', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1959, NULL, 'HAMZA SAİD', 'ŞAHAN', 'ANASINIF-B', NULL, 'ÇETİN ŞAHAN', '05366307080', 'FİLİZ ŞAHAN', '05396522026', '', 'SEVGİ ÖZDEMİR', '05347603727', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1960, '27769513572', 'ASEL', 'İBİLEME', 'ANASINIF-B', NULL, 'AKİF İBİLEME', '05072480677', 'AYŞE İBİLEME', '05412809619', 'ZÜMRÜT MAH. MEHMET GÖNÜL APT.1261 SOK. NO:21', 'SEVGİ ÖZDEMİR', '05347603727', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1961, '29917446046', 'TEVFİK', 'ERSOY', 'ANASINIF-B', '2020-01-13', 'AHMET ERSOY', '05304134616', 'GÜLİZAR ERSOY', '05331270021', '', 'SEVGİ ÖZDEMİR', '05347603727', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1962, '29851447550', 'HASAN HÜSEYİN', 'GÜDÜCÜ', 'ANASINIF-B', '2019-12-02', 'MUSA GÜDÜCÜ', '', 'GÜL GÜDÜCÜ', '05532916879', '', 'SEVGİ ÖZDEMİR', '05347603727', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1963, '38239861000', 'EGE', 'PEKER', 'ANASINIF-B', '2020-07-01', 'YUSUF PEKER', '', 'AYŞİN ÇETİNKAYA', '05074926073', '', 'SEVGİ ÖZDEMİR', '05347603727', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1964, '30163439256', 'AYLA', 'YANAR', 'ANASINIF-B', '2020-09-02', 'ESVET YANAR', '05335766689', 'DEFNE YANAR', '05064588833', 'YENİŞAFAK MAH 1106 SOK NO:10 ÇINAR APT', 'SEVGİ ÖZDEMİR', '05347603727', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1965, '29875446836', 'ASAF EMRE', 'IŞIK', 'ANASINIF-B', '2019-12-23', 'HASAN IŞIK', '05336528482', 'BETÜL IŞIK', '05374115162', 'ŞEMİKLER MAH 3137 SOK NO:2/B SEYİR KONUTLARI A BLOK', 'SEVGİ ÖZDEMİR', '05347603727', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1966, '30028442452', 'MİNA', 'KOVAN', 'ANASINIF-B', NULL, 'MEHMET KOVAN', '05326052910', 'EMEL KOVAN', '05365467484', 'SELÇUKBEY MAH 650 SOK NO:8 EVORA D6 BLOK K:3', 'SEVGİ ÖZDEMİR', '05347603727', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1967, '29923445682', 'HAYAT', 'TANRIKULU', 'ANASINIF-B', '2020-02-04', 'AHMET TANRIKULU', '05377443512', 'NEŞE TANRIKULU', '05395515526', 'GÜMÜŞÇAY MAH 4224 SOK NO:82', 'SEVGİ ÖZDEMİR', '05347603727', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1968, '30013444256', 'NİLAY', 'KABÜK', 'ANASINIF-B', '2020-05-02', 'MUHAMMET KABÜK', '05076926216', 'PINAR KABÜK', '05436926216', 'ÇAKMAK MAH 129 SOK NO:25 D:5 GÜL SİTESİ B BLOK', 'SEVGİ ÖZDEMİR', '05347603727', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1969, '18413959730', 'METEHAN', 'DENİZ', 'ANASINIF-B', '2020-04-01', 'ALİ DENİZ', '05530090313', 'FEYZA DENİZ', '05368730266', 'KARŞIYAKA MH. 2439/10 SK. NO:6(ANANE ADRESİ)', 'SEVGİ ÖZDEMİR', '05347603727', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1970, '22784814062', 'ÖMER FATİH', 'ÇİFTÇİ', 'ANASINIF-B', '2019-12-02', 'HULUSİ ÇİFTÇİ', '05364931124', 'NURCAN', '05325869452', 'GERZELE MAH. 504 SOK. NO:2 TORUNLAR APT.', 'SEVGİ ÖZDEMİR', '05347603727', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1971, '29893447310', 'ESMA TÜLAY', 'ERSOY', 'ANASINIF-B', '2020-01-09', 'DEDE ERSOY', '05334604627', 'ŞERİFE ERSOY', '05522574627', 'BAHÇELİEVLER MH. 3015 SK NO:2', 'SEVGİ ÖZDEMİR', '05347603727', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1972, '46535032536', 'SEYİT', 'DANIK', 'ANASINIF-A', '2020-06-10', '', '', 'AYŞE DANIK', '05398551374', '0539 855 13 74', 'EMİNE DİLARA TOPÇU', '05454564569', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1973, '47269383642', 'SEMİH ASAF', 'KIVILCIM', 'ANASINIF-A', '2020-01-15', 'SERDAR KIVILCIM', '05550613380', 'SENEM KIVILCIM', '05059887000', '0505 988 70 00', 'EMİNE DİLARA TOPÇU', '05454564569', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1974, '30076443388', 'ESLEM', 'YILDIZ', 'ANASINIF-A', '2020-06-29', 'TANJU YILDIZ', '05437469897', 'EMİNE YILDIZ', '05454549732', '0545 454 97 32', 'EMİNE DİLARA TOPÇU', '05454564569', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1975, NULL, 'TARIK HAMZA', 'GENCER', 'ANASINIF-A', NULL, 'YASİN GENCER', '05355586527', 'GÜLDEN GENCER', '05355515722', 'ŞEMİKLER MH. 3007 SK. NO:9 ÖZER-2 APT. K:3 D:15', 'EMİNE DİLARA TOPÇU', '05454564569', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1976, '99240922906', 'KARIM', 'SHAKROUF', 'ANASINIF-A', '2020-01-19', 'MOHAMAD FERAS SHAKROUF', '05417416063', 'NATALİİA SHAKROUF', '', 'SİNPAŞ KONUTLARI', 'EMİNE DİLARA TOPÇU', '05454564569', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1977, '30064441754', 'KUMSAL', 'BIYIK', 'ANASINIF-A', '2020-06-11', 'ERDOĞAN BIYIK', '', 'ŞÜKRAN BIYIK', '05452617925', 'SEVİNDİK MAH 2306 SOK NO:21', 'EMİNE DİLARA TOPÇU', '05454564569', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1978, '30121440690', 'İBRAHİM', 'AŞIK', 'ANASINIF-A', '2020-07-30', 'KADİR AŞIK', '05069641420', 'ÜLKÜ AŞIK', '05073203467', '', 'EMİNE DİLARA TOPÇU', '05454564569', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1979, '29941446616', 'EYMEN', 'ÜRKMEZ', 'ANASINIF-A', '2020-02-23', 'ERHAN ÜRKMEZ', '05354343730', 'GÖZDE ÜRKMEZ', '05364707384', '', 'EMİNE DİLARA TOPÇU', '05454564569', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1980, '29695455476', 'MEYRA', 'AKBULUT', 'ANASINIF-A', '2019-07-20', 'METİN AKBULUT', '05392153864', 'ŞEYDA AKBULUT', '05392153865', 'KARAHASANLI MAH 2020 SOK NO:10/B D:8 SADE YAŞAM DEMİRTEN', 'EMİNE DİLARA TOPÇU', '05454564569', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1981, '18596953624', 'KEREM', 'ÇALHAN', 'ANASINIF-A', '2020-03-28', 'İBRAHİM SAİT ÇALHAN', '05068495728', 'HİLAL ÇALHAN', '05547775190', 'GÜLTEPE MH. 4826 SK. NO:1 YEŞİLVADİ SİT. C. BLK. D:10', 'EMİNE DİLARA TOPÇU', '05454564569', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1982, '29875449356', 'MUSAB KAAN', 'AYCAN', 'ANASINIF-A', '2019-12-24', 'YASİN AYCAN', '05304146688', 'MELAHAT AYCAN', '05076050513', 'SELÇUKBEY MAH 657 SOK A BLOK D:14 ESSE LİFE', 'EMİNE DİLARA TOPÇU', '05454564569', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1983, '14655085060', 'MEHMET AKİF', 'EKER', 'ANASINIF-A', '2020-07-01', 'GÜLSÜM EKER', '05079613173', 'YASİR EKER', '05434585135', 'PINARKENT MAH 165 SOK DEDAKENT SİTESİ E BLOK K:3 D:13', 'EMİNE DİLARA TOPÇU', '05454564569', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1984, '17551840528', 'ÖYKÜ', 'ÇAVDAR', 'ANASINIF-A', '2020-09-16', 'ALİ ÇAVDAR', '05354040643', 'NADİRE ÇAVDAR', '05352424534', 'KARŞIYAKA MH. 2439/1 SK. NO:14', 'EMİNE DİLARA TOPÇU', '05454564569', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1985, '23809057150', 'ELİF HÜMA', 'TURKUT', 'ANASINIF-A', '2019-11-29', 'HASAN TURKUT', '05536075719', 'HATİCE TURKUT', '05062715948', 'KARAHASANLI MAH 2008 SOK ADLİYE LOJMANLARI D4 BLOK NO:5/23', 'EMİNE DİLARA TOPÇU', '05454564569', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10'),
(1986, '29677456286', 'KERİM EYMEN', 'KILINÇ', 'ANASINIF-A', NULL, 'AKIN KILINÇ', '05326485234', 'GÜLŞAH KILINÇ', '05349596078', 'AKKONAK MH. 1723 SK. NO:81 K:2', 'EMİNE DİLARA TOPÇU', '05454564569', NULL, 1, 1, '2025-10-23 22:15:10', '2025-10-23 22:15:10');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `vp_system_settings`
--

CREATE TABLE `vp_system_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('string','number','boolean','json') DEFAULT 'string',
  `description` text DEFAULT NULL,
  `is_public` tinyint(1) DEFAULT 0,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `vp_system_settings`
--

INSERT INTO `vp_system_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `description`, `is_public`, `updated_by`, `updated_at`) VALUES
(1, 'site_name', 'Vildan Portal', 'string', 'Site adı', 1, NULL, '2025-10-08 19:18:53'),
(2, 'site_logo', '', 'string', 'Site logosu', 1, NULL, '2025-10-08 19:18:53'),
(3, 'default_theme', 'light', 'string', 'Varsayılan tema (light/dark)', 1, NULL, '2025-10-08 19:18:53'),
(4, 'students_per_page', '50', 'number', 'Sayfa başına öğrenci sayısı', 0, NULL, '2025-10-08 19:18:53'),
(5, 'activities_per_page', '20', 'number', 'Sayfa başına etkinlik sayısı', 0, NULL, '2025-10-08 19:18:53'),
(6, 'search_results_per_page', '20', 'number', 'Arama sonuçları sayfa başına', 0, NULL, '2025-10-08 19:18:53'),
(7, 'session_lifetime', '30', 'number', 'Oturum süresi (gün)', 0, NULL, '2025-10-08 19:18:53'),
(8, 'max_concurrent_sessions', '5', 'number', 'Maksimum eşzamanlı oturum sayısı', 0, NULL, '2025-10-08 19:18:53'),
(9, 'enable_google_login', '1', 'boolean', 'Google ile giriş aktif', 0, NULL, '2025-10-08 19:18:53'),
(10, 'google_client_id', '', 'string', 'Google OAuth Client ID', 0, NULL, '2025-10-08 19:18:53'),
(11, 'google_client_secret', '', 'string', 'Google OAuth Client Secret', 0, NULL, '2025-10-08 19:18:53');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `vp_time_slots`
--

CREATE TABLE `vp_time_slots` (
  `id` int(11) NOT NULL,
  `time_code` varchar(20) NOT NULL COMMENT 'Saat kodu (örn: 09-0930)',
  `display_time` varchar(50) NOT NULL COMMENT 'Gösterim formatı (örn: 09:00 - 09:30)',
  `start_time` time NOT NULL COMMENT 'Başlangıç saati',
  `end_time` time NOT NULL COMMENT 'Bitiş saati',
  `sort_order` int(11) DEFAULT 0 COMMENT 'Sıralama',
  `is_active` tinyint(1) DEFAULT 1 COMMENT 'Etkin/Pasif',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Saat dilimleri tablosu';

--
-- Tablo döküm verisi `vp_time_slots`
--

INSERT INTO `vp_time_slots` (`id`, `time_code`, `display_time`, `start_time`, `end_time`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '09-0930', '09:00 - 09:30', '09:00:00', '09:30:00', 1, 1, '2025-10-23 21:53:46', '2025-10-23 21:53:46'),
(2, '0930-10', '09:30 - 10:00', '09:30:00', '10:00:00', 2, 1, '2025-10-23 21:53:46', '2025-10-23 21:53:46'),
(3, '10-1030', '10:00 - 10:30', '10:00:00', '10:30:00', 3, 1, '2025-10-23 21:53:46', '2025-10-23 21:53:46'),
(4, '1030-11', '10:30 - 11:00', '10:30:00', '11:00:00', 4, 1, '2025-10-23 21:53:46', '2025-10-23 21:53:46'),
(5, '11-1130', '11:00 - 11:30', '11:00:00', '11:30:00', 5, 1, '2025-10-23 21:53:46', '2025-10-23 21:53:46'),
(6, '1130-12', '11:30 - 12:00', '11:30:00', '12:00:00', 6, 1, '2025-10-23 21:53:46', '2025-10-23 21:53:46'),
(7, '12-1230', '12:00 - 12:30', '12:00:00', '12:30:00', 7, 1, '2025-10-23 21:53:46', '2025-10-23 21:53:46'),
(8, '1230-13', '12:30 - 13:00', '12:30:00', '13:00:00', 8, 1, '2025-10-23 21:53:46', '2025-10-23 21:53:46'),
(9, '13-1330', '13:00 - 13:30', '13:00:00', '13:30:00', 9, 1, '2025-10-23 21:53:46', '2025-10-23 21:53:46'),
(10, '1330-14', '13:30 - 14:00', '13:30:00', '14:00:00', 10, 1, '2025-10-23 21:53:46', '2025-10-23 21:53:46'),
(11, '14-1430', '14:00 - 14:30', '14:00:00', '14:30:00', 11, 1, '2025-10-23 21:53:46', '2025-10-23 21:53:46'),
(12, '1430-15', '14:30 - 15:00', '14:30:00', '15:00:00', 12, 1, '2025-10-23 21:53:46', '2025-10-23 21:53:46'),
(13, '15-1530', '15:00 - 15:30', '15:00:00', '15:30:00', 13, 1, '2025-10-23 21:53:46', '2025-10-23 21:53:46'),
(14, '1530-16', '15:30 - 16:00', '15:30:00', '16:00:00', 14, 1, '2025-10-23 21:53:46', '2025-10-23 21:53:46'),
(15, '16-1630', '16:00 - 16:30', '16:00:00', '16:30:00', 15, 1, '2025-10-23 21:53:46', '2025-10-23 21:53:46'),
(16, '1630-17', '16:30 - 17:00', '16:30:00', '17:00:00', 16, 1, '2025-10-23 21:53:46', '2025-10-23 21:53:46'),
(17, '17-1730', '17:00 - 17:30', '17:00:00', '17:30:00', 17, 1, '2025-10-23 21:53:46', '2025-10-23 21:53:46'),
(18, '1730-18', '17:30 - 18:00', '17:30:00', '18:00:00', 18, 1, '2025-10-23 21:53:46', '2025-10-23 21:53:46'),
(19, '18-1830', '18:00 - 18:30', '18:00:00', '18:30:00', 19, 1, '2025-10-23 21:53:46', '2025-10-23 21:53:46'),
(20, '1830-19', '18:30 - 19:00', '18:30:00', '19:00:00', 20, 1, '2025-10-23 21:53:46', '2025-10-23 21:53:46'),
(21, '19-1930', '19:00 - 19:30', '19:00:00', '19:30:00', 21, 1, '2025-10-23 21:53:46', '2025-10-23 21:53:46'),
(22, '1930-20', '19:30 - 20:00', '19:30:00', '20:00:00', 22, 1, '2025-10-23 21:53:46', '2025-10-23 21:53:46'),
(23, '20-2030', '20:00 - 20:30', '20:00:00', '20:30:00', 23, 1, '2025-10-23 21:53:46', '2025-10-23 21:53:46'),
(24, '2030-21', '20:30 - 21:00', '20:30:00', '21:00:00', 24, 1, '2025-10-23 21:53:46', '2025-10-23 21:53:46'),
(25, '21-2130', '21:00 - 21:30', '21:00:00', '21:30:00', 25, 1, '2025-10-23 21:53:46', '2025-10-23 21:53:46'),
(26, '2130-22', '21:30 - 22:00', '21:30:00', '22:00:00', 26, 1, '2025-10-23 21:53:46', '2025-10-23 21:53:46');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `vp_users`
--

CREATE TABLE `vp_users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `phone` varchar(20) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `can_change_password` tinyint(1) DEFAULT 1,
  `last_login` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login_at` datetime DEFAULT NULL,
  `sessions_valid_from` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `vp_users`
--

INSERT INTO `vp_users` (`id`, `username`, `email`, `password_hash`, `full_name`, `role_id`, `is_active`, `phone`, `profile_photo`, `google_id`, `can_change_password`, `last_login`, `last_login_ip`, `created_at`, `updated_at`, `last_login_at`, `sessions_valid_from`) VALUES
(1, 'tarihci20', 'admin@vildanportal.com', '$2y$10$O2zm8VliTF6.2XujECWOLOIn8PfwqLjByxcrWuqT2rlfMYmSWR3V6', 'Admin Kullanıcı', 1, 1, NULL, NULL, NULL, 1, '2025-10-12 16:59:36', '185.118.178.11', '2025-10-08 19:18:53', '2025-10-24 15:17:58', '2025-10-24 18:17:58', '2025-10-12 18:20:35'),
(5, 'vildan', 'vildankoleji@gmail.com', '$2y$10$WilIrfKZ3/1rIxGoDrGriuTJA/NlLpTzAIfiBIUc0Dz5MxV8vrFBi', 'Vildan Öğretmen', 2, 1, NULL, NULL, NULL, 0, NULL, '185.118.178.11', '2025-10-23 21:33:11', '2025-10-23 21:40:55', '2025-10-24 00:40:55', '2025-10-23 21:33:11');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `vp_user_sessions`
--

CREATE TABLE `vp_user_sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_token` varchar(255) NOT NULL,
  `device_info` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `remember_me` tinyint(1) DEFAULT 0,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `vp_activities`
--
ALTER TABLE `vp_activities`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `vp_activity_areas`
--
ALTER TABLE `vp_activity_areas`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `vp_activity_time_slots`
--
ALTER TABLE `vp_activity_time_slots`
  ADD PRIMARY KEY (`activity_id`,`time_slot_id`);

--
-- Tablo için indeksler `vp_role_page_permissions`
--
ALTER TABLE `vp_role_page_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_role_page` (`role_id`,`page_id`);

--
-- Tablo için indeksler `vp_students`
--
ALTER TABLE `vp_students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_tc_no` (`tc_no`);

--
-- Tablo için indeksler `vp_time_slots`
--
ALTER TABLE `vp_time_slots`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_time_code` (`time_code`);

--
-- Tablo için indeksler `vp_users`
--
ALTER TABLE `vp_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_username` (`username`),
  ADD UNIQUE KEY `unique_email` (`email`);

--
-- Tablo için indeksler `vp_user_sessions`
--
ALTER TABLE `vp_user_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_vp_user_sessions_user_id` (`user_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `vp_activities`
--
ALTER TABLE `vp_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- Tablo için AUTO_INCREMENT değeri `vp_activity_areas`
--
ALTER TABLE `vp_activity_areas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Tablo için AUTO_INCREMENT değeri `vp_role_page_permissions`
--
ALTER TABLE `vp_role_page_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- Tablo için AUTO_INCREMENT değeri `vp_students`
--
ALTER TABLE `vp_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1987;

--
-- Tablo için AUTO_INCREMENT değeri `vp_time_slots`
--
ALTER TABLE `vp_time_slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Tablo için AUTO_INCREMENT değeri `vp_users`
--
ALTER TABLE `vp_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `vp_user_sessions`
--
ALTER TABLE `vp_user_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `vp_user_sessions`
--
ALTER TABLE `vp_user_sessions`
  ADD CONSTRAINT `fk_vp_user_sessions_user_id` FOREIGN KEY (`user_id`) REFERENCES `vp_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
