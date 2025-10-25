-- ===================================
-- CREATE vp_role_page_permissions TABLE
-- Production'da bu tabloyu oluşturmak için çalıştırın
-- ===================================

USE vildacgg_portalv2;

-- Tablo zaten varsa sil (test amacı)
-- DROP TABLE IF EXISTS `vp_role_page_permissions`;

-- Tablo oluştur
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

-- Primary Key ve Unique Index ekle
ALTER TABLE `vp_role_page_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_role_page` (`role_id`,`page_id`);

-- AUTO_INCREMENT set et
ALTER TABLE `vp_role_page_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=200;

-- Test: Tablo oluştu mu kontrol et
DESCRIBE vp_role_page_permissions;

-- Test: INSERT yapabilir miyiz?
INSERT INTO `vp_role_page_permissions` (role_id, page_id, can_view, can_create, can_edit, can_delete)
VALUES (5, 12, 1, 1, 0, 0);

-- Test: Veriler kaydedildi mi?
SELECT * FROM vp_role_page_permissions WHERE role_id = 5;

-- Test verilerini sil
DELETE FROM vp_role_page_permissions WHERE role_id = 5 AND id > 199;

-- Kontrol et - boş olmalı
SELECT COUNT(*) as permission_count FROM vp_role_page_permissions WHERE role_id = 5;
