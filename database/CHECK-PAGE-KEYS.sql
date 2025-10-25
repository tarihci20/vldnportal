-- ============================================================================
-- DATABASE PAGE_KEY CHECK
-- Sidebar'da kullanılan page_key'lerin database'de olup olmadığını kontrol et
-- ============================================================================

USE vildacgg_portalv2;

-- 1. Database'de kayıtlı TÜMSAYFALAR
SELECT id, page_name, page_key, is_active FROM vp_pages ORDER BY id;

-- 2. Sidebar'da kullanılan page_key'ler şunlar:
-- - dashboard
-- - student-search
-- - students
-- - activities
-- - activity-areas
-- - etut (veya etut-ortaokul, etut-lise)
-- - reports
-- - users
-- - roles
-- - settings

-- 3. EKSIK page_key kontrolü (Sidebar'da var ama DB'de yok)
-- Şu değerler database'de OLMALI:
SELECT 
    'dashboard' as sidebar_page_key,
    CASE WHEN EXISTS (SELECT 1 FROM vp_pages WHERE page_key = 'dashboard') THEN '✅' ELSE '❌ MISSING!' END as db_status
UNION ALL SELECT 'student-search', CASE WHEN EXISTS (SELECT 1 FROM vp_pages WHERE page_key = 'student-search') THEN '✅' ELSE '❌ MISSING!' END
UNION ALL SELECT 'students', CASE WHEN EXISTS (SELECT 1 FROM vp_pages WHERE page_key = 'students') THEN '✅' ELSE '❌ MISSING!' END
UNION ALL SELECT 'activities', CASE WHEN EXISTS (SELECT 1 FROM vp_pages WHERE page_key = 'activities') THEN '✅' ELSE '❌ MISSING!' END
UNION ALL SELECT 'activity-areas', CASE WHEN EXISTS (SELECT 1 FROM vp_pages WHERE page_key = 'activity-areas') THEN '✅' ELSE '❌ MISSING!' END
UNION ALL SELECT 'etut', CASE WHEN EXISTS (SELECT 1 FROM vp_pages WHERE page_key = 'etut') THEN '✅' ELSE '❌ MISSING!' END
UNION ALL SELECT 'etut-ortaokul', CASE WHEN EXISTS (SELECT 1 FROM vp_pages WHERE page_key = 'etut-ortaokul') THEN '✅' ELSE '❌ MISSING!' END
UNION ALL SELECT 'etut-lise', CASE WHEN EXISTS (SELECT 1 FROM vp_pages WHERE page_key = 'etut-lise') THEN '✅' ELSE '❌ MISSING!' END
UNION ALL SELECT 'reports', CASE WHEN EXISTS (SELECT 1 FROM vp_pages WHERE page_key = 'reports') THEN '✅' ELSE '❌ MISSING!' END
UNION ALL SELECT 'users', CASE WHEN EXISTS (SELECT 1 FROM vp_pages WHERE page_key = 'users') THEN '✅' ELSE '❌ MISSING!' END
UNION ALL SELECT 'roles', CASE WHEN EXISTS (SELECT 1 FROM vp_pages WHERE page_key = 'roles') THEN '✅' ELSE '❌ MISSING!' END
UNION ALL SELECT 'settings', CASE WHEN EXISTS (SELECT 1 FROM vp_pages WHERE page_key = 'settings') THEN '✅' ELSE '❌ MISSING!' END;

-- 4. EĞER EKSIKLER VARSA, eklemek için SQL (✅ işaretli olanları atlayın):
-- INSERT INTO vp_pages (page_name, page_key, page_url, is_active, sort_order) VALUES
-- ('Dashboard', 'dashboard', '/dashboard', 1, 1),
-- ('Öğrenci Ara', 'student-search', '/student-search', 1, 2),
-- ('Öğrenciler', 'students', '/students', 1, 3),
-- ('Etkinlikler', 'activities', '/activities', 1, 4),
-- ('Etkinlik Alanları', 'activity-areas', '/activity-areas', 1, 5),
-- ('Etüt Yönetimi', 'etut', '/etut', 1, 6),
-- ('Ortaokul Etüt', 'etut-ortaokul', '/etut/ortaokul', 1, 7),
-- ('Lise Etüt', 'etut-lise', '/etut/lise', 1, 8),
-- ('Raporlar', 'reports', '/reports', 1, 9),
-- ('Kullanıcılar', 'users', '/admin/users', 1, 10),
-- ('Roller', 'roles', '/admin/roles', 1, 11),
-- ('Ayarlar', 'settings', '/admin/settings', 1, 12);
