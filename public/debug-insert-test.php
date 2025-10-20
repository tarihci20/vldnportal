<?php
/**
 * Direct Student Insert Test
 */

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Core\Database;
use App\Models\Student;

try {
    echo "<h2>Direct Student Insert Test</h2>";
    
    $db = Database::getInstance();
    
    // Test data
    $testData = [
        'tc_no' => '11111111111',
        'first_name' => 'Test Insert ' . time(),
        'last_name' => 'Student',
        'birth_date' => '2010-01-01',
        'class' => '9-A',
        'is_active' => 1,
        'created_by' => 1,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    echo "<p><strong>Insert Data:</strong></p>";
    echo "<pre>";
    print_r($testData);
    echo "</pre>";
    
    // Insert
    echo "<p><strong>Inserting...</strong></p>";
    $insertId = $db->insert('students', $testData);
    
    echo "<p><strong>Insert Result:</strong> " . var_export($insertId, true) . "</p>";
    
    if ($insertId) {
        // Try to find it
        echo "<p><strong>Finding by ID: " . $insertId . "</strong></p>";
        
        // Method 1: Direct query
        echo "<p><em>Method 1: Direct SELECT</em></p>";
        $db->query("SELECT * FROM students WHERE id = :id");
        $db->bind(':id', $insertId);
        $result1 = $db->single();
        echo "<pre>";
        print_r($result1);
        echo "</pre>";
        
        // Method 2: Using Model find
        echo "<p><em>Method 2: Model->find()</em></p>";
        $model = new Student();
        $result2 = $model->findById($insertId);
        echo "<pre>";
        print_r($result2);
        echo "</pre>";
        
        // Check if both methods match
        if ($result1 === $result2) {
            echo "<p style='color: green;'>✅ Both methods match!</p>";
        } else {
            echo "<p style='color: red;'>❌ Methods return different results!</p>";
            echo "<p><strong>Difference:</strong></p>";
            if (!$result2) {
                echo "<p>Model->find() returned NULL/FALSE</p>";
            }
        }
    } else {
        echo "<p style='color: red;'><strong>Insert failed!</strong></p>";
        echo "<p>Error: " . $db->getError() . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>Exception:</strong> " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
