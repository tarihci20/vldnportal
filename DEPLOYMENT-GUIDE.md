# Production Deployment Guide - Database Prefix Fix

## Overview
Bu rehber, Vildan Portal v2 uygulamasının production ortamında doğru şekilde çalışmasını sağlamak için database schema'nın kurulumu ve configuration adımlarını içerir.

**Problem Çözüldü:** Önceki versiyonda database table prefix `vp_` uygulanmıyordu. Bu, production ortamında tabloların bulunamamasına neden oluyordu.

**Çözüm:** Model base class'ı tablonun adına otomatik olarak prefix ekleyecek şekilde konfigüre edildi.

---

## 1. Database Schema Kurulumu (ONE-TIME SETUP)

### Adım 1: Database'e Erişim
1. **cPanel'e gir** → `phpMyAdmin` aç
2. **Sol taraftan** database seç: `vildacgg_portalv2`
3. Veya mevcut database'i seç (admin'e sor)

### Adım 2: Schema Import
1. **"SQL" tab'ını tıkla** (phpMyAdmin üst menüde)
2. **`database/schema.sql`** dosyasının içeriğini kopyala:
   ```
   Proje Klasörü > database > schema.sql
   ```
3. **SQL editöre yapıştır** ve **"Go" butonunu tıkla**

### Adım 3: Doğrulama
Şu 12 tabelonun oluştuğunu kontrol et:
- ✅ `vp_roles`
- ✅ `vp_users`
- ✅ `vp_students`
- ✅ `vp_activities`
- ✅ `vp_activity_areas`
- ✅ `vp_time_slots`
- ✅ `vp_activity_area_time_slots`
- ✅ `vp_etut_applications`
- ✅ `vp_etut_form_settings`
- ✅ `vp_pages`
- ✅ `vp_role_page_permissions`
- ✅ `vp_push_subscriptions`

**Tablolar phpMyAdmin'de sol tarafta listelenecek.**

---

## 2. Configuration Kontrolü

### Adım 1: `config/constants.php` Kontrol Et
Production ortamında şu değerlerin doğru olduğunu doğrula:

```php
define('DB_HOST', 'localhost');           // Production sunucunun değeri
define('DB_NAME', 'vildacgg_portalv2');   // Database adı (cPanel'de gösterilen)
define('DB_USER', 'vildacgg_tarihci20');  // Database kullanıcısı (cPanel'de gösterilen)
define('DB_PASS', 'PASSWORD_HERE');       // Database şifresi (cPanel'de gösterilen)
define('DB_PREFIX', 'vp_');               // MUST BE 'vp_' - DON'T CHANGE
```

**İlk Admin Kullanıcı Oluşturmak İçin** (gerekirse):
```sql
INSERT INTO vp_users (name, email, password, role_id, created_at) 
VALUES ('Admin', 'admin@example.com', '$2y$10$...', 1, NOW());
```

### Adım 2: `config/database.php` Kontrol Et
Dosya şu bağlantı parametrelerini içermeli:
```php
'host'     => defined('DB_HOST') ? DB_HOST : 'localhost',
'database' => defined('DB_NAME') ? DB_NAME : 'vildacgg_portalv2',
'username' => defined('DB_USER') ? DB_USER : 'root',
'password' => defined('DB_PASS') ? DB_PASS : '',
'charset'  => 'utf8mb4',
'prefix'   => defined('DB_PREFIX') ? DB_PREFIX : 'vp_',
```

---

## 3. Test İşlemleri

### Test 1: Database Bağlantısı
Production sunucusunda bir test dosyası oluştur:
```php
<?php
require_once 'core/Database.php';
$db = \Database::getInstance();
$result = $db->select('users', ['*'], 1);
echo $result ? "✅ Bağlantı başarılı" : "❌ Bağlantı başarısız";
?>
```

### Test 2: Excel Import
**Adım 1:** Mevcut 659 öğrenci Excel dosyasını hazırla
**Adım 2:** Admin panelinden import et
**Adım 3:** Öğrencilerin `vp_students` tablosunda göründüğünü kontrol et:
```sql
SELECT COUNT(*) FROM vp_students;  -- Should be 659
```

### Test 3: Sistem İşlemleri
- ✅ Kullanıcı girişi (login)
- ✅ Etkinlik alanları listesi
- ✅ Öğrenci listesi
- ✅ Rol ve izin kontrolü

---

## 4. Troubleshooting

### Sorun: "Table 'vp_students' doesn't exist"
**Çözüm:** 
1. `database/schema.sql` import edildikten sonra tablolar listelenmiş mi? (Adım 1.3'ü kontrol et)
2. Database adı doğru mu? (`vildacgg_portalv2`)
3. Table prefix doğru mu? (`vp_` olmalı)

```sql
-- phpMyAdmin'de çalıştır:
SHOW TABLES LIKE 'vp_%';
```

### Sorun: "Access denied for user"
**Çözüm:**
1. `config/constants.php`'da kullanıcı adı ve şifre doğru mu?
2. cPanel'de database kullanıcısı bu database için yetkilendirilmiş mi?

```
cPanel > MySQL Databases > Add User to Database
```

### Sorun: "Too many connections"
**Çözüm:** 
- Database `max_connections` limitini kontrol et
- Eski bağlantıları kapat
- Hosting provider'a ilet

---

## 5. Technical Details (Developers)

### How Table Prefixing Works
1. **Model base class** (`core/Model.php`) constructor'ında:
   ```php
   public function __construct() {
       if (!empty($this->table)) {
           $this->table = $this->getTableName();
       }
   }
   ```

2. **getTableName()** method, `vp_` prefix'i otomatik ekler:
   ```php
   protected function getTableName() {
       $prefix = \DB_PREFIX ?? 'vp_';
       $table = $this->table;
       if (!empty($table) && strpos($table, $prefix) === 0) {
           return $table; // Already prefixed
       }
       return !empty($table) ? $prefix . $table : null;
   }
   ```

3. **Tüm child models** (`Student`, `User`, `Role`, vb.):
   ```php
   class Student extends Model {
       protected $table = 'students';  // Constructor bunu 'vp_students'e dönüştürür
   }
   ```

### Database Architecture
- **Database.php**: PDO singleton wrapper (table prefixing YAPMAZ)
- **Model.php**: Base class, table prefix'i kendisi ekler
- **Child Models**: `$table` property'si unprefixed halde başlar, constructor'da prefixed olur

### Property Initialization Order
PHP, child class properties'lerini parent constructor'dan ÖNCE initialize eder:
```
1. Child::$table = 'students' (initialized)
2. Parent::__construct() called
3. Inside parent constructor: $this->table = 'vp_students'
```

---

## 6. Deployment Checklist

- [ ] `database/schema.sql` import edildi (12 tablo oluşturuldu)
- [ ] `config/constants.php` production değerleriyle konfigüre edildi
- [ ] `config/database.php` doğru prefix'i tanımlıyor (`vp_`)
- [ ] Database bağlantısı test edildi
- [ ] Tüm 12 table phpMyAdmin'de görünüyor
- [ ] Excel import testi başarılı oldu
- [ ] Sistem fonksiyonları test edildi
- [ ] Hata log'ları kontrol edildi (`storage/logs/`)

---

## 7. Support & Questions

**Sorunlarla karşılaşırsan:**
1. `storage/logs/` klasöründeki hata log'larını kontrol et
2. `public/health.php` çalıştır (varsa health check yap)
3. Hosting provider'a database ayarlarını sorgulaması tavsiye et

---

**Son Güncelleme:** 2024
**Versiyon:** 2.0 (Database Prefix Fixed)
