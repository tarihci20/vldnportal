-- =====================================================
-- Fix Production Database Issues (Simplified)
-- Vildan Portal v2 - Database Structure Repair
-- NO INFORMATION_SCHEMA QUERIES (hosting restrictions)
-- =====================================================

-- ÖNEMLI: Bu script production database'ini düzeltir.
-- Backup al ve test et ÖNCE!

SET FOREIGN_KEY_CHECKS=0;

-- =====================================================
-- 1. Fix AUTO_INCREMENT on vp_users id column
-- =====================================================

-- PRIMARY KEY zaten var, sadece AUTO_INCREMENT ekle
ALTER TABLE `vp_users` 
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- =====================================================
-- 2. Add UNIQUE Indexes to vp_users
-- =====================================================

-- username UNIQUE INDEX
ALTER TABLE `vp_users` 
  ADD UNIQUE INDEX `unique_username` (`username`);

-- email UNIQUE INDEX
ALTER TABLE `vp_users` 
  ADD UNIQUE INDEX `unique_email` (`email`);

-- =====================================================
-- 3. Add FOREIGN KEY Constraint
-- =====================================================

-- FK: vp_users.role_id -> vp_roles.id
ALTER TABLE `vp_users`
  ADD CONSTRAINT `fk_vp_users_role_id`
  FOREIGN KEY (`role_id`) REFERENCES `vp_roles`(`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;

-- =====================================================
-- 4. Basic Verification (no INFORMATION_SCHEMA)
-- =====================================================

-- Verify vp_users structure
DESCRIBE `vp_users`;

-- Verify vp_roles exists
DESCRIBE `vp_roles`;

-- Check vp_users data
SELECT COUNT(*) as total_users FROM `vp_users`;

-- Check vp_roles data
SELECT COUNT(*) as total_roles FROM `vp_roles`;

-- Attempt INSERT test (optional - uncomment to test AUTO_INCREMENT)
-- SELECT 'AUTO_INCREMENT TEST - COMMENT OUT FOR PRODUCTION' as test_status;
-- INSERT INTO vp_users (username, email, password_hash, role_id, is_active, created_at, updated_at)
-- VALUES ('test_autoincrement', 'test@test.local', 'test', 1, 1, NOW(), NOW());
-- SELECT 'User created with ID:' as status, LAST_INSERT_ID() as new_id;
-- DELETE FROM vp_users WHERE username = 'test_autoincrement';

SET FOREIGN_KEY_CHECKS=1;

-- =====================================================
-- Script Complete
-- =====================================================
-- Expected results:
-- - No errors about duplicate keys (if they already exist)
-- - DESCRIBE shows AUTO_INCREMENT in id EXTRA column
-- - Both tables verified with row counts
-- =====================================================
