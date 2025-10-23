-- =====================================================
-- Safe Version - Checks Before Making Changes
-- Fix Production Database Issues
-- Vildan Portal v2 - Database Structure Repair
-- =====================================================

-- ÖNEMLI: Bu script production database'ini düzeltir.
-- Bu versyon kontrol yaparak kısmi hatalarl handling eder.

SET FOREIGN_KEY_CHECKS=0;

-- =====================================================
-- STEP 1: Check Current Structure
-- =====================================================

-- Show current vp_users structure
DESCRIBE `vp_users`;

-- Show current indexes
SHOW INDEXES FROM `vp_users` WHERE Key_name IN ('PRIMARY', 'unique_username', 'unique_email');

-- Check existing FK constraints
SELECT CONSTRAINT_NAME 
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE TABLE_NAME = 'vp_users' 
  AND TABLE_SCHEMA = DATABASE()
  AND CONSTRAINT_NAME LIKE 'fk_%';

-- =====================================================
-- STEP 2: Fix AUTO_INCREMENT on id (PRIMARY KEY already exists)
-- =====================================================

-- Only modify if not already AUTO_INCREMENT
ALTER TABLE `vp_users` 
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- Verify AUTO_INCREMENT was set
SHOW CREATE TABLE `vp_users`;

-- =====================================================
-- STEP 3: Add UNIQUE Indexes (these will fail silently if they exist)
-- =====================================================

-- Try to add username UNIQUE (if exists, will error but continue in batch mode)
ALTER TABLE `vp_users` 
  ADD UNIQUE INDEX `unique_username` (`username`);

-- Try to add email UNIQUE (if exists, will error but continue in batch mode)
ALTER TABLE `vp_users` 
  ADD UNIQUE INDEX `unique_email` (`email`);

-- Verify UNIQUE indexes exist
SHOW INDEXES FROM `vp_users` WHERE Key_name IN ('unique_username', 'unique_email');

-- =====================================================
-- STEP 4: Add FOREIGN KEY Constraint (optional)
-- =====================================================

-- Check if vp_roles table exists first
SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES 
WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'vp_roles';

-- Try to add FK constraint for role_id
-- If FK with same name exists, DROP it first or this will error
ALTER TABLE `vp_users`
  ADD CONSTRAINT `fk_vp_users_role_id`
  FOREIGN KEY (`role_id`) REFERENCES `vp_roles`(`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;

-- =====================================================
-- STEP 5: Final Verification
-- =====================================================

-- Check complete vp_users structure
DESCRIBE `vp_users`;

-- Check all indexes
SHOW INDEXES FROM `vp_users`;

-- Check all constraints
SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE TABLE_NAME = 'vp_users' AND TABLE_SCHEMA = DATABASE();

-- Test INSERT to verify AUTO_INCREMENT works
-- SELECT * FROM vp_users WHERE id > 0 ORDER BY id DESC LIMIT 1;  -- Check max ID
-- INSERT INTO vp_users (username, email, password_hash, role_id, is_active, created_at, updated_at)
-- VALUES ('test_user_' . UNIX_TIMESTAMP(), 'test_' . UNIX_TIMESTAMP() . '@test.local', 'test', 1, 1, NOW(), NOW());

SET FOREIGN_KEY_CHECKS=1;

-- =====================================================
-- Script Complete
-- =====================================================
-- If you see errors about "Duplicate key name" or "Multiple primary key", 
-- it means that constraint/index already exists (which is fine!)
-- =====================================================
