<?php
/**
 * Ultra Simple: Fixed - Load constants.php FIRST
 * This test verifies that constants are loaded before Database class
 */

echo "<h1>Ultra Simple DB Test - FIXED</h1>";

try {
    // 1. Load constants FIRST (CRITICAL)
    echo "<h2>1. Loading constants...</h2>";
    require_once dirname(__DIR__) . '/config/constants.php';
    echo "<p>✅ Constants loaded</p>";
    echo "<p>BASE_URL: " . (defined('BASE_URL') ? BASE_URL : 'NOT DEFINED') . "</p>";
    echo "<p>DB_HOST: " . (defined('DB_HOST') ? DB_HOST : 'NOT DEFINED') . "</p>";
    echo "<p>DB_NAME: " . (defined('DB_NAME') ? DB_NAME : 'NOT DEFINED') . "</p>";
    
    // 2. Load Composer
    echo "<h2>2. Loading Composer autoload...</h2>";
    require_once dirname(__DIR__) . '/vendor/autoload.php';
    echo "<p>✅ Autoload loaded</p>";
    
    // 3. Load core files
    echo "<h2>3. Loading core files...</h2>";
    require_once ROOT_PATH . '/core/Database.php';
    require_once ROOT_PATH . '/app/Models/Student.php';
    echo "<p>✅ Core files loaded</p>";
    
    // 4. Direct PDO test
    echo "<h2>4. Direct PDO - SELECT by ID</h2>";
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET,
        DB_USER,
        DB_PASS
    );
    
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->execute([1]);
    $direct = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($direct) {
        echo "<p style='color: green;'>✅ FOUND by direct PDO</p>";
        echo "<p>ID: " . $direct['id'] . "</p>";
        echo "<p>Name: " . $direct['first_name'] . "</p>";
    } else {
        echo "<p style='color: red;'>❌ NOT FOUND by direct PDO</p>";
    }
    
    // 5. Test with Database class
    echo "<h2>5. Testing Database class...</h2>";
    $db = \Core\Database::getInstance();
    echo "<p>✅ Database instance created</p>";
    
    $db->query("SELECT * FROM students WHERE id = :id LIMIT 1");
    $db->bind(':id', 1);
    $dbResult = $db->single();
    
    if ($dbResult) {
        echo "<p style='color: green;'>✅ Database->single() found it</p>";
        echo "<pre>";
        var_dump($dbResult);
        echo "</pre>";
    } else {
        echo "<p style='color: red;'>❌ Database->single() returned NULL</p>";
    }
    
    // 6. Test Model
    echo "<h2>6. Testing Model->findById()...</h2>";
    $model = new \App\Models\Student();
    echo "<p>✅ Model instantiated</p>";
    
    $result = $model->findById(1);
    
    if ($result) {
        echo "<p style='color: green;'>✅ Model->findById(1) SUCCESS</p>";
        echo "<pre>";
        var_dump($result);
        echo "</pre>";
    } else {
        echo "<p style='color: red;'>❌ Model->findById(1) returned NULL</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
