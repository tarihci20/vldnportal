<?php
/**
 * Debug: Detail Page Test
 */

require_once dirname(__DIR__) . '/config/constants.php';
require_once ROOT_PATH . '/vendor/autoload.php';
require_once ROOT_PATH . '/core/Database.php';
require_once ROOT_PATH . '/app/Models/Student.php';
require_once ROOT_PATH . '/app/helpers/session.php';

echo "<h1>Detail Page Debug</h1>";

// Test all student IDs
for ($id = 1; $id <= 3; $id++) {
    echo "<h2>Testing Student ID = $id</h2>";
    
    try {
        $student = new \App\Models\Student();
        $result = $student->findById($id);
        
        if ($result) {
            echo "<p style='color: green;'>✅ FOUND via Model->findById($id)</p>";
            echo "<pre>";
            var_dump($result);
            echo "</pre>";
        } else {
            echo "<p style='color: red;'>❌ NOT FOUND via Model->findById($id)</p>";
            
            // Try direct database query
            echo "<p>Trying direct database query...</p>";
            $db = \Core\Database::getInstance();
            $results = $db->select('students', ['*'], ['id' => $id]);
            if (!empty($results)) {
                echo "<p style='color: green;'>✅ Direct query FOUND</p>";
                echo "<pre>";
                var_dump($results[0]);
                echo "</pre>";
            } else {
                echo "<p style='color: red;'>❌ Direct query NOT FOUND</p>";
            }
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'><strong>Error:</strong> " . $e->getMessage() . "</p>";
    }
    
    echo "<hr>";
}
?>
