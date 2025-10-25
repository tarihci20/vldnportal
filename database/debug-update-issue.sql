-- ===================================
-- PROBLEM DEBUG: ADMIN PANEL'DEN UPDATE SORUNU
-- ===================================

USE vildacgg_portalv2;

-- 1. ROLE 5 SON UPDATED TIME KONTROL
SELECT 'ROLE 5 EN SON GÜNCELLEMELERİ:' as test;
SELECT 
    id,
    role_id,
    page_id,
    can_view,
    can_create,
    can_edit,
    can_delete,
    updated_at
FROM vp_role_page_permissions
WHERE role_id = 5
ORDER BY updated_at DESC
LIMIT 5;

-- 2. ADMIN PANEL FORM SUBMIT SONRASI DEĞER DEĞİŞTİ Mİ?
-- (Admin Panel'den page 12'nin can_create'i 0 yapıp test et)
-- Sonra bu query'i çalıştır
SELECT 'PAGE 12 DETAY (ROLE 5):' as test;
SELECT 
    id,
    role_id,
    page_id,
    can_view,
    can_create,
    can_edit,
    can_delete,
    updated_at
FROM vp_role_page_permissions
WHERE role_id = 5 AND page_id = 12;

-- 3. ADMIN USER KİMLİĞİ KONTROL
SELECT 'ADMIN USER KONTROL:' as test;
SELECT id, name, email, role_id, is_active FROM vp_users WHERE email LIKE '%admin%' OR role_id = 1 LIMIT 5;

-- 4. ROLE 5 İZİN VERİLEN ROLLER
SELECT 'ROLE 5 PERMISSION SUMMARY:' as test;
SELECT 
    role_id,
    COUNT(*) as total_permissions,
    SUM(can_view) as view_count,
    SUM(can_create) as create_count,
    SUM(can_edit) as edit_count,
    SUM(can_delete) as delete_count
FROM vp_role_page_permissions
WHERE role_id = 5;

-- 5. DELETE ÇALIŞTI MI AMA INSERT BAŞARISIZ MI?
-- Eğer tüm kayıtlar aynı timestamp'e sahipse, DELETE + INSERT son oluştu demek
SELECT 'CREATED_AT vs UPDATED_AT (ROLE 5):' as test;
SELECT 
    COUNT(*) as kayit_sayisi,
    created_at,
    updated_at
FROM vp_role_page_permissions
WHERE role_id = 5
GROUP BY created_at, updated_at;
