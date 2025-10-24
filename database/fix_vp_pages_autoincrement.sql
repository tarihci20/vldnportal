-- PRODUCTION HATA FIX: vp_pages tablosuna AUTO_INCREMENT ekle

-- Adım 1: Mevcut verileri kontrol et
SELECT * FROM vp_pages ORDER BY id DESC LIMIT 1;

-- Adım 2: vp_pages tablosunun id kolonunu AUTO_INCREMENT olarak düzelt
ALTER TABLE vp_pages 
MODIFY id int(11) NOT NULL AUTO_INCREMENT;

-- Adım 3: Eğer id = 14 sonda ise, AUTO_INCREMENT başlangıç değerini ayarla
ALTER TABLE vp_pages AUTO_INCREMENT = 15;

-- Adım 4: Şimdi yeni sayfaları ekle (id otomatik atanacak)
INSERT INTO vp_pages (page_key, page_name, page_url, parent_id, sort_order, is_active, etut_type, created_at) 
VALUES 
('etut_ortaokul', 'Ortaokul Etüt', '/etut/ortaokul', 11, 1, 1, 'ortaokul', NOW()),
('etut_lise', 'Lise Etüt', '/etut/lise', 11, 2, 1, 'lise', NOW());

-- Adım 5: Kontrol et
SELECT id, page_key, page_name, etut_type FROM vp_pages WHERE page_key IN ('etut_ortaokul', 'etut_lise');

-- Adım 6: Tüm etut sayfalarını listele
SELECT id, page_key, page_name, parent_id, etut_type FROM vp_pages WHERE page_key LIKE 'etut%' ORDER BY id;
