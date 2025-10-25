-- =================================================================
-- DIAGNOSTIC: hasPermission() Debug - Neden menüler görünmüyor?
-- =================================================================
-- Bu SQL'i production'da çalıştırın ve sonucu gönderin
-- =================================================================

-- ADIM 1: emine kullanıcısının role_id'sini bul
-- =================================================================
SELECT 
    u.id as user_id,
    u.name as kullanici_adi,
    u.role_id,
    r.role_name as rol_adi
FROM vp_users u
JOIN vp_roles r ON r.id = u.role_id
WHERE u.name = 'emine';

-- BEKLENEN: role_id = 5 (vice_principal)

-- =================================================================
-- ADIM 2: Role ID 5'in SAHİP OLDUĞU permission'ları listele
-- =================================================================
SELECT 
    p.page_key,
    p.page_name,
    p.page_url,
    rpp.can_view,
    rpp.can_create,
    rpp.can_edit,
    rpp.can_delete
FROM vp_role_page_permissions rpp
JOIN vp_pages p ON p.id = rpp.page_id
WHERE rpp.role_id = 5
ORDER BY p.page_key;

-- BEKLENEN: 22 satır (tüm page_key'ler)

-- =================================================================
-- ADIM 3: hasPermission() fonksiyonunun ARADIĞI page_key'ler
-- =================================================================
-- Sidebar'da kullanılan page_key'ler:

SELECT 
    'student-search' as sidebar_page_key,
    COUNT(*) as 'vp_pages tablosunda var mı?'
FROM vp_pages 
WHERE page_key = 'student-search'
UNION ALL
SELECT 'students', COUNT(*) FROM vp_pages WHERE page_key = 'students'
UNION ALL
SELECT 'activities', COUNT(*) FROM vp_pages WHERE page_key = 'activities'
UNION ALL
SELECT 'activity-areas', COUNT(*) FROM vp_pages WHERE page_key = 'activity-areas'
UNION ALL
SELECT 'etut', COUNT(*) FROM vp_pages WHERE page_key = 'etut'
UNION ALL
SELECT 'etut-ortaokul', COUNT(*) FROM vp_pages WHERE page_key = 'etut-ortaokul'
UNION ALL
SELECT 'etut-lise', COUNT(*) FROM vp_pages WHERE page_key = 'etut-lise'
UNION ALL
SELECT 'reports', COUNT(*) FROM vp_pages WHERE page_key = 'reports'
UNION ALL
SELECT 'users', COUNT(*) FROM vp_pages WHERE page_key = 'users'
UNION ALL
SELECT 'roles', COUNT(*) FROM vp_pages WHERE page_key = 'roles'
UNION ALL
SELECT 'settings', COUNT(*) FROM vp_pages WHERE page_key = 'settings';

-- BEKLENEN: Her biri 1+ (en az 1 kayıt var)
-- EĞER 0 ise: O page_key eksik demektir!

-- =================================================================
-- ADIM 4: CRITICAL - Role 5 + Spesifik page_key permission kontrolü
-- =================================================================
-- Sidebar'da görünmeyen menüler için manuel kontrol:

-- ETÜT (etut, etut-ortaokul, etut-lise)
SELECT 
    'ETÜT KONTROL' as test,
    p.page_key,
    p.page_name,
    CASE 
        WHEN rpp.id IS NOT NULL THEN 'VAR - Permission tanımlı'
        ELSE 'YOK - Permission EKSİK!'
    END as durum,
    rpp.can_view
FROM vp_pages p
LEFT JOIN vp_role_page_permissions rpp ON rpp.page_id = p.id AND rpp.role_id = 5
WHERE p.page_key IN ('etut', 'etut-ortaokul', 'etut-lise');

-- RAPORLAR
SELECT 
    'RAPORLAR KONTROL' as test,
    p.page_key,
    p.page_name,
    CASE 
        WHEN rpp.id IS NOT NULL THEN 'VAR - Permission tanımlı'
        ELSE 'YOK - Permission EKSİK!'
    END as durum,
    rpp.can_view
FROM vp_pages p
LEFT JOIN vp_role_page_permissions rpp ON rpp.page_id = p.id AND rpp.role_id = 5
WHERE p.page_key = 'reports';

-- KULLANICILAR, ROLLER, AYARLAR
SELECT 
    'ADMIN PANEL KONTROL' as test,
    p.page_key,
    p.page_name,
    CASE 
        WHEN rpp.id IS NOT NULL THEN 'VAR - Permission tanımlı'
        ELSE 'YOK - Permission EKSİK!'
    END as durum,
    rpp.can_view
FROM vp_pages p
LEFT JOIN vp_role_page_permissions rpp ON rpp.page_id = p.id AND rpp.role_id = 5
WHERE p.page_key IN ('users', 'roles', 'settings');

-- =================================================================
-- BEKLENEN SONUÇLAR:
-- =================================================================
-- 1. emine → role_id = 5
-- 2. Role 5 → 22 permission
-- 3. Tüm page_key'ler → 1+ (var)
-- 4. Etüt/Raporlar/Admin → "VAR - Permission tanımlı" + can_view = 1
-- 
-- EĞER "YOK - Permission EKSİK!" görürseniz:
-- → O page_key için permission eklenmemiş demektir!
-- =================================================================
