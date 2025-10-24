# Ortaokul ve Lise Etüt Sayfaları Ekleme Kılavuzu

Eğer rol izinleri tablosunda "Ortaokul Etüt" ve "Lise Etüt" seçeneklerini görmüyorsanız, veritabanınıza bu sayfaları eklemeniz gerekir.

## Production'a Sayfaları Ekleme

### Yöntem 1: PHP Script ile (Önerilen)

```bash
cd portalv2/
php scripts/add-etut-pages.php
```

Bu script:
- Veritabanına bağlanır
- Parent sayfa (Etüt Alanı) ID'sini bulur
- Ortaokul Etüt ve Lise Etüt sayfalarını ekler
- Sonucu gösterir

### Yöntem 2: SQL Dump ile

Eğer script çalışmazsa, direkt SQL komutu çalıştırabilirsiniz:

```sql
-- Önce Etüt Alanı (parent) sayfasının ID'sini bulun:
SELECT id FROM vp_pages WHERE page_key = 'etut_area';

-- Sonra bu komutları çalıştırın (parent_id'yi yukarıdaki ID ile değiştirin):
INSERT INTO vp_pages (page_key, page_name, page_url, parent_id, sort_order, is_active, etut_type, created_at) 
VALUES ('etut_ortaokul', 'Ortaokul Etüt', '/etut/ortaokul', 11, 1, 1, 'ortaokul', NOW());

INSERT INTO vp_pages (page_key, page_name, page_url, parent_id, sort_order, is_active, etut_type, created_at) 
VALUES ('etut_lise', 'Lise Etüt', '/etut/lise', 11, 2, 1, 'lise', NOW());
```

### Yöntem 3: SQL Dosya ile

```bash
mysql -h localhost -u vildacgg_tarihci20 -p vildacgg_portalv2 < database/add_etut_pages.sql
```

## Kontrol Etme

Sayfalar eklendiğinde, Admin Panel → Rol İzinleri → Ortaokul Müdür Yardımcısı bölümünde şu sayfaları göreceksiniz:

- ✅ Ortaokul Etüt (ortaokul)
- ✅ Lise Etüt (lise)

Bu sayfalara karşılık gelen checkboxları işaretleyerek izinleri atayabilirsiniz.

## Sorun Giderme

**Hala görünmüyorsa:**

1. Veritabanında sayfalara bakın:
```sql
SELECT id, page_key, page_name, etut_type FROM vp_pages WHERE page_key LIKE 'etut_%';
```

2. Sayfaların parent_id'sinin doğru olduğunu kontrol edin
3. Browser cache'ini temizleyin (Ctrl+Shift+Delete)
4. Production sayfasını yenileyin
