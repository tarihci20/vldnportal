# Rol ve İzin Yönetim Sistemi - Kullanım Kılavuzu

## 📋 Genel Bakış

Bu sistem, Vildan Portal'da kullanıcı rollerini ve sayfa bazlı izinleri yönetmek için geliştirilmiştir.

## 🎭 Roller

Sistemde 5 farklı rol bulunmaktadır:

1. **Admin** (ID: 1) - Tüm yetkilere sahip
2. **Öğretmen** (ID: 2) - Sınırlı yetkiler
3. **Sekreter** (ID: 3) - Orta seviye yetkiler
4. **Okul Müdürü** (ID: 4) - Yüksek seviye yetkiler
5. **Müdür Yardımcısı** (ID: 5) - Yüksek seviye yetkiler

## 🔑 İzin Tipleri

Her sayfa için 4 farklı izin tipi vardır:

- **can_view** - Görüntüleme izni
- **can_create** - Oluşturma izni
- **can_edit** - Düzenleme izni
- **can_delete** - Silme izni

## 📂 Dosya Yapısı

```
app/
├── models/
│   └── Role.php                           # Rol modeli
├── controllers/
│   └── AdminController.php                # Admin işlemleri
├── middleware/
│   └── PermissionMiddleware.php          # İzin kontrolü
├── helpers/
│   └── session.php                        # hasPermission(), canChangePassword()
└── views/
    ├── admin/
    │   ├── users/
    │   │   ├── index.php                  # Kullanıcı listesi
    │   │   ├── create.php                 # Yeni kullanıcı
    │   │   └── edit.php                   # Kullanıcı düzenle
    │   └── roles/
    │       └── index.php                  # Rol izinleri yönetimi
    └── profile/
        └── index.php                      # Kullanıcı profili
```

## 🚀 Kullanım Örnekleri

### 1. Controller'da İzin Kontrolü

```php
use App\Middleware\PermissionMiddleware;

class StudentController extends Controller
{
    public function index() {
        // Görüntüleme izni kontrolü
        PermissionMiddleware::canView('students');
        
        // Listeleme işlemi...
    }
    
    public function create() {
        // Oluşturma izni kontrolü
        PermissionMiddleware::canCreate('students');
        
        // Ekleme formu...
    }
    
    public function store() {
        // Oluşturma izni kontrolü
        PermissionMiddleware::canCreate('students');
        
        // Kaydetme işlemi...
    }
    
    public function edit($id) {
        // Düzenleme izni kontrolü
        PermissionMiddleware::canEdit('students');
        
        // Düzenleme formu...
    }
    
    public function delete($id) {
        // Silme izni kontrolü
        PermissionMiddleware::canDelete('students');
        
        // Silme işlemi...
    }
}
```

### 2. View'da İzin Kontrolü

```php
<!-- Düzenleme butonu sadece izinli kullanıcılara göster -->
<?php if (hasPermission('students', 'can_edit')): ?>
    <a href="/students/<?= $student['id'] ?>/edit">Düzenle</a>
<?php endif; ?>

<!-- Silme butonu sadece izinli kullanıcılara göster -->
<?php if (hasPermission('students', 'can_delete')): ?>
    <button onclick="deleteStudent(<?= $student['id'] ?>)">Sil</button>
<?php endif; ?>

<!-- Yeni ekle butonu sadece izinli kullanıcılara göster -->
<?php if (hasPermission('students', 'can_create')): ?>
    <a href="/students/create">Yeni Öğrenci Ekle</a>
<?php endif; ?>
```

### 3. Helper Fonksiyonlar

```php
// İzin kontrolü
if (hasPermission('students', 'can_view')) {
    // Öğrencileri göster
}

// Şifre değiştirme izni
if (canChangePassword()) {
    // Şifre değiştirme formu göster
}

// Kullanıcı adı değiştirme izni
if (canChangeUsername()) {
    // Kullanıcı adı alanını aktif et
}

// Rol kontrolleri
if (isAdmin()) {
    // Admin işlemleri
}

if (isTeacher()) {
    // Öğretmen işlemleri
}
```

### 4. Model Kullanımı

```php
use App\Models\Role;

$roleModel = new Role();

// Tüm rolleri getir
$roles = $roleModel->getAllRoles();

// Belirli bir rolün izinlerini getir
$permissions = $roleModel->getPermissionsByRoleId(2); // Öğretmen

// İzin kontrolü
$canEdit = $roleModel->checkPermission(2, 'students', 'can_edit');

// İzinleri güncelle
$permissionsArray = [
    1 => [  // Sayfa ID
        'can_view' => 1,
        'can_create' => 0,
        'can_edit' => 0,
        'can_delete' => 0
    ],
    // ...
];
$roleModel->updateRolePermissions(2, $permissionsArray);
```

## 🎯 Admin Panel Kullanımı

### Kullanıcı Yönetimi

1. **Kullanıcı Listesi**: `/admin/users`
   - Tüm kullanıcıları görüntüle
   - Filtreleme ve arama (DataTables)
   - Düzenle/Sil işlemleri

2. **Yeni Kullanıcı Ekle**: `/admin/users/create`
   - Kullanıcı bilgilerini gir
   - Rol seç
   - "Şifre değiştirebilir" seçeneği

3. **Kullanıcı Düzenle**: `/admin/users/{id}/edit`
   - Bilgileri güncelle
   - Rol değiştir
   - Şifre değiştirme iznini ayarla

### Rol İzinleri Yönetimi

1. **Rol İzinleri**: `/admin/roles`
   - Dropdown'dan rol seç
   - Her sayfa için izinleri checkbox'larla ayarla
   - "Hepsini seç" butonu ile toplu işlem
   - Kaydet

## ⚠️ Önemli Notlar

### Öğretmen Kısıtlamaları

- ✅ Öğretmenler kendi profillerini görüntüleyebilir
- ❌ Öğretmenler kullanıcı adlarını **değiştiremez**
- ❌ Öğretmenler şifrelerini **değiştiremez** (can_change_password = 0 ise)
- ℹ️ Profil sayfasında bilgilendirme mesajı gösterilir

### Admin Ayrıcalıkları

- Admin rolü **her zaman** tüm izinlere sahiptir
- İzin kontrollerinde admin bypass edilir
- Admin kendi hesabını **silemez**

### Güvenlik

- CSRF token kontrolü tüm POST işlemlerinde zorunlu
- İzin kontrolleri middleware katmanında yapılır
- Yetkisiz erişim denemeleri loglanır

## 📊 Veritabanı Tabloları

### roles
```sql
- id
- role_name (admin, teacher, secretary, principal, vice_principal)
- display_name (Admin, Öğretmen, Sekreter, Okul Müdürü, Müdür Yardımcısı)
- description
```

### users
```sql
- id
- username
- email
- password_hash
- full_name
- role_id (FK -> roles.id)
- can_change_password (0 veya 1)
- is_active
```

### role_page_permissions
```sql
- id
- role_id (FK -> roles.id)
- page_id (FK -> pages.id)
- can_view
- can_create
- can_edit
- can_delete
```

### pages
```sql
- id
- page_name
- page_slug (students, users, etut, vb.)
- display_name
- is_active
```

## 🔧 Yeni Sayfa Ekleme

1. `pages` tablosuna yeni kayıt ekle:
```sql
INSERT INTO pages (page_name, page_slug, display_name, is_active) 
VALUES ('Veli Toplantıları', 'parent-meetings', 'Veli Toplantıları', 1);
```

2. Controller'da izin kontrolü ekle:
```php
PermissionMiddleware::canView('parent-meetings');
```

3. View'da buton kontrolü:
```php
<?php if (hasPermission('parent-meetings', 'can_create')): ?>
    <a href="/parent-meetings/create">Yeni Toplantı</a>
<?php endif; ?>
```

4. Admin panelden roller için izinleri ayarla

## ✅ Test Checklist

- [ ] Admin tüm sayfalara erişebiliyor
- [ ] Öğretmen sadece izinli sayfalara erişebiliyor
- [ ] Öğretmen şifre değiştiremiyor
- [ ] Öğretmen kullanıcı adı değiştiremiyor
- [ ] Rol izinleri kaydediliyor
- [ ] Kullanıcı ekleme/düzenleme/silme çalışıyor
- [ ] DataTables arama ve filtreleme çalışıyor
- [ ] CSRF koruması aktif
- [ ] Flash mesajlar gösteriliyor

## 📝 Gelecek Geliştirmeler

- [ ] Rol tabanlı sidebar menü gösterimi
- [ ] İzin bazlı route koruma
- [ ] Aktivite log detaylandırma
- [ ] Toplu kullanıcı import (Excel)
- [ ] Rol kopyalama özelliği
- [ ] İzin şablonları (preset)
