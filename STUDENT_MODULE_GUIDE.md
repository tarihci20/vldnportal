# 👥 Vildan Portal - Öğrenci Modülü Detaylı Kılavuzu

## 📋 İçindekiler
1. [Modül Yapısı](#modül-yapısı)
2. [Veritabanı Şeması](#veritabanı-şeması)
3. [Tüm Operasyonlar](#tüm-operasyonlar)
4. [Excel İşlemleri](#excel-işlemleri)
5. [Hata Çözümleri](#hata-çözümleri)
6. [Geliştirme Notları](#geliştirme-notları)

---

## 🏗️ Modül Yapısı

```
app/
├─ Controllers/
│  └─ StudentController.php
│     ├─ __construct()           ← Auth check
│     ├─ index()                 ← List students
│     ├─ search()                ← Search page
│     ├─ ajaxSearch()            ← AJAX search
│     ├─ detail($id)             ← Show detail
│     ├─ create()                ← Show form
│     ├─ store()                 ← Save to DB ✨
│     ├─ edit($id)               ← Edit form
│     ├─ update($id)             ← Update DB
│     ├─ delete($id)             ← Delete
│     ├─ exportExcel()           ← Export
│     ├─ downloadTemplate()      ← Download template
│     └─ importExcel()           ← Import
│
├─ Models/
│  └─ Student.php
│     ├─ getAll()                ← Paginated list
│     ├─ search()                ← Search
│     ├─ findById($id)           ← Get one
│     ├─ findByTc($tc)           ← Get by TC
│     ├─ create($data)           ← Insert
│     ├─ update($id, $data)      ← Update
│     ├─ delete($id)             ← Soft delete
│     ├─ hardDelete($id)         ← Hard delete
│     ├─ isTcExists($tc)         ← Check TC unique
│     ├─ getUniqueClasses()      ← Get all classes
│     ├─ getAllForExport()       ← Export all
│     └─ deleteAll()             ← Delete all
│
├─ Views/
│  └─ students/
│     ├─ index.php               ← List view
│     ├─ create.php              ← Add form ✨
│     ├─ edit.php                ← Edit form
│     ├─ detail.php              ← Detail view
│     └─ search.php              ← Search page
│
└─ Helpers/
   └─ excel.php
      ├─ exportStudentsToExcel()
      ├─ createStudentExcelTemplate()
      ├─ importStudentsFromExcel()
      └─ validateExcelFile()
```

---

## 📊 Veritabanı Şeması

### **Students Table**

```sql
CREATE TABLE `students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tc_no` varchar(11) COLLATE utf8mb4_unicode_ci,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birth_date` date,
  `class` varchar(50) COLLATE utf8mb4_unicode_ci,
  `address` text COLLATE utf8mb4_unicode_ci,
  `father_name` varchar(100) COLLATE utf8mb4_unicode_ci,
  `father_phone` varchar(11) COLLATE utf8mb4_unicode_ci,
  `mother_name` varchar(100) COLLATE utf8mb4_unicode_ci,
  `mother_phone` varchar(11) COLLATE utf8mb4_unicode_ci,
  `teacher_name` varchar(100) COLLATE utf8mb4_unicode_ci,
  `teacher_phone` varchar(11) COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) DEFAULT 1,
  `created_by` int(11),
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  PRIMARY KEY (`id`),
  UNIQUE KEY `tc_no` (`tc_no`),
  KEY `class` (`class`),
  KEY `first_name` (`first_name`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### **Indexes Explanation**

| Index | Purpose | Usage |
|-------|---------|-------|
| `id` (PRIMARY) | Unique ID | Fast lookups |
| `tc_no` (UNIQUE) | Prevent duplicates | TC validation |
| `class` | Filtering | Class-based search |
| `first_name` | Search | Name search |
| `created_at` | Sorting | Recent students |

---

## 🔄 Tüm Operasyonlar

### **1. Öğrenci Listesi (LIST)**

**Route:** `GET /students`

```php
// Controller
public function index() {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $class = isset($_GET['class']) ? trim($_GET['class']) : '';
    
    $result = $this->studentModel->getAll($page, 50, [
        'search' => $search,
        'class' => $class
    ]);
    
    $this->view('students/index', [
        'students' => $result['data'],
        'pagination' => $result['pagination'],
        'classes' => $this->studentModel->getUniqueClasses(),
        'search' => $search,
        'classFilter' => $class
    ]);
}

// Model query
SELECT * FROM students 
WHERE is_active = 1
  AND (first_name LIKE '%search%' 
    OR last_name LIKE '%search%' 
    OR tc_no LIKE '%search%')
  AND class = 'filter'
ORDER BY first_name, last_name
LIMIT 50 OFFSET 0;
```

### **2. Öğrenci Ayrıntısı (DETAIL)**

**Route:** `GET /students/{id}`

```php
// Controller
public function detail($id) {
    $student = $this->studentModel->findById($id);
    
    if (!$student) {
        setFlashMessage('Öğrenci bulunamadı.', 'error');
        redirect('/students');
    }
    
    $this->view('students/detail', [
        'student' => $student,
        'title' => $student['first_name'] . ' ' . $student['last_name']
    ]);
}

// Model query
SELECT * FROM students WHERE id = ?;
```

### **3. Öğrenci Ekle Form (CREATE FORM)** ✨

**Route:** `GET /students/create`

```php
// Controller
public function create() {
    $this->view('students/create', [
        'title' => 'Yeni Öğrenci Ekle'
    ]);
}

// View: app/views/students/create.php
// Form alanları:
- TC Kimlik No (required, 11 digits, unique)
- İsim (required)
- Soyisim (required)
- Doğum Tarihi (required)
- Sınıf (required)
- Adres (optional)
- Baba: İsim, Telefon (optional)
- Anne: İsim, Telefon (optional)
- Öğretmen: İsim, Telefon (optional)
- Notlar (optional)
```

### **4. Öğrenci Kaydet (STORE)** ✨

**Route:** `POST /students/store`

```php
// Controller
public function store() {
    // 1. CSRF Validation
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        setFlashMessage('Geçersiz form token.', 'error');
        redirect('/students/create');
    }
    
    // 2. Data Sanitization
    $data = [
        'tc_no' => cleanTcNo($_POST['tc_no'] ?? ''),
        'first_name' => cleanName($_POST['first_name'] ?? ''),
        'last_name' => cleanName($_POST['last_name'] ?? ''),
        'class' => cleanText($_POST['class'] ?? ''),
        'birth_date' => cleanText($_POST['birth_date'] ?? ''),
        'father_name' => cleanName($_POST['father_name'] ?? ''),
        'father_phone' => cleanPhone($_POST['father_phone'] ?? ''),
        'mother_name' => cleanName($_POST['mother_name'] ?? ''),
        'mother_phone' => cleanPhone($_POST['mother_phone'] ?? ''),
        'address' => cleanText($_POST['address'] ?? ''),
        'teacher_name' => cleanName($_POST['teacher_name'] ?? ''),
        'teacher_phone' => cleanPhone($_POST['teacher_phone'] ?? ''),
        'notes' => cleanText($_POST['notes'] ?? ''),
        'is_active' => 1,
        'created_by' => getCurrentUserId()
    ];
    
    // 3. Validation
    $errors = [];
    if (empty($data['first_name'])) {
        $errors[] = 'İsim alanı zorunludur.';
    }
    if (empty($data['last_name'])) {
        $errors[] = 'Soyisim alanı zorunludur.';
    }
    if (empty($data['tc_no'])) {
        $errors[] = 'TC kimlik numarası zorunludur.';
    } elseif (strlen($data['tc_no']) != 11) {
        $errors[] = 'TC kimlik numarası 11 haneli olmalıdır.';
    } elseif ($this->studentModel->isTcExists($data['tc_no'])) {
        $errors[] = 'Bu TC kimlik numarası ile kayıtlı bir öğrenci zaten var.';
    }
    if (empty($data['birth_date'])) {
        $errors[] = 'Doğum tarihi zorunludur.';
    }
    if (empty($data['class'])) {
        $errors[] = 'Sınıf alanı zorunludur.';
    }
    
    // 4. Show errors if any
    if (!empty($errors)) {
        setFlashMessage(implode('<br>', $errors), 'error');
        redirect('/students/create');
    }
    
    // 5. Save to database
    $studentId = $this->studentModel->create($data);
    
    // 6. Handle response
    if ($studentId !== false && !is_string($studentId)) {
        setFlashMessage('Öğrenci başarıyla eklendi.', 'success');
        redirect('/students/' . $studentId);
    } else {
        $errorMsg = 'Öğrenci eklenirken bir hata oluştu.';
        if (is_string($studentId) && !empty($studentId)) {
            $errorMsg .= '<br><strong>Veritabanı Hatası:</strong> ' . htmlspecialchars($studentId);
        }
        setFlashMessage($errorMsg, 'error');
        redirect('/students/create');
    }
}

// Model
public function create($data) {
    if (isset($data['tc_no']) && empty($data['tc_no'])) {
        $data['tc_no'] = null;
    }
    $result = parent::create($data);
    if ($result === false && method_exists($this->db, 'getError')) {
        return $this->db->getError();
    }
    return $result;
}

// Database
public function insert($table, $data) {
    $columns = implode(', ', array_keys($data));
    $placeholders = ':' . implode(', :', array_keys($data));
    $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
    $this->query($sql);
    foreach ($data as $key => $value) {
        $this->bind(":{$key}", $value);
    }
    if ($this->execute()) {
        return $this->lastInsertId();
    }
    return false;
}
```

### **5. Öğrenci Düzenle (EDIT & UPDATE)**

**Routes:** 
- `GET /students/{id}/edit` - Edit form
- `POST /students/{id}` - Update

```php
// Controller - Similar to create/store
// Model - Uses update() method
// Database - Uses UPDATE query
```

### **6. Öğrenci Sil (DELETE)**

**Route:** `POST /students/{id}/delete` (AJAX)

```php
public function delete($id) {
    // Soft delete: Set is_active = 0
    $this->studentModel->delete($id);
    
    // Hard delete (permanent)
    $this->studentModel->hardDelete($id);
}
```

---

## 📥 Excel İşlemleri

### **1. İçe Aktar (IMPORT)**

**Route:** `POST /students/import/excel`

```php
// Process
1. Receive file
2. Validate file (type, size)
3. Save temp file
4. Read Excel with PhpSpreadsheet
5. For each row:
   - Sanitize data
   - Check TC uniqueness
   - Check duplicates in import
   - Insert or update
6. Generate report (added, updated, skipped)
7. Log errors
8. Delete temp file
9. Show result to user
```

### **2. Dışa Aktar (EXPORT)**

**Route:** `GET /students/export/excel`

```php
// Process
1. Get all students
2. Create Excel spreadsheet
3. Add headers
4. Add data rows
5. Format cells
6. Save file
7. Download file
8. Delete temp file
```

### **3. Şablon İndir (TEMPLATE)**

**Route:** `GET /students/download/template`

```php
// Process
1. Create empty Excel file
2. Add headers with formatting
3. Add sample data (optional)
4. Add data validation
5. Save file
6. Download file
```

---

## ⚠️ Hata Çözümleri

### **1. "Öğrenci eklenirken bir hata oluştu" Hatası**

**Olası Nedenler:**
- ❌ Gerekli alan boş
- ❌ TC numarası zaten kayıtlı
- ❌ Veritabanı bağlantı sorunu
- ❌ Tabloda alan eksik

**Çözüm:**

```php
// 1. Kontrol edin: Zorunlu alanlar dolu mu?
- TC Kimlik No
- İsim
- Soyisim
- Doğum Tarihi
- Sınıf

// 2. Kontrol edin: TC barkodu 11 haneli mi?
// 3. Kontrol edin: TC zaten kayıtlı mı?
// 4. Veritabanı hatalarını kontrol edin
// APP_DEBUG = true yapıp tekrar deneyin
```

### **2. "Field 'id' doesn't have a default value" Hatası**

**Neden:** Veritabanı şemasında `id` alanı AUTO_INCREMENT değil

**Çözüm:**

```sql
ALTER TABLE students MODIFY id INT AUTO_INCREMENT;
```

### **3. "Duplicate entry for key 'tc_no'" Hatası**

**Neden:** TC numarası zaten veritabanında var

**Çözüm:**
- Farklı TC kullanın
- Veya öğrenciyi düzenle (TC değiştir)

### **4. Excel İçe Aktarma Hataları**

**Hata Mesajları:**
- "Yanlış dosya formatı" → .xlsx dosyası kullanın
- "Dosya çok büyük" → Dosya boyutunu azaltın
- "Geçersiz veriler" → Veri formatını kontrol edin

---

## 📚 Geliştirme Notları

### **Yeni Özellik Ekleme**

**Örnek: "Ek Telefon" alanı eklemek**

1. **Veritabanı:**
   ```sql
   ALTER TABLE students ADD COLUMN phone_additional VARCHAR(11);
   ```

2. **Form (create.php):**
   ```html
   <input type="tel" name="phone_additional" />
   ```

3. **Controller (store):**
   ```php
   'phone_additional' => cleanPhone($_POST['phone_additional'] ?? ''),
   ```

4. **Model (Student.php):**
   ```php
   // No changes needed - generic create() handles it
   ```

5. **View (detail.php):**
   ```html
   <?= $student['phone_additional'] ?>
   ```

### **Validasyon Kuralı Ekleme**

**Örnek: Yaş sınırlandırması (16+)**

```php
// In controller store()
$birthDate = new DateTime($data['birth_date']);
$today = new DateTime();
$age = $today->diff($birthDate)->y;

if ($age < 16) {
    $errors[] = 'Öğrenci 16 yaşından büyük olmalıdır.';
}
```

### **Yetki Kontrolü Ekleme**

**Senaryo: Sadece öğretmenler öğrenci ekleyebilir**

```php
public function create() {
    if (!hasPermission('students', 'can_create')) {
        setFlashMessage('Bu işlem için yetkiniz yok.', 'error');
        redirect('/dashboard');
    }
    
    $this->view('students/create', [
        'title' => 'Yeni Öğrenci Ekle'
    ]);
}
```

### **Logging Ekleme**

```php
// In controller
error_log("Student created: " . json_encode($data));
error_log("Student ID: " . $studentId);

// In model
logActivity('student_created', 'students', $studentId, null, $data);
```

---

## 🎯 Test Senaryoları

### **Test 1: Başarılı Öğrenci Ekleme**

```
1. /students/create'e git
2. Form doldur:
   - TC: 12345678901
   - İsim: Ahmet
   - Soyisim: Yılmaz
   - Doğum: 2010-05-15
   - Sınıf: 9-A
3. Kaydet'e tıkla
4. Beklenen: "Öğrenci başarıyla eklendi." mesajı
5. Öğrencinin detay sayfasına git
```

### **Test 2: Zorunlu Alan Hataları**

```
1. Form'u boş bırak
2. Kaydet'e tıkla
3. Beklenen: Hata mesajlarını gör
   - "İsim alanı zorunludur."
   - "Soyisim alanı zorunludur."
   - "TC kimlik numarası zorunludur."
   - "Doğum tarihi zorunludur."
   - "Sınıf alanı zorunludur."
```

### **Test 3: TC Benzersizliği**

```
1. Aynı TC ile iki öğrenci ekle
2. Beklenen: 
   - İlki başarılı
   - İkincisi hata: "Bu TC kimlik numarası ile..."
```

---

**Öğrenci Modülü Detaylı Kılavuzu Tamamlandı!** 🎓
