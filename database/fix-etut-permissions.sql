-- Add missing permissions for vice_principal (Role 5)
-- For pages: 11 (Etüt Form Ayarları), 12 (Ortaokul Etüt), 13 (Lise Etüt)

INSERT INTO vp_role_page_permissions 
(role_id, page_id, can_view, can_create, can_edit, can_delete, created_at, updated_at) 
VALUES 
-- Page 11: Etüt Form Ayarları
(5, 11, 1, 1, 1, 1, NOW(), NOW()),
-- Page 12: Ortaokul Etüt Başvuruları
(5, 12, 1, 1, 1, 1, NOW(), NOW()),
-- Page 13: Lise Etüt Başvuruları
(5, 13, 1, 1, 1, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE 
    can_view = 1,
    can_create = 1, 
    can_edit = 1,
    can_delete = 1,
    updated_at = NOW();

-- Verify the changes
SELECT 
    p.id,
    p.page_name,
    rp.can_view,
    rp.can_create,
    rp.can_edit,
    rp.can_delete
FROM vp_pages p
LEFT JOIN vp_role_page_permissions rp ON p.id = rp.page_id AND rp.role_id = 5
WHERE p.id IN (11, 12, 13)
ORDER BY p.id;
