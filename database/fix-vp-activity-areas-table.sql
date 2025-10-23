-- =====================================================
-- Fix vp_activity_areas Table - Add PRIMARY KEY & AUTO_INCREMENT
-- =====================================================

SET FOREIGN_KEY_CHECKS=0;

-- =====================================================
-- 1. Add PRIMARY KEY to vp_activity_areas
-- =====================================================

ALTER TABLE `vp_activity_areas`
  ADD PRIMARY KEY (`id`);

-- =====================================================
-- 2. Fix AUTO_INCREMENT on id
-- =====================================================

ALTER TABLE `vp_activity_areas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- =====================================================
-- 3. Verify Structure
-- =====================================================

DESCRIBE `vp_activity_areas`;

-- Check row count
SELECT COUNT(*) as total_areas FROM `vp_activity_areas`;

-- Check max id
SELECT MAX(id) as max_id FROM `vp_activity_areas`;

SET FOREIGN_KEY_CHECKS=1;

-- =====================================================
-- Script Complete
-- =====================================================
-- Now you can insert new activity areas without error
-- INSERT INTO vp_activity_areas (area_name, color_code, is_active)
-- VALUES ('Yeni Alan', '#3B82F6', 1);
-- =====================================================
