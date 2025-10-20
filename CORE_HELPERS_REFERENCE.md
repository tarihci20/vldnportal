# 🔧 Vildan Portal - Core & Helper Functions Referansı

## 📚 İçindekiler
1. [Core Kütüphaneleri](#core-kütüphaneleri)
2. [Helper Fonksiyonları](#helper-fonksiyonları)
3. [Güvenlik Fonksiyonları](#güvenlik-fonksiyonları)
4. [Middleware'ler](#middlewareler)
5. [Routing Sistemi](#routing-sistemi)

---

## 🏗️ Core Kütüphaneleri

### **1. Database.php** - PDO Wrapper

```php
// Singleton instance
$db = Core\Database::getInstance();

// Basic Query
$db->query("SELECT * FROM students WHERE id = ?");
$db->bind(1, $studentId);
$result = $db->single();  // Tek satır

// Multiple rows
$db->query("SELECT * FROM students");
$results = $db->resultSet();  // Tüm satırlar

// Insert
$id = $db->insert('students', [
    'first_name' => 'Ahmet',
    'last_name' => 'Yılmaz',
    'tc_no' => '12345678901'
]);

// Update
$db->update('students', 
    ['first_name' => 'Ahmed'],  // new values
    ['id' => 1]                  // where conditions
);

// Delete
$db->delete('students', ['id' => 1]);

// Error handling
$error = $db->getError();
```

### **2. Model.php** - Base Model Class

```php
namespace App\Models;

class Student extends Model {
    protected $table = 'students';
    protected $primaryKey = 'id';
    protected $timestamps = true;
    
    // Inherited methods:
    $this->find($id);           // Find by ID
    $this->findWhere($where);   // Find by condition
    $this->all();               // Get all
    $this->create($data);       // Insert
    $this->update($id, $data);  // Update
    $this->delete($id);         // Soft delete
}
```

### **3. Controller.php** - Base Controller

```php
// View rendering
$this->view('students/index', [
    'students' => $students,
    'title' => 'Student List'
], 'main');  // layout

// JSON response
$this->json(['success' => true, 'data' => $data]);

// Success response
$this->success($data, 'Operation successful');

// Error response
$this->error('Something went wrong');
```

### **4. Router.php** - URL Routing

```php
// Define routes
$router->get('/students', 'StudentController@index');
$router->post('/students', 'StudentController@store');
$router->get('/students/{id}', 'StudentController@detail');
$router->post('/students/{id}', 'StudentController@update');
$router->delete('/students/{id}', 'StudentController@delete');

// Dispatch request
$router->dispatch();

// Generate URL
url('/students');              // /portalv2/students
url('/students/123');          // /portalv2/students/123
url('/students?page=2');       // /portalv2/students?page=2
```

### **5. Request.php** - HTTP Request

```php
// Get method
$method = $this->getMethod();  // GET, POST, PUT, DELETE

// Get parameters
$input = $this->getInput();    // $_GET + $_POST combined

// Get header
$contentType = $this->getHeader('Content-Type');

// Check AJAX
if ($this->isAjax()) { ... }
```

### **6. Response.php** - HTTP Response

```php
// JSON response
$response->json(['status' => 'ok']);

// Redirect
$response->redirect('/dashboard');

// Set header
$response->header('Content-Type', 'application/json');

// Set status code
$response->status(404);

// Send response
$response->send();
```

### **7. Session.php** - Session Management

```php
// Singleton instance
$session = Core\Session::getInstance();

// Set value
$session->set('user_id', 123);

// Get value
$userId = $session->get('user_id');

// Check exists
if ($session->has('user_id')) { ... }

// Delete value
$session->delete('user_id');

// Destroy session
$session->destroy();

// CSRF token
$token = $session->generateToken();
$session->validateToken($token);
```

---

## 🛠️ Helper Fonksiyonları

### **app/helpers/functions.php**

```php
// URL Management
url($path = '')                    // Generate URL with base path
asset($path)                       // Get asset URL
upload($path)                      // Get upload URL

// Redirects
redirect($url)                     // Redirect to URL
back()                             // Redirect to previous page
abort($statusCode = 404)           // Abort with error code

// String Functions
truncate($text, $limit)            // Truncate text
slug($text)                        // Convert to URL slug
camelCase($text)                   // Convert to camelCase
snakeCase($text)                   // Convert to snake_case

// Array Functions
arrayGet($array, $key, $default)   // Get value or default
arraySet(&$array, $key, $value)    // Set array value
arrayMap($items, $key)             // Extract column

// Date Functions
now()                              // Current timestamp
today()                            // Today's date
parseDate($date, $format = 'Y-m-d')
formatDate($date)                  // Format: 01.01.2024
formatDateTurkish($date)           // Türkçe format

// Helper Checks
isProduction()                     // Check if production
isDevelopment()                    // Check if development
isDebug()                          // Check if debug mode
```

### **app/helpers/session.php**

```php
// Session Functions
startSession()                     // Initialize session
isLoggedIn()                       // Check if user logged in
getCurrentUserId()                 // Get current user ID
currentUser()                      // Get current user data
auth($key = null)                  // Get auth value
logout()                           // Logout user

// Flash Messages
setFlashMessage($msg, $type = 'info')
getFlashMessage()                  // Get and delete message

// CSRF Protection
csrf_token()                       // Get CSRF token
csrfField()                        // Output hidden field
validateCsrfToken($token)          // Validate token

// Permissions
hasPermission($module, $action)    // Check permission
isAdmin()                          // Check if admin
hasRole($roles)                    // Check roles
```

### **app/helpers/sanitize.php**

```php
// Input Cleaning
cleanName($value)                  // Clean names
cleanEmail($email)                 // Clean email
cleanPhone($phone)                 // Clean phone (11 digits)
cleanTcNo($tc)                     // Clean TC (11 digits)
cleanText($text)                   // Clean general text
cleanPassword($pass)               // Validate password
cleanUrl($url)                     // Clean URL
stripScripts($text)                // Remove scripts
```

### **app/helpers/validation.php**

```php
// Validation Functions
validateRequired($value)           // Check if required
validateEmail($email)              // Validate email
validatePhone($phone)              // Validate phone
validateTcNo($tc)                  // Validate TC number
validateDate($date)                // Validate date
validateUrl($url)                  // Validate URL
validateLength($text, $min, $max)  // Check length
validateMatch($value1, $value2)    // Check if match

// Response Functions
validate($data, $rules)            // Validate with rules
validationError($key)              // Get error message
hasErrors()                        // Check if errors exist
showErrors()                       // Display all errors
```

### **app/helpers/excel.php**

```php
// Excel Functions
exportStudentsToExcel($students)   // Export to Excel
createStudentExcelTemplate()       // Create template
importStudentsFromExcel($path)     // Import from Excel
validateExcelFile($file)           // Validate file

// Cleaning Functions (Also in sanitize.php)
cleanName($value)
cleanPhone($value)
cleanTcNo($value)
cleanText($value)
```

---

## 🔒 Güvenlik Fonksiyonları

### **CSRF Protection**

```php
// Generate token
$token = generateCSRFToken();

// Validate token
if (validateCsrfToken($_POST['csrf_token'])) {
    // Safe to process
}

// In form
<input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

// Helper
<?= csrfField() ?>  // Outputs the hidden input
```

### **Input Validation & Sanitization**

```php
// Example in StudentController::store()
$tc = cleanTcNo($_POST['tc_no'] ?? '');  // "12345678901"
$name = cleanName($_POST['first_name'] ?? '');  // "Ahmet"
$phone = cleanPhone($_POST['phone'] ?? '');  // "05551234567"

// Validation
if (empty($tc) || strlen($tc) != 11) {
    $errors[] = 'TC must be 11 digits';
}
```

### **Authentication**

```php
// Check login status
if (!isLoggedIn()) {
    redirect('/login');
}

// Get current user
$user = currentUser();
$userId = getCurrentUserId();

// Check admin
if (!isAdmin()) {
    abort(403);  // Forbidden
}

// Logout
logout();  // Destroy session and redirect
```

### **Password Security**

```php
// Hash password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Verify password
if (password_verify($inputPassword, $hashedPassword)) {
    // Correct password
}
```

---

## 🚦 Middleware'ler

### **AuthMiddleware.php** - Authentication Check

```php
use App\Middleware\AuthMiddleware;

// In controller constructor
AuthMiddleware::handle();  // Throws exception if not logged in

// Or manual check
if (!isLoggedIn()) {
    redirect('/login');
}
```

### **PermissionMiddleware.php** - Authorization Check

```php
use App\Middleware\PermissionMiddleware;

// Check specific permission
PermissionMiddleware::canView('students');
PermissionMiddleware::canCreate('students');
PermissionMiddleware::canEdit('students');
PermissionMiddleware::canDelete('students');
```

### **CSRFMiddleware.php** - CSRF Token Validation

```php
use App\Middleware\CSRFMiddleware;

// Validate CSRF token
CSRFMiddleware::verify($_POST['csrf_token']);
```

### **RateLimitMiddleware.php** - Rate Limiting

```php
use App\Middleware\RateLimitMiddleware;

// Limit requests per minute
RateLimitMiddleware::check($userId, 60);  // Max 60 per minute
```

### **RoleMiddleware.php** - Role-Based Access

```php
use App\Middleware\RoleMiddleware;

// Check roles
RoleMiddleware::check(['admin', 'teacher']);  // Only these roles
```

---

## 🗺️ Routing Sistemi

### **web.php** - Route Definitions

```php
// Student routes
$router->get('/students', 'StudentController@index', 'students.index');
$router->get('/students/create', 'StudentController@create', 'students.create');
$router->post('/students', 'StudentController@store', 'students.store');
$router->get('/students/{id}', 'StudentController@detail', 'students.detail');
$router->get('/students/{id}/edit', 'StudentController@edit', 'students.edit');
$router->post('/students/{id}', 'StudentController@update', 'students.update');
$router->delete('/students/{id}', 'StudentController@delete', 'students.delete');

// Named routes for url generation
<?= url('students.index') ?>  // /portalv2/students
<?= url('students.create') ?>  // /portalv2/students/create
```

### **api.php** - API Routes (AJAX)

```php
$router->get('/api/students/{id}', 'StudentController@apiGet');
$router->post('/api/students/search', 'StudentController@ajaxSearch');
$router->get('/api/students/class/list', 'StudentController@classList');
```

---

## 🔄 Request/Response Cycle

### **Full Lifecycle Example**

```
1. USER REQUEST
   └─ POST /students/store with form data

2. ROUTER
   ├─ Match route
   └─ Call StudentController::store()

3. MIDDLEWARE
   ├─ AuthMiddleware → Check login
   ├─ CSRFMiddleware → Verify token
   └─ PermissionMiddleware → Check permissions

4. CONTROLLER
   ├─ Sanitize input
   ├─ Validate data
   ├─ Call Model
   └─ Handle response

5. MODEL
   ├─ Apply business rules
   ├─ Call Database
   └─ Return result

6. DATABASE
   ├─ Prepare statement
   ├─ Execute query
   └─ Return data

7. RESPONSE
   ├─ Set flash message
   ├─ Redirect or render view
   └─ Send to browser

8. USER FEEDBACK
   └─ See result page with message
```

---

## 📊 Common Patterns

### **Create Record Pattern**

```php
// Controller
public function store() {
    // 1. Validate CSRF
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        setFlashMessage('Invalid token', 'error');
        redirect('/students/create');
    }
    
    // 2. Sanitize input
    $data = [
        'name' => cleanName($_POST['name'] ?? ''),
        'email' => cleanEmail($_POST['email'] ?? '')
    ];
    
    // 3. Validate data
    $errors = [];
    if (empty($data['name'])) {
        $errors[] = 'Name is required';
    }
    if (!empty($errors)) {
        setFlashMessage(implode('<br>', $errors), 'error');
        redirect('/students/create');
    }
    
    // 4. Create record
    $id = $this->model->create($data);
    if ($id) {
        setFlashMessage('Student added successfully', 'success');
        redirect('/students/' . $id);
    } else {
        setFlashMessage('Error adding student', 'error');
        redirect('/students/create');
    }
}
```

### **List Records Pattern**

```php
public function index() {
    $page = $_GET['page'] ?? 1;
    $search = $_GET['search'] ?? '';
    
    $result = $this->model->getAll($page, 20, [
        'search' => $search
    ]);
    
    $this->view('students/index', [
        'students' => $result['data'],
        'pagination' => $result['pagination']
    ]);
}
```

### **Update Record Pattern**

```php
public function update($id) {
    // 1. Find existing record
    $student = $this->model->findById($id);
    if (!$student) {
        setFlashMessage('Student not found', 'error');
        redirect('/students');
    }
    
    // 2. Same as create: validate, sanitize
    
    // 3. Update record
    if ($this->model->update($id, $data)) {
        setFlashMessage('Student updated', 'success');
        redirect('/students/' . $id);
    } else {
        setFlashMessage('Update failed', 'error');
        redirect('/students/' . $id . '/edit');
    }
}
```

---

**Core & Helper Functions Referansı Tamamlandı!** 📚
