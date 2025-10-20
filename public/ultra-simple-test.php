<?php
/**
 * Ultra Simple: Just test Database->select()
 */

echo "<h1>Ultra Simple DB Test</h1>";

try {
    // Direct connection - NO config
    $pdo = new PDO(
        'mysql:host=localhost;dbname=vildacgg_portalv2;charset=utf8mb4',
        'vildacgg_tarihci20',
        'C@rg_;NBXBu5'
    );
    
    echo "<h2>Direct PDO - SELECT by ID</h2>";
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->execute([1]);
    $direct = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($direct) {
        echo "<p>✅ FOUND by direct PDO</p>";
        echo "<p>ID: " . $direct['id'] . "</p>";
        echo "<p>Name: " . $direct['first_name'] . "</p>";
    } else {
        echo "<p>❌ NOT FOUND by direct PDO</p>";
    }
    
    // Now test with config
    echo "<h2>Loading config...</h2>";
    $configPath = dirname(__DIR__) . '/config/config.php';
    if (file_exists($configPath)) {
        require_once $configPath;
        echo "<p>✅ Config loaded</p>";
        
        // Check constants
        echo "<p>BASE_URL: " . (defined('BASE_URL') ? BASE_URL : 'NOT DEFINED') . "</p>";
        echo "<p>DB_HOST: " . (defined('DB_HOST') ? DB_HOST : 'NOT DEFINED') . "</p>";
        echo "<p>DB_NAME: " . (defined('DB_NAME') ? DB_NAME : 'NOT DEFINED') . "</p>";
        
        // Load autoload
        $autoloadPath = dirname(__DIR__) . '/vendor/autoload.php';
        if (file_exists($autoloadPath)) {
            require_once $autoloadPath;
            echo "<p>✅ Autoload loaded</p>";
            
            // Test Model
            echo "<h2>Testing Model...</h2>";
            try {
                $model = new \App\Models\Student();
                echo "<p>✅ Model instantiated</p>";
                
                $result = $model->findById(1);
                
                if ($result) {
                    echo "<p>✅ Model->findById(1) SUCCESS</p>";
                    echo "<pre>";
                    var_dump($result);
                    echo "</pre>";
                } else {
                    echo "<p>❌ Model->findById(1) returned NULL</p>";
                    
                    // Test Database directly
                    echo "<h2>Testing Database class directly...</h2>";
                    $db = \Core\Database::getInstance();
                    echo "<p>DB Instance: " . (is_object($db) ? "OK" : "FAILED") . "</p>";
                    
                    $db->query("SELECT * FROM students WHERE id = :id");
                    $db->bind(':id', 1);
                    $dbResult = $db->single();
                    
                    if ($dbResult) {
                        echo "<p>✅ Database->single() found it</p>";
                        echo "<pre>";
                        var_dump($dbResult);
                        echo "</pre>";
                    } else {
                        echo "<p>❌ Database->single() returned NULL</p>";
                    }
                }
            } catch (Exception $e) {
                echo "<p style='color: red;'>❌ Model Error: " . $e->getMessage() . "</p>";
            }
        } else {
            echo "<p>❌ Autoload not found at: $autoloadPath</p>";
        }
    } else {
        echo "<p>❌ Config not found at: $configPath</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>Error:</strong> " . $e->getMessage() . "</p>";
}
?>
