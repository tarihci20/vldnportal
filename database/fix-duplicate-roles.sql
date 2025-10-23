-- =====================================================
-- Fix Duplicate Roles in vp_roles Table
-- =====================================================

-- BACKUP ALMADAN ÖNCE BU SQL'İ ÇALIŞTIRMA!

SET FOREIGN_KEY_CHECKS=0;

-- =====================================================
-- 1. Duplicate teacher entries (id=3) sil
-- =====================================================

-- Önce kontrol et kaç tane var
SELECT COUNT(*) as duplicate_count FROM vp_roles WHERE id = 3;

-- DELETE from duplicates (last 2 teacher entries with id=3)
DELETE FROM vp_roles 
WHERE id = 3 AND role_name = 'teacher' 
ORDER BY created_at DESC 
LIMIT 2;

-- =====================================================
-- 2. Verify sonuç
-- =====================================================

-- id=3 sadece secretary kalmalı
SELECT * FROM vp_roles WHERE id = 3;

-- Tüm roles göster
SELECT * FROM vp_roles ORDER BY id;

-- =====================================================
-- 3. Add PRIMARY KEY if not exists (for safety)
-- =====================================================

-- Check if primary key exists
SHOW INDEXES FROM vp_roles WHERE Key_name = 'PRIMARY';

-- If no PRIMARY KEY, add it:
-- ALTER TABLE vp_roles ADD PRIMARY KEY (id);

SET FOREIGN_KEY_CHECKS=1;

-- =====================================================
-- Completed
-- =====================================================
