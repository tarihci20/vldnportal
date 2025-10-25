-- ===================================
-- ROLE 5 (VICE_PRINCIPAL) PERMİSSİONLARI EKLE
-- Production'da çalıştırın
-- ===================================

USE vildacgg_portalv2;

-- Önce mevcut permission'ları sil (eğer varsa)
DELETE FROM vp_role_page_permissions WHERE role_id = 5;

-- Role 5 (vice_principal) için tüm aktif sayfalar'a full permission ver
INSERT INTO vp_role_page_permissions (role_id, page_id, can_view, can_create, can_edit, can_delete, created_at, updated_at)
SELECT 
    5 as role_id,
    p.id as page_id,
    1 as can_view,
    1 as can_create,
    1 as can_edit,
    1 as can_delete,
    NOW() as created_at,
    NOW() as updated_at
FROM vp_pages p
WHERE p.is_active = 1
ORDER BY p.id;

-- Kontrol et - kaç permission eklendi?
SELECT 'ROLE 5 PERMİSSİON EKLEME SONUCU:' as result;
SELECT COUNT(*) as toplam_permission FROM vp_role_page_permissions WHERE role_id = 5;

-- Detaylı göster
SELECT 'ROLE 5 TÜM PERMİSSİONLARI:' as result;
SELECT 
    rp.id,
    rp.role_id,
    rp.page_id,
    p.page_name,
    p.etut_type,
    rp.can_view,
    rp.can_create,
    rp.can_edit,
    rp.can_delete,
    rp.created_at,
    rp.updated_at
FROM vp_role_page_permissions rp
LEFT JOIN vp_pages p ON rp.page_id = p.id
WHERE rp.role_id = 5
ORDER BY rp.page_id;

-- Role 5'in page 12 ve 13'e permission'ları kontrol et
SELECT 'PAGE 12, 13 KONTROL (ROLE 5):' as result;
SELECT 
    rp.page_id,
    p.page_name,
    rp.can_view,
    rp.can_create,
    rp.can_edit,
    rp.can_delete
FROM vp_role_page_permissions rp
LEFT JOIN vp_pages p ON rp.page_id = p.id
WHERE rp.role_id = 5 AND rp.page_id IN (12, 13)
ORDER BY rp.page_id;

-- Tüm roller özet
SELECT 'TÜM ROLLER PERMISSION ÖZETİ:' as result;
SELECT 
    r.id,
    r.role_name,
    r.display_name,
    COUNT(rp.id) as permission_count
FROM vp_roles r
LEFT JOIN vp_role_page_permissions rp ON r.id = rp.role_id
GROUP BY r.id, r.role_name, r.display_name
ORDER BY r.id;
