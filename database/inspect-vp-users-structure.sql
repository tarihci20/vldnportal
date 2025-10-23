-- =====================================================
-- INSPECTION ONLY - No Changes (Safe to run anytime)
-- Vildan Portal v2 - Database Structure Check
-- =====================================================

-- Bu script sadece mevcut database yapısını kontrol eder.
-- Hiçbir değişiklik yapmaz.

USE `vildacgg_portalv2`;

-- =====================================================
-- 1. Check vp_users Table Structure
-- =====================================================

DESCRIBE `vp_users`;

-- =====================================================
-- 2. Check Indexes on vp_users
-- =====================================================

SHOW INDEXES FROM `vp_users`;

-- =====================================================
-- 3. Check PRIMARY KEY Details
-- =====================================================

SELECT 
  CONSTRAINT_NAME,
  COLUMN_NAME,
  ORDINAL_POSITION,
  REFERENCED_TABLE_NAME,
  REFERENCED_COLUMN_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE TABLE_NAME = 'vp_users' AND TABLE_SCHEMA = 'vildacgg_portalv2';

-- =====================================================
-- 4. Check AUTO_INCREMENT Status
-- =====================================================

SELECT 
  TABLE_NAME,
  COLUMN_NAME,
  COLUMN_TYPE,
  EXTRA
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = 'vp_users' 
  AND TABLE_SCHEMA = 'vildacgg_portalv2'
  AND COLUMN_NAME = 'id';

-- =====================================================
-- 5. Check UNIQUE Indexes
-- =====================================================

SELECT 
  TABLE_NAME,
  INDEX_NAME,
  COLUMN_NAME,
  SEQ_IN_INDEX,
  NON_UNIQUE
FROM INFORMATION_SCHEMA.STATISTICS
WHERE TABLE_NAME = 'vp_users'
  AND TABLE_SCHEMA = 'vildacgg_portalv2'
  AND NON_UNIQUE = 0;  -- 0 = UNIQUE, 1 = non-unique

-- =====================================================
-- 6. Check FOREIGN KEY Constraints
-- =====================================================

SELECT 
  CONSTRAINT_NAME,
  TABLE_NAME,
  COLUMN_NAME,
  REFERENCED_TABLE_NAME,
  REFERENCED_COLUMN_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE TABLE_NAME = 'vp_users' 
  AND TABLE_SCHEMA = 'vildacgg_portalv2'
  AND REFERENCED_TABLE_NAME IS NOT NULL;

-- =====================================================
-- 7. Check vp_roles Table (for FK reference)
-- =====================================================

DESCRIBE `vp_roles`;

-- =====================================================
-- 8. Check for NULL values in role_id (for FK validation)
-- =====================================================

SELECT COUNT(*) as null_count FROM `vp_users` WHERE role_id IS NULL;
SELECT COUNT(*) as total_count FROM `vp_users`;

-- =====================================================
-- 9. Check for invalid role_id values (orphaned records)
-- =====================================================

SELECT COUNT(*) as orphaned_count 
FROM `vp_users` u 
LEFT JOIN `vp_roles` r ON u.role_id = r.id 
WHERE r.id IS NULL;

-- =====================================================
-- 10. Show CREATE TABLE (complete table definition)
-- =====================================================

SHOW CREATE TABLE `vp_users`;

-- =====================================================
-- Inspection Complete
-- =====================================================
