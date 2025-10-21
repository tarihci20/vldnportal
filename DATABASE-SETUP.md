# 🗄️ Vildan Portal v2 - Database Setup Guide

## Sorun: "Table not found: vp_students"

Eğer aşağıdaki hatayı alıyorsanız:
```
SQLSTATE[42S02]: Base table or view not found: 1146 Table 'vildacgg_portalv2.vp_students' doesn't exist
```

Bu, veritabanında tablo oluşturulmadığı anlamına gelir.

---

## ✅ Çözüm: Database Schema'sı Import Etme

### Yöntem 1: cPanel phpmyadmin ile (Kolay)

1. **cPanel'e gir** → https://yourdomain.com:2083/
2. **MySQL Databases** bölümüne git
3. **Veritabanını seç** → "vildacgg_portalv2"
4. **phpmyadmin** aç
5. **SQL** tab'ına tıkla
6. `database/schema.sql` dosyasının içeriğini yapıştır
7. **GO** veya **Execute** butonuna tıkla

**Sonuç:** Tüm tablolar otomatik oluşturulacak, varsayılan roller ve sayfalar eklenecek.

---

### Yöntem 2: MySQL Command Line ile

SSH erişimi varsa:

```bash
# Production sunucuya bağlan
ssh username@yourdomain.com

# MySQL'de veritabanını seç ve schema'yı import et
mysql -u vildacgg_tarihci20 -p vildacgg_portalv2 < /home/yourusername/public_html/portalv2/database/schema.sql
```

---

### Yöntem 3: Dosyasız - Direktmen SQL

Eğer `schema.sql` dosyasının yanında klasörler varsa, phpmyadmin'de şu SQL'i çalıştır:

```sql
-- Aşağıdaki SQL bloğunu phpmyadmin SQL penceresine yapıştır
-- database/schema.sql dosyasında tamamen bulunur
```

> **NOT:** Tüm CREATE TABLE komutlarını `database/schema.sql`'den alıp yapıştırabilirsin.

---

## 📊 Oluşturulan Tablolar

Schema import edildikten sonra şu tablolar oluşturulacak:

| Tablo | Amaç | Önemli |
|-------|------|--------|
| `vp_students` | Öğrencileri sakla | Çekirdek |
| `vp_users` | Kullanıcıları sakla | Çekirdek |
| `vp_roles` | Rolleri tanımla (Admin, Öğretmen, vb) | Çekirdek |
| `vp_pages` | Sayfaları tanımla | İzin için |
| `vp_role_page_permissions` | Rol-sayfa izinleri | İzin için |
| `vp_activities` | Faaliyetleri sakla | Opsiyonel |
| `vp_activity_areas` | Faaliyet alanlarını sakla | ETÜT için |
| `vp_etut_applications` | ETÜT başvurularını sakla | ETÜT için |
| `vp_time_slots` | Zaman dilimlerini sakla | Takvim için |
| `vp_activity_area_time_slots` | Alan-zaman eşleştirmesi | ETÜT için |
| `vp_etut_form_settings` | ETÜT ayarlarını sakla | Ayarlar |
| `vp_push_subscriptions` | Push bildirim abonelikleri | PWA için |

---

## 🔐 Varsayılan Roller

Schema import edildikten sonra otomatik eklenir:

```
1. Admin (ID: 1)              - Tüm yetkilere sahip
2. Öğretmen (ID: 2)           - Sınırlı yetkiler
3. Sekreter (ID: 3)           - Orta seviye yetkiler
4. Okul Müdürü (ID: 4)        - Yüksek seviye yetkiler
5. Müdür Yardımcısı (ID: 5)   - Yüksek seviye yetkiler
```

---

## 👤 İlk Admin Kullanıcı Oluşturma

Schema import edildikten sonra, admin kullanıcı **manuel olarak oluşturmalısın**:

### Yöntem 1: phpmyadmin ile

1. phpmyadmin'de `vp_users` tablosuna git
2. **Insert** tab'ına tıkla
3. Şu bilgileri gir:

```sql
INSERT INTO `vp_users` (
    `username`, 
    `email`, 
    `password_hash`, 
    `full_name`, 
    `role_id`, 
    `can_change_password`, 
    `is_active`
) VALUES (
    'admin',
    'admin@yourdomain.com',
    '$2y$10$...hashlı_şifre...',  -- bcrypt hash
    'Yönetici',
    1,
    1,
    1
);
```

> **ÖNEMLİ:** Şifreyi bcrypt ile hash'le! Online tool: https://www.browserling.com/tools/bcrypt

### Yöntem 2: SQL Script ile

```bash
php -r "echo password_hash('your_password_here', PASSWORD_BCRYPT);"
```

Çıktıyı kopyala ve phpmyadmin'deki `password_hash` alanına yapıştır.

---

## ✅ Kontrol Listesi

Schema import ettikten sonra şunları doğrula:

- [ ] **vp_students** tablosu boş olarak oluşturuldu
- [ ] **vp_users** tablosu boş olarak oluşturuldu
- [ ] **vp_roles** tablosu 5 rol ile dolduruldu (Admin, Öğretmen, vb)
- [ ] **vp_pages** tablosu 7 sayfa ile dolduruldu (students, users, etut, vb)
- [ ] **Admin kullanıcı** oluşturuldu ve login olabiliyor
- [ ] Tarayıcıda öğrenci sayfasını açabiliyorsun
- [ ] Excel import test edildi

---

## 🚨 Sorun Gidericiler

### Hata: "Access denied for user"

```
Database bağlantı hatası: Access denied for user 'vildacgg_tarihci20'@'localhost'
```

**Çözüm:**
- `config/constants.php` dosyasındaki DB_USER, DB_PASS, DB_NAME kontrol et
- Hosting sağlayıcıdan doğru bilgileri iste

---

### Hata: "Table already exists"

Eğer tablolar zaten varsa ve schema import etmek istiyorsan:

```sql
-- Tüm tabloları sil (DİKKAT: Veri kaybı!)
DROP TABLE IF EXISTS `vp_push_subscriptions`;
DROP TABLE IF EXISTS `vp_etut_form_settings`;
DROP TABLE IF EXISTS `vp_etut_applications`;
DROP TABLE IF EXISTS `vp_activity_area_time_slots`;
DROP TABLE IF EXISTS `vp_time_slots`;
DROP TABLE IF EXISTS `vp_activities`;
DROP TABLE IF EXISTS `vp_activity_areas`;
DROP TABLE IF EXISTS `vp_role_page_permissions`;
DROP TABLE IF EXISTS `vp_pages`;
DROP TABLE IF EXISTS `vp_users`;
DROP TABLE IF EXISTS `vp_students`;
DROP TABLE IF EXISTS `vp_roles`;

-- Sonra schema.sql'i import et
```

---

### Hata: "Syntax Error in SQL"

Eğer SQL import ederken syntax hatası alırsan:

1. `database/schema.sql` dosyasını açmış mısın?
2. Tüm `SET` komutlarını dahil ettiniz mi?
3. Kodlama UTF-8 mi?

Yeni dene:
1. Dosyayı Notepad++'da aç
2. Format → UTF-8 without BOM seç
3. Tamamını seç ve kopyala
4. phpmyadmin'de yapıştır
5. Çalıştır

---

## 📝 Notlar

- **Prefix:** Tüm tablolar `vp_` prefixi ile başlar (Production ortamı için)
- **Charset:** UTF-8MB4 kullanılıyor (Türkçe karakterler için)
- **Collation:** utf8mb4_unicode_ci (Türkçe karşılaştırma için)
- **Timezone:** +03:00 (Türkiye Saati)

---

## 🔄 Gelecek İş

Tüm tabloları oluşturduktan sonra:

1. ✅ Admin kullanıcı oluştur
2. ✅ Admin panelinde oturum aç
3. ✅ Kullanıcıları yönet
4. ✅ Öğrencileri Excel ile import et
5. ✅ ETÜT başvurularını test et

---

## 📞 Yardım

Hala sorun yaşıyorsan:

1. **Error log'u kontrol et:** `/storage/logs/`
2. **Production config'i kontrol et:** `config/constants.php`
3. **Database bağlantısını test et:** Health check: `public/health.php`
4. **Logs'u gönder:** Hata mesajını screenshot ile birlikte paylaş

---

**Son Güncelleme:** 2024-10-21
**Versiyon:** Vildan Portal v2
