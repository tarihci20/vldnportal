## 🔧 PRODUCTION'A UYGULANACAK DEĞİŞİKLİKLER

### 📌 Sorun
Rol İzinleri tablosunda "Ortaokul Etüt" ve "Lise Etüt" seçenekleri görünmüyor.

**Hata Mesajı:**
```
#1364 - Field 'id' doesn't have a default value
```

### ✅ Çözüm
1. `vp_pages` tablosunun `id` kolonunu AUTO_INCREMENT yapmalı
2. Yeni sayfaları eklemelidir

### 🚀 Uygulama Adımları

#### Seçenek 1: Hazır SQL Script ile (ÖNERİLEN)

phpMyAdmin veya MySQL terminal'de şu SQL komutlarını çalıştırın:

```sql
-- Adım 1: vp_pages tablosunun id kolonunu AUTO_INCREMENT olarak düzelt (MUTLAKA çalıştırın!)
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
```

#### Seçenek 2: SQL Dosya ile

```bash
mysql -h localhost -u vildacgg_tarihci20 -p vildacgg_portalv2 < database/add_etut_pages.sql
```

#### Seçenek 3: PHP Script ile

```bash
cd /portalv2
php scripts/add-etut-pages.php
```

### ⚠️ ÖNEMLİ NOTLAR

**Adım 1'i mutlaka çalıştırmalıdır!** Aksi takdirde INSERT işlemi başarısız olur.

```sql
-- MUTLAKA çalıştırın:
ALTER TABLE vp_pages 
MODIFY id int(11) NOT NULL AUTO_INCREMENT;
```

### ✔️ Kontrol Etme

Uygulama sonrası:

1. Admin Panel → Rol İzinleri → Ortaokul Müdür Yardımcısı rolüne tıklayın
2. Sayfalar listesinde şu öğeleri görüyor olmalısınız:
   - ✅ Ortaokul Etüt
   - ✅ Lise Etüt

3. Eğer hala görmüyorsanız, browser cache'ini temizleyin (Ctrl+Shift+Delete)

### 🔗 Ilgili Dosyalar

- `database/add_etut_pages.sql` - SQL script
- `database/fix_vp_pages_autoincrement.sql` - AUTO_INCREMENT fix
- `scripts/add-etut-pages.php` - PHP script
- `ETUT_PAGES_SETUP.md` - Detaylı kılavuz

### 📝 Notlar

- INSERT IGNORE kullanıyor, zaten varsa hata vermez
- Yeni eklenen sayfalar varsayılan olarak aktif (`is_active = 1`)
- etut_type otomatik olarak ayarlanıyor:
  - etut_ortaokul → 'ortaokul'
  - etut_lise → 'lise'
