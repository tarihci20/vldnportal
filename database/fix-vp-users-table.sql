-- =====================================================
-- Fix Production Database Issues
-- Vildan Portal v2 - Database Structure Repair
-- =====================================================

-- ÖNEMLI: Bu script production database'ini düzeltir.
-- Backup al ve test et ÖNCE!

SET FOREIGN_KEY_CHECKS=0;

-- =====================================================
-- 1. ALTER TABLE vp_users - Add PRIMARY KEY & AUTO_INCREMENT
-- =====================================================

-- Existing PRIMARY KEY'i kaldır (eğer varsa)
ALTER TABLE `vp_users` DROP PRIMARY KEY;

-- Şimdi PRIMARY KEY ve AUTO_INCREMENT ekle
ALTER TABLE `vp_users` 
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
  ADD PRIMARY KEY (`id`);

-- =====================================================
-- 2. ADD UNIQUE INDEXES (if not exist)
-- =====================================================

-- username UNIQUE
ALTER TABLE `vp_users` 
  ADD UNIQUE INDEX `unique_username` (`username`);

-- email UNIQUE  
ALTER TABLE `vp_users` 
  ADD UNIQUE INDEX `unique_email` (`email`);

-- =====================================================
-- 3. ADD FOREIGN KEY CONSTRAINTS
-- =====================================================

-- FK: vp_users.role_id -> vp_roles.id
ALTER TABLE `vp_users`
  ADD CONSTRAINT `fk_vp_users_role_id`
  FOREIGN KEY (`role_id`) REFERENCES `vp_roles`(`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;

-- =====================================================
-- 4. Verify Structure
-- =====================================================

-- Check vp_users structure
DESCRIBE `vp_users`;

-- Check indexes
SHOW INDEXES FROM `vp_users`;

-- Check constraints
SELECT CONSTRAINT_NAME, TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE TABLE_NAME = 'vp_users' AND TABLE_SCHEMA = DATABASE();

SET FOREIGN_KEY_CHECKS=1;

-- =====================================================
-- Script Complete
-- =====================================================
-- Run this in phpMyAdmin or MySQL CLI:
-- mysql -u vildacgg_tarihci20 -p vildacgg_portalv2 < fix-database.sql
-- =====================================================
