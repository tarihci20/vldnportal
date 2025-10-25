-- ============================================
-- DÜZELTMECİ: Müdür Yardımcısı için Eksik Etüt İzinlerini Ekle (Role 5)
-- ============================================
-- 
-- PROBLEM: Aşağıdaki 3 sayfa Role 5 için izne sahip değil:
-- 1. Etüt Form Ayarları
-- 2. Lise Etüt Başvuruları  
-- 3. Ortaokul Etüt Başvuruları
--
-- ÇÖZÜM: Bu sayfalar için izin kayıtları ekle
-- ============================================

-- İlk olarak, geçerli durumu kontrol edelim
SELECT 'ÖNCESI - Müdür Yardımcısı (Role 5) geçerli izinleri:' as durum;
SELECT 
    p.id,
    p.page_name,
    p.etut_type,
    COUNT(rp.id) as izin_var_mi
FROM vp_pages p
LEFT JOIN vp_role_page_permissions rp ON p.id = rp.page_id AND rp.role_id = 5
WHERE p.is_active = 1
GROUP BY p.id, p.page_name, p.etut_type
ORDER BY p.id;

-- Şimdi eksik izinleri ekleyelim
INSERT INTO vp_role_page_permissions 
(role_id, page_id, can_view, can_create, can_edit, can_delete, created_at, updated_at)
SELECT 
    5 as role_id,
    p.id as page_id,
    1 as goruntuleme,
    1 as ekleme,
    1 as duzenleme,
    1 as silme,
    NOW() as olusturulma_tarihi,
    NOW() as guncelleme_tarihi
FROM vp_pages p
WHERE p.is_active = 1
AND p.page_name IN ('Etüt Form Ayarları', 'Lise Etüt Başvuruları', 'Ortaokul Etüt Başvuruları')
AND NOT EXISTS (
    SELECT 1 FROM vp_role_page_permissions rp 
    WHERE rp.role_id = 5 AND rp.page_id = p.id
);

-- Değişiklikleri doğrulayalım
SELECT 'SONRASI - Güncellenmiş Müdür Yardımcısı (Role 5) izinleri:' as durum;
SELECT 
    p.id,
    p.page_name,
    p.etut_type,
    rp.can_view as goruntuleme,
    rp.can_create as ekleme,
    rp.can_edit as duzenleme,
    rp.can_delete as silme
FROM vp_pages p
LEFT JOIN vp_role_page_permissions rp ON p.id = rp.page_id AND rp.role_id = 5
WHERE p.is_active = 1
ORDER BY p.id;

-- Özet göster
SELECT 'ÖZETİ:' as durum;
SELECT 
    COUNT(*) as toplam_izin,
    SUM(CASE WHEN can_view = 1 THEN 1 ELSE 0 END) as goruntuleme_sayisi,
    SUM(CASE WHEN can_create = 1 THEN 1 ELSE 0 END) as ekleme_sayisi
FROM vp_role_page_permissions
WHERE role_id = 5;
