<?php
/**
 * Check if new student was created
 */
include 'config/config.example.php';
include 'config/config.php';
include 'core/Database.php';

use Core\Database;

$db = Database::getInstance();

// Check latest students
$db->query("SELECT id, tc_no, first_name, last_name, class, created_at FROM students WHERE is_active = 1 ORDER BY created_at DESC LIMIT 5");
$students = $db->resultSet();

echo "<h1>Son 5 Öğrenci</h1>";
echo "<pre>";
print_r($students);
echo "</pre>";

// Check for TC that should exist (from tests)
$testTCs = ['55555555555', '66666666666', '44444444444', '33333333333', '22222222222'];

echo "<h2>Test TC'ler Kontrol</h2>";
foreach ($testTCs as $tc) {
    $db->query("SELECT id, tc_no, first_name FROM students WHERE tc_no = ? AND is_active = 1");
    $db->bind(1, $tc);
    $result = $db->single();
    
    if ($result) {
        echo "<p>✓ <strong>$tc</strong> BULUNDU: {$result['first_name']}</p>";
    } else {
        echo "<p>✗ <strong>$tc</strong> bulunamadı</p>";
    }
}
?>
