<?php
// Direktif olarak hatları göster
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

echo "Test başladı<br>";

// Config yükle
require_once '../config/config.php';
echo "Config yüklendi<br>";

// Autoload yükle
require_once '../vendor/autoload.php';
echo "Autoload yüklendi<br>";

// Database test
use Core\Database;
$db = Database::getInstance();
echo "Database bağlantısı OK<br>";

// Basit SQL sorgusu
$db->query("SELECT COUNT(*) as count FROM vp_roles");
$result = $db->single();
echo "Mevcut rol sayısı: " . $result['count'] . "<br>";

// Role model test
use App\Models\Role;
echo "Role model yüklendi<br>";

$roleModel = new Role();
echo "Role model instantiate edildi<br>";

$testData = [
    'role_name' => 'test_' . time(),
    'display_name' => 'Test Role',
    'description' => 'Test Description',
    'is_active' => 1
];

echo "Test data: " . json_encode($testData) . "<br>";

$roleId = $roleModel->create($testData);

echo "Role created! ID: " . $roleId . "<br>";

?>
