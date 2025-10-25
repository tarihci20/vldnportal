-- =================================================================
-- TEŞHIS: Permission sistemi neden çalışmıyor?
-- =================================================================
-- Production'da çalıştırın ve sonucu gönderin
-- =================================================================

-- 1. hasPermission() fonksiyonu hangi page_key'leri buluyor?
-- =================================================================
SELECT 
    'Database Durum' as test_kategori,
    p.page_key,
    COUNT(DISTINCT rpp.role_id) as 'Kaç role atanmış?'
FROM vp_pages p
LEFT JOIN vp_role_page_permissions rpp ON rpp.page_id = p.id AND rpp.can_view = 1
WHERE p.is_active = 1
GROUP BY p.page_key
ORDER BY p.page_key;

-- BEKLENEN: Her page_key için 1+ role
-- EĞER 0 görüyorsanız: O sayfa HİÇ role'e atanmamış!

-- =================================================================
-- 2. hasPermission() fonksiyonu çalışıyor mu?
-- =================================================================
-- Role 5 (vice_principal) için permission kontrolü:

SELECT 
    'hasPermission() Test' as test,
    p.page_key,
    CASE 
        WHEN rpp.can_view = 1 THEN 'TRUE - Görülebilir'
        ELSE 'FALSE - Görülemez'
    END as 'hasPermission() sonucu'
FROM vp_pages p
LEFT JOIN vp_role_page_permissions rpp ON rpp.page_id = p.id AND rpp.role_id = 5
WHERE p.page_key IN (
    'dashboard', 'student-search', 'students', 'activities', 'activity-areas',
    'etut-ortaokul', 'etut-lise', 'reports', 'users', 'roles', 'settings'
)
ORDER BY p.page_key;

-- BEKLENEN: Hepsi "TRUE - Görülebilir"
-- EĞER "FALSE" varsa: Permission kayıtları eksik!

-- =================================================================
-- 3. DUPLICATE page_key sorunu var mı?
-- =================================================================
SELECT 
    page_key,
    COUNT(*) as 'Tekrar Sayısı',
    GROUP_CONCAT(id ORDER BY id) as 'ID\'ler',
    GROUP_CONCAT(page_name ORDER BY id SEPARATOR ' | ') as 'İsimler'
FROM vp_pages
WHERE is_active = 1
GROUP BY page_key
HAVING COUNT(*) > 1
ORDER BY page_key;

-- BEKLENEN: Boş sonuç (duplicate olmamalı)
-- EĞER sonuç varsa: Aynı page_key'den birden fazla var → Bu PROBLEM!

-- =================================================================
-- 4. Session'da user bilgisi doğru mu?
-- =================================================================
-- emine kullanıcısının tüm bilgileri:

SELECT 
    u.id,
    u.username,
    u.email,
    u.role_id,
    r.role_name,
    r.display_name,
    u.is_active
FROM vp_users u
JOIN vp_roles r ON r.id = u.role_id
WHERE u.username = 'emine';

-- BEKLENEN: role_id = 5, is_active = 1

-- =================================================================
-- SONUÇ ANALİZİ:
-- =================================================================
-- Bu 4 test permission sisteminin hangi katmanında sorun olduğunu gösterir:
-- 
-- Test 1 → Database katmanı (permission kayıtları var mı?)
-- Test 2 → hasPermission() mantığı (doğru sonuç üretiyor mu?)
-- Test 3 → Data integrity (duplicate kayıtlar var mı?)
-- Test 4 → Session katmanı (user bilgisi doğru mu?)
-- =================================================================
