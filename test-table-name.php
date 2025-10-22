<?php
require 'config/constants.php';
require 'vendor/autoload.php';

try {
    $student = new \App\Models\Student();
    echo "Student table name: " . var_export($student->table, true) . "\n";
    echo "Expected: vp_students\n";
    
    // Constructor call tracki
    $reflection = new ReflectionClass($student);
    echo "Constructor exists: " . ($reflection->getConstructor() ? "YES" : "NO") . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
