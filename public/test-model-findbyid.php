<?php
/**
 * Test 4: Model->findById() Isolation
 */

echo "<h1>Test 4: Model->findById() Debug</h1>";

try {
    require_once dirname(__DIR__) . '/config/config.php';
    require_once dirname(__DIR__) . '/vendor/autoload.php';
    
    echo "<h2>1. Loading Model...</h2>";
    $model = new \App\Models\Student();
    echo "<p>✅ Model loaded</p>";
    echo "<p>Table: <strong>" . $model->table . "</strong></p>";
    echo "<p>Primary Key: <strong>" . (isset($model->primaryKey) ? $model->primaryKey : 'NOT SET') . "</strong></p>";
    
    echo "<h2>2. Testing findById(1)...</h2>";
    
    // Get reflection to check protected properties
    $reflection = new ReflectionClass($model);
    $tableProperty = $reflection->getProperty('table');
    $tableProperty->setAccessible(true);
    $pkProperty = $reflection->getProperty('primaryKey');
    $pkProperty->setAccessible(true);
    
    echo "<p>Actual table: <strong>" . $tableProperty->getValue($model) . "</strong></p>";
    echo "<p>Actual PK: <strong>" . $pkProperty->getValue($model) . "</strong></p>";
    
    // Call findById
    echo "<p>Calling findById(1)...</p>";
    $result = $model->findById(1);
    
    if ($result) {
        echo "<p style='color: green;'>✅ Result found!</p>";
        echo "<pre>";
        var_dump($result);
        echo "</pre>";
    } else {
        echo "<p style='color: red;'>❌ Result is NULL/FALSE!</p>";
        
        // Try to find any student
        echo "<h2>3. Fallback: Try to find ANY student...</h2>";
        echo "<p>Calling findById(1) through base find()...</p>";
        
        // Direct DB query
        $db = \Core\Database::getInstance();
        echo "<p>Database instance: " . (is_object($db) ? "OK" : "FAILED") . "</p>";
        
        // Manual query
        echo "<p>Running manual SELECT...</p>";
        $db->query("SELECT * FROM students WHERE id = :id LIMIT 1");
        $db->bind(':id', 1);
        $manualResult = $db->single();
        
        if ($manualResult) {
            echo "<p>✅ Manual SELECT found it!</p>";
            echo "<pre>";
            var_dump($manualResult);
            echo "</pre>";
        } else {
            echo "<p>❌ Manual SELECT also failed</p>";
        }
    }
    
    echo "<h2>4. Check all students...</h2>";
    $db = \Core\Database::getInstance();
    $db->query("SELECT id, first_name, tc_no FROM students");
    $all = $db->resultSet();
    echo "<p>Total students: " . count($all) . "</p>";
    if (count($all) > 0) {
        echo "<pre>";
        print_r($all);
        echo "</pre>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>Exception:</strong> " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
