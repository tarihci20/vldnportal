<?php
require 'config/constants.php';
require 'vendor/autoload.php';

try {
    $db = \Core\Database::getInstance();
    echo "Database connection: OK\n";
    
    // Check if vp_students table exists
    $db->query("SHOW TABLES LIKE 'vp_students'");
    $result = $db->resultSet();
    echo "vp_students table exists: " . (count($result) > 0 ? "YES" : "NO") . "\n";
    
    // Try to get student count
    $student = new \App\Models\Student();
    echo "Student model created\n";
    echo "Student table name: " . $student->table . "\n";
    
    $allStudents = $student->all();
    echo "Student count: " . count($allStudents) . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    echo "\nStack:\n";
    echo $e->getTraceAsString() . "\n";
}
?>
