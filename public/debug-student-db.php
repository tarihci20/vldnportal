<?php
/**
 * Debug: Student Insert Test from cPanel
 */

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Core\Database;
use App\Models\Student;

try {
    echo "<h2>Debug: Student Database Test</h2>";
    
    // 1. DB Connection
    echo "<p><strong>1. Database Connection:</strong>";
    $db = Database::getInstance();
    echo " ✅ Connected</p>";
    
    // 2. Check recent students
    echo "<p><strong>2. Last 3 students:</strong></p>";
    $db->query("SELECT id, first_name, last_name, tc_no, is_active, created_at FROM students ORDER BY id DESC LIMIT 3");
    $recent = $db->resultSet();
    echo "<pre>";
    print_r($recent);
    echo "</pre>";
    
    // 3. Check total count
    echo "<p><strong>3. Total student count:</strong> ";
    $db->query("SELECT COUNT(*) as count FROM students");
    $countResult = $db->single();
    echo $countResult['count'] . " students</p>";
    
    // 4. Check is_active = 1
    echo "<p><strong>4. Active students (is_active=1):</strong> ";
    $db->query("SELECT COUNT(*) as count FROM students WHERE is_active = 1");
    $activeResult = $db->single();
    echo $activeResult['count'] . " active</p>";
    
    // 5. Check is_active = 0
    echo "<p><strong>5. Inactive students (is_active=0):</strong> ";
    $db->query("SELECT COUNT(*) as count FROM students WHERE is_active = 0");
    $inactiveResult = $db->single();
    echo $inactiveResult['count'] . " inactive</p>";
    
    // 6. Check table structure
    echo "<p><strong>6. Table columns:</strong></p>";
    $db->query("DESC students");
    $columns = $db->resultSet();
    echo "<pre>";
    print_r($columns);
    echo "</pre>";
    
    // 7. Check for NULL is_active
    echo "<p><strong>7. Check for NULL is_active:</strong> ";
    $db->query("SELECT COUNT(*) as count FROM students WHERE is_active IS NULL");
    $nullResult = $db->single();
    echo $nullResult['count'] . " NULL values</p>";
    
    echo "<p style='color: green;'><strong>✅ All checks passed!</strong></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>❌ Error:</strong> " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
