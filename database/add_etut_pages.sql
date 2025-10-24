-- Ortaokul ve Lise Etüt Sayfaları Ekle
-- Bu komutlar production'da çalıştırılmalıdır

-- Adım 1: vp_pages tablosunun id kolonunu AUTO_INCREMENT olarak düzelt
ALTER TABLE vp_pages 
MODIFY id int(11) NOT NULL AUTO_INCREMENT;

-- Adım 2: Eğer son id = 14 ise, AUTO_INCREMENT başlangıç değerini ayarla
ALTER TABLE vp_pages AUTO_INCREMENT = 15;

-- Adım 3: Ortaokul Etüt sayfasını ekle
INSERT IGNORE INTO vp_pages (page_key, page_name, page_url, parent_id, sort_order, is_active, etut_type, created_at) 
VALUES ('etut_ortaokul', 'Ortaokul Etüt', '/etut/ortaokul', 11, 1, 1, 'ortaokul', NOW());

-- Adım 4: Lise Etüt sayfasını ekle
INSERT IGNORE INTO vp_pages (page_key, page_name, page_url, parent_id, sort_order, is_active, etut_type, created_at) 
VALUES ('etut_lise', 'Lise Etüt', '/etut/lise', 11, 2, 1, 'lise', NOW());

-- Adım 5: Kontrol et - eklenen sayfaları göster
SELECT id, page_key, page_name, etut_type FROM vp_pages WHERE page_key IN ('etut_ortaokul', 'etut_lise');
