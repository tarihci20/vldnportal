<?php
/**
 * Direct Model Test
 */

require_once dirname(__DIR__) . '/config/constants.php';
require_once ROOT_PATH . '/vendor/autoload.php';
require_once ROOT_PATH . '/core/Database.php';
require_once ROOT_PATH . '/app/Models/Student.php';

echo "<h1>Direct Model Test</h1>";

try {
    $student = new \App\Models\Student();
    
    echo "<h2>Test findById(1)</h2>";
    $result = $student->findById(1);
    
    if ($result) {
        echo "<p style='color: green;'>✅ FOUND</p>";
        echo "<pre>";
        var_dump($result);
        echo "</pre>";
    } else {
        echo "<p style='color: red;'>❌ NOT FOUND</p>";
    }
    
    echo "<h2>Test find(1) - direct call</h2>";
    $result2 = $student->find(1);
    
    if ($result2) {
        echo "<p style='color: green;'>✅ FOUND</p>";
        echo "<pre>";
        var_dump($result2);
        echo "</pre>";
    } else {
        echo "<p style='color: red;'>❌ NOT FOUND</p>";
    }
    
    echo "<h2>Test Database->select() direct</h2>";
    $db = \Core\Database::getInstance();
    $results = $db->select('students', ['*'], ['id' => 1]);
    
    echo "<p>Results type: " . gettype($results) . "</p>";
    echo "<p>Results count: " . (is_array($results) ? count($results) : 'N/A') . "</p>";
    echo "<pre>";
    var_dump($results);
    echo "</pre>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
