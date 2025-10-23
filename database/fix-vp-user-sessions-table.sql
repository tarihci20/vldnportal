-- =====================================================
-- Fix vp_user_sessions Table Structure
-- Add PRIMARY KEY and FOREIGN KEY constraints
-- =====================================================

SET FOREIGN_KEY_CHECKS=0;

-- =====================================================
-- 1. Add PRIMARY KEY to vp_user_sessions
-- =====================================================

ALTER TABLE `vp_user_sessions`
  ADD PRIMARY KEY (`id`);

-- =====================================================
-- 2. Fix AUTO_INCREMENT on vp_user_sessions id
-- =====================================================

ALTER TABLE `vp_user_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- =====================================================
-- 3. Add FOREIGN KEY constraint (user_id -> vp_users.id)
-- =====================================================

-- This will allow automatic cascading delete when user is deleted
ALTER TABLE `vp_user_sessions`
  ADD CONSTRAINT `fk_vp_user_sessions_user_id`
  FOREIGN KEY (`user_id`) REFERENCES `vp_users`(`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

-- =====================================================
-- 4. Verify Structure
-- =====================================================

DESCRIBE `vp_user_sessions`;

-- Check row count
SELECT COUNT(*) as total_sessions FROM `vp_user_sessions`;

SET FOREIGN_KEY_CHECKS=1;

-- =====================================================
-- Script Complete
-- =====================================================
-- This allows user deletion to cascade and automatically
-- delete all related session records
-- =====================================================
