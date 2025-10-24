## 🔧 PRODUCTION'A UYGULANACAK DEĞİŞİKLİKLER

### 📌 Sorun
Rol İzinleri tablosunda "Ortaokul Etüt" ve "Lise Etüt" seçenekleri görünmüyor.

### ✅ Çözüm
Production veritabanında `vp_pages` tablosuna iki yeni sayfa eklenmesi gerekiyor:
1. **Ortaokul Etüt** (page_key: etut_ortaokul)
2. **Lise Etüt** (page_key: etut_lise)

### 🚀 Uygulama Adımları

#### Seçenek 1: PHP Script ile (ÖNERİLEN)

```bash
cd /portalv2
php scripts/add-etut-pages.php
```

#### Seçenek 2: Direkt SQL ile

phpMyAdmin veya terminal'de şu SQL komutlarını çalıştırın:

```sql
-- Önce parent sayfa ID'sini bulun
SELECT id FROM vp_pages WHERE page_key = 'etut_area';
-- Sonuç: 11 (veya farklı bir ID)

-- Sonra bu komutları çalıştırın (parent_id=11):
INSERT INTO vp_pages (page_key, page_name, page_url, parent_id, sort_order, is_active, etut_type, created_at) 
VALUES ('etut_ortaokul', 'Ortaokul Etüt', '/etut/ortaokul', 11, 1, 1, 'ortaokul', NOW());

INSERT INTO vp_pages (page_key, page_name, page_url, parent_id, sort_order, is_active, etut_type, created_at) 
VALUES ('etut_lise', 'Lise Etüt', '/etut/lise', 11, 2, 1, 'lise', NOW());
```

### ✔️ Kontrol Etme

Uygulama sonrası:

1. Admin Panel → Rol İzinleri → Ortaokul Müdür Yardımcısı rolüne tıklayın
2. Sayfalar listesinde şu öğeleri görüyor olmalısınız:
   - ✅ Ortaokul Etüt
   - ✅ Lise Etüt

3. Bu sayfalara karşılık izinler ayarlayabilirsiniz:
   - Görüntüleme (can_view)
   - Ekleme (can_create)
   - Düzenleme (can_edit)
   - Silme (can_delete)

### 🔗 Ilgili Dosyalar

- `scripts/add-etut-pages.php` - PHP script
- `database/add_etut_pages.sql` - SQL dump
- `ETUT_PAGES_SETUP.md` - Detaylı kılavuz

### 📝 Notlar

- Bu işlem güvenlidir (INSERT IGNORE kullanıyor, zaten varsa hata vermez)
- Yeni eklenen sayfalar varsayılan olarak aktif (`is_active = 1`)
- etut_type otomatik olarak ayarlanıyor:
  - etut_ortaokul → 'ortaokul'
  - etut_lise → 'lise'
