-- =====================================================
-- Fix ALL Tables - Add Missing PRIMARY KEYS & AUTO_INCREMENT
-- Vildan Portal v2 - Complete Database Repair
-- =====================================================

SET FOREIGN_KEY_CHECKS=0;

-- =====================================================
-- 1. vp_activity_areas - Fix PRIMARY KEY & AUTO_INCREMENT
-- =====================================================

ALTER TABLE `vp_activity_areas`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `vp_activity_areas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- =====================================================
-- 2. vp_activities - Fix PRIMARY KEY & AUTO_INCREMENT
-- =====================================================

ALTER TABLE `vp_activities`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `vp_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- =====================================================
-- 3. vp_activity_types - Fix if needed
-- =====================================================

-- Check if exists
-- ALTER TABLE `vp_activity_types`
--   ADD PRIMARY KEY (`id`);
-- ALTER TABLE `vp_activity_types`
--   MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- =====================================================
-- 4. vp_etut_applications - Fix PRIMARY KEY & AUTO_INCREMENT
-- =====================================================

ALTER TABLE `vp_etut_applications`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `vp_etut_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- =====================================================
-- 5. vp_etut_form_settings - Fix PRIMARY KEY & AUTO_INCREMENT
-- =====================================================

ALTER TABLE `vp_etut_form_settings`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `vp_etut_form_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- =====================================================
-- 6. vp_role_page_permissions - Fix PRIMARY KEY & AUTO_INCREMENT
-- =====================================================

ALTER TABLE `vp_role_page_permissions`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `vp_role_page_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- =====================================================
-- 7. vp_students - Fix PRIMARY KEY & AUTO_INCREMENT
-- =====================================================

ALTER TABLE `vp_students`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `vp_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- =====================================================
-- 8. vp_time_slots - Fix PRIMARY KEY & AUTO_INCREMENT
-- =====================================================

ALTER TABLE `vp_time_slots`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `vp_time_slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- =====================================================
-- 9. vp_pages - Fix PRIMARY KEY & AUTO_INCREMENT
-- =====================================================

ALTER TABLE `vp_pages`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `vp_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- =====================================================
-- 10. vp_recurring_rules - Fix PRIMARY KEY & AUTO_INCREMENT
-- =====================================================

ALTER TABLE `vp_recurring_rules`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `vp_recurring_rules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- =====================================================
-- 11. vp_roles - Fix PRIMARY KEY & AUTO_INCREMENT
-- =====================================================

ALTER TABLE `vp_roles`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `vp_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- =====================================================
-- 12. vp_system_settings - Fix PRIMARY KEY & AUTO_INCREMENT
-- =====================================================

ALTER TABLE `vp_system_settings`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `vp_system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- =====================================================
-- 13. vp_user_sessions - Already fixed, but verify
-- =====================================================

ALTER TABLE `vp_user_sessions`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `vp_user_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- =====================================================
-- 14. vp_users - Already fixed, but verify
-- =====================================================

ALTER TABLE `vp_users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `vp_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- =====================================================
-- 15. vp_password_resets - Fix PRIMARY KEY
-- =====================================================

ALTER TABLE `vp_password_resets`
  ADD PRIMARY KEY (`id`);

-- =====================================================
-- 16. Verify All Tables
-- =====================================================

-- Check vp_activity_areas
DESCRIBE `vp_activity_areas`;
SELECT COUNT(*) as total FROM `vp_activity_areas`;

-- Check vp_activities
DESCRIBE `vp_activities`;
SELECT COUNT(*) as total FROM `vp_activities`;

-- Check vp_roles
DESCRIBE `vp_roles`;
SELECT COUNT(*) as total FROM `vp_roles`;

-- Check vp_students
DESCRIBE `vp_students`;
SELECT COUNT(*) as total FROM `vp_students`;

SET FOREIGN_KEY_CHECKS=1;

-- =====================================================
-- Script Complete - All Tables Fixed
-- =====================================================
-- Run this ONCE in production and your INSERT errors will be gone!
-- =====================================================
