<?php
// Direktif olarak hatları göster
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

echo "Test başladı<br>";

// ROOT_PATH tanımla
define('ROOT_PATH', dirname(__DIR__));
define('BASE_PATH', '/portalv2');
define('BASE_URL', 'https://vldn.in/portalv2');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('CORE_PATH', ROOT_PATH . '/core');

echo "Constants defined<br>";

// DB sabitleri tanımla
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'vildacgg_portalv2');
define('DB_USER', 'vildacgg_tarihci20');
define('DB_PASS', 'C@rg_;NBXBu5');
define('DB_CHARSET', 'utf8mb4');
define('DB_PREFIX', 'vp_');
define('APP_DEBUG', true);

echo "DB Constants defined<br>";

// Autoload yükle
require_once ROOT_PATH . '/vendor/autoload.php';
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
