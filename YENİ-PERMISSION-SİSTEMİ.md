# YENİ PERMISSION SİSTEMİ - TASARIM DOKÜMANI

## 🎯 Hedef
Basit, güvenilir, tüm kullanıcılar için çalışan bir permission sistemi.

## ❌ Mevcut Sistemin Sorunları

1. **Duplicate page_key'ler**: Aynı sayfa birden fazla kez tanımlanmış
2. **İsimlendirme karmaşası**: `student-search` vs `student_search` (tire vs alt çizgi)
3. **Permission kontrolü tutarsız**: Bazı yerlerde role check, bazı yerlerde permission check
4. **Debugging zor**: Hangi katmanda sorun var anlamak çok zaman alıyor

## ✅ Yeni Sistem - Basit Prensipler

### 1. TEK SAYFA = TEK PAGE_KEY
```sql
-- ÖNCEKİ (KARMAŞIK):
page_key = 'students' (id: 3)
page_key = 'students' (id: 17) ← DUPLICATE!

-- YENİ (BASİT):
page_key = 'students' (id: 3) ← Sadece 1 tane!
```

### 2. PAGE_KEY STANDARDI
```
Kural: Sadece küçük harf + tire (-)
Örnekler:
  ✅ student-search
  ✅ etut-ortaokul
  ✅ activity-areas
  
  ❌ student_search (alt çizgi YASAK)
  ❌ StudentSearch (büyük harf YASAK)
```

### 3. SIDEBAR = DATABASE
```php
// ESKI (KARMAŞIK):
<?php if (in_array($role, ['admin', 'mudur'])): ?>
<?php if (hasPermission('something')): ?>

// YENİ (BASİT):
<?php if (hasPermission('page-key', 'can_view')): ?>
```

HER MENÜ İTEMİ → Sadece hasPermission() kullanır.

### 4. PERMISSION HIERARCHY
```
- can_view: Sayfayı görebilir mi?
- can_create: Yeni kayıt oluşturabilir mi?
- can_edit: Mevcut kaydı düzenleyebilir mi?
- can_delete: Kaydı silebilir mi?
```

Kural: `can_view = 0` ise → Diğer yetkiler önemsiz (sayfa görünmez)

## 🏗️ Database Yapısı

### vp_pages (MASTER TABLE)
```sql
CREATE TABLE vp_pages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    page_key VARCHAR(50) UNIQUE NOT NULL,  -- ← UNIQUE constraint
    page_name VARCHAR(100) NOT NULL,
    page_url VARCHAR(255) NOT NULL,
    category VARCHAR(50) DEFAULT 'general', -- sidebar kategori
    icon VARCHAR(50) DEFAULT 'fas fa-circle', -- Font Awesome icon
    is_active TINYINT(1) DEFAULT 1,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### vp_role_page_permissions (JUNCTION TABLE)
```sql
CREATE TABLE vp_role_page_permissions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    role_id INT NOT NULL,
    page_id INT NOT NULL,
    can_view TINYINT(1) DEFAULT 0,
    can_create TINYINT(1) DEFAULT 0,
    can_edit TINYINT(1) DEFAULT 0,
    can_delete TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_role_page (role_id, page_id), -- ← HER ROLE+PAGE ÇİFTİ 1 KEZ!
    FOREIGN KEY (role_id) REFERENCES vp_roles(id) ON DELETE CASCADE,
    FOREIGN KEY (page_id) REFERENCES vp_pages(id) ON DELETE CASCADE
);
```

## 📋 Sayfa Listesi (FINAL)

| Category | page_key | page_name | page_url | icon |
|----------|----------|-----------|----------|------|
| main | dashboard | Ana Sayfa | /dashboard | fas fa-home |
| students | student-search | Öğrenci Ara | /student-search | fas fa-search |
| students | students | Öğrenci Bilgileri | /students | fas fa-user-graduate |
| activities | activities | Etkinlikler | /activities | fas fa-calendar-alt |
| activities | activity-areas | Etkinlik Alanları | /activity-areas | fas fa-map-marker-alt |
| etut | etut-ortaokul | Ortaokul Etüt | /etut/ortaokul | fas fa-school |
| etut | etut-lise | Lise Etüt | /etut/lise | fas fa-graduation-cap |
| reports | reports | Raporlar | /reports | fas fa-chart-bar |
| admin | users | Kullanıcılar | /admin/users | fas fa-users |
| admin | roles | Rol İzinleri | /admin/roles | fas fa-shield-alt |
| admin | settings | Sistem Ayarları | /admin/settings | fas fa-cog |

**TOPLAM: 11 sayfa** (Temiz, duplicate yok)

## 🔧 hasPermission() Fonksiyonu

```php
/**
 * Kullanıcının belirli bir sayfaya erişim yetkisi var mı?
 * 
 * @param string $pageKey vp_pages.page_key
 * @param string $permissionType can_view|can_create|can_edit|can_delete
 * @return bool
 */
function hasPermission($pageKey, $permissionType = 'can_view') {
    // Session'da user var mı?
    if (!isset($_SESSION['user_id'])) {
        return false;
    }
    
    $userId = $_SESSION['user_id'];
    $roleId = $_SESSION['role_id'] ?? null;
    
    if (!$roleId) {
        return false;
    }
    
    // Admin her zaman tam yetki
    if ($_SESSION['role_name'] === 'admin') {
        return true;
    }
    
    // Database'den kontrol
    $db = Database::getInstance();
    
    $sql = "SELECT rpp.{$permissionType}
            FROM vp_role_page_permissions rpp
            JOIN vp_pages p ON p.id = rpp.page_id
            WHERE rpp.role_id = :role_id 
            AND p.page_key = :page_key
            AND p.is_active = 1
            LIMIT 1";
    
    $db->query($sql);
    $db->bind(':role_id', $roleId);
    $db->bind(':page_key', $pageKey);
    
    $result = $db->single();
    
    return ($result && $result->{$permissionType} == 1);
}
```

## 🚀 Deployment Plan

### ADIM 1: Database Temizliği
1. Duplicate page_key'leri tespit et
2. Eski permission kayıtlarını yedekle
3. Tabloları temizle

### ADIM 2: Yeni Schema
1. vp_pages tablosunu yeniden oluştur (UNIQUE constraint ile)
2. vp_role_page_permissions tablosunu yeniden oluştur (UNIQUE constraint ile)
3. 11 sayfa kaydını INSERT et

### ADIM 3: Default Permissions
1. Admin role → Tüm sayfalara FULL ACCESS
2. Principal (Müdür) → Tüm sayfalara FULL ACCESS
3. Vice Principal → Configurable (admin panelden ayarlanır)
4. Secretary → Configurable
5. Teacher → Sadece student-search (VIEW only)

### ADIM 4: Code Refactor
1. hasPermission() fonksiyonunu güncelle
2. Sidebar'ı basitleştir (sadece hasPermission kullan)
3. Tüm controller'larda PermissionMiddleware kullan

### ADIM 5: Test
1. Her role ile login ol
2. Sidebar menülerin doğru görüntülendiğini kontrol et
3. Sayfalara erişim kontrolünü test et

## ⚠️ Risk Analizi

| Risk | Olasılık | Etki | Çözüm |
|------|----------|------|-------|
| Data loss | Düşük | Yüksek | Backup zorunlu |
| Downtime | Orta | Orta | Gece deployment |
| User confusion | Düşük | Düşük | Role'ler değişmeyecek |
| Permission hatası | Orta | Yüksek | Test kullanıcıları ile test |

## 📅 Zaman Çizelgesi

- **Backup**: 15 dakika
- **Schema rebuild**: 10 dakika
- **Data migration**: 20 dakika
- **Code update**: 30 dakika
- **Testing**: 30 dakika

**TOPLAM**: ~2 saat

## ✅ Onay Gerektiren Kararlar

1. **Eski permission kayıtlarını silmek** → Backup'tan restore edilebilir
2. **Production'da doğrudan deployment** → Test ortamı yok
3. **Tüm kullanıcılar logout olacak** → Session temizlenmeli

---

**ONAY VERİRSENİZ BAŞLIYORUZ! 🚀**
