-- Ortaokul ve Lise Etüt Sayfaları Ekle
-- Bu komutlar production'da çalıştırılmalıdır

-- Önce bu ID'lerin var olup olmadığını kontrol et ve ekle
INSERT IGNORE INTO `vp_pages` (`id`, `page_key`, `page_name`, `page_url`, `parent_id`, `sort_order`, `is_active`, `etut_type`, `created_at`) VALUES
(11, 'etut_area', 'Etüt Alanı', '/etut', NULL, 6, 1, 'all', NOW());

-- Eğer ID 11 zaten varsa (farklı sayfa olabilir), ID 12 ve 13'ü kontrol et
INSERT IGNORE INTO `vp_pages` (`id`, `page_key`, `page_name`, `page_url`, `parent_id`, `sort_order`, `is_active`, `etut_type`, `created_at`) VALUES
(12, 'etut_ortaokul', 'Ortaokul Etüt', '/etut/ortaokul', 11, 1, 1, 'ortaokul', NOW()),
(13, 'etut_lise', 'Lise Etüt', '/etut/lise', 11, 2, 1, 'lise', NOW()),
(14, 'etut_settings', 'Etüt Form Ayarları', '/admin/etut-settings', 11, 3, 1, 'all', NOW());

-- Eğer bu ID'ler zaten kullanılıyorsa, page_key ile kontrol et ve ekle
INSERT INTO `vp_pages` (`page_key`, `page_name`, `page_url`, `parent_id`, `sort_order`, `is_active`, `etut_type`, `created_at`)
SELECT 'etut_ortaokul', 'Ortaokul Etüt', '/etut/ortaokul', (SELECT id FROM vp_pages WHERE page_key = 'etut_area' LIMIT 1), 1, 1, 'ortaokul', NOW()
WHERE NOT EXISTS (SELECT 1 FROM vp_pages WHERE page_key = 'etut_ortaokul');

INSERT INTO `vp_pages` (`page_key`, `page_name`, `page_url`, `parent_id`, `sort_order`, `is_active`, `etut_type`, `created_at`)
SELECT 'etut_lise', 'Lise Etüt', '/etut/lise', (SELECT id FROM vp_pages WHERE page_key = 'etut_area' LIMIT 1), 2, 1, 'lise', NOW()
WHERE NOT EXISTS (SELECT 1 FROM vp_pages WHERE page_key = 'etut_lise');
