<?php
/**
 * Production Database & Import Debugging
 * Çalıştır: https://vldn.in/portalv2/public/debug-import.php
 */

require '../config/constants.php';
require '../vendor/autoload.php';

if (!isset($_GET['secret']) || $_GET['secret'] !== 'debug2024') {
    die('Unauthorized');
}

echo "<h1>Database Debug</h1>";
echo "<hr>";

try {
    $db = \Core\Database::getInstance();
    
    // 1. Connection test
    echo "<h3>1. Database Connection</h3>";
    $db->query("SELECT 1");
    echo "✅ Connection OK<br>";
    echo "Database: " . DB_NAME . "<br>";
    echo "Prefix: " . DB_PREFIX . "<br>";
    
    // 2. Check vp_students table exists
    echo "<h3>2. vp_students Table</h3>";
    $db->query("SHOW TABLES LIKE 'vp_students'");
    $result = $db->resultSet();
    if (count($result) > 0) {
        echo "✅ Table exists<br>";
    } else {
        echo "❌ Table NOT FOUND!<br>";
        echo "Available vp_ tables:<br>";
        $db->query("SHOW TABLES LIKE 'vp_%'");
        $tables = $db->resultSet();
        foreach ($tables as $t) {
            echo "- " . array_values($t)[0] . "<br>";
        }
        die();
    }
    
    // 3. Check vp_students structure
    echo "<h3>3. vp_students Schema</h3>";
    $db->query("DESCRIBE vp_students");
    $columns = $db->resultSet();
    echo "<table border='1' style='border-collapse:collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($col['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($col['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($col['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($col['Key']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // 4. Test Student Model
    echo "<h3>4. Student Model Test</h3>";
    $student = new \App\Models\Student();
    echo "Table name: " . htmlspecialchars($student->table) . "<br>";
    echo "Expected: vp_students<br>";
    if ($student->table === 'vp_students') {
        echo "✅ Correct prefix!<br>";
    } else {
        echo "❌ WRONG PREFIX!<br>";
    }
    
    // 5. Count students
    echo "<h3>5. Student Count</h3>";
    $student->getDb()->query("SELECT COUNT(*) as cnt FROM vp_students");
    $count_result = $student->getDb()->single();
    echo "Current count: " . ($count_result['cnt'] ?? 0) . "<br>";
    
    // 6. Test insert
    echo "<h3>6. Test Insert</h3>";
    $testData = [
        'first_name' => 'TEST',
        'last_name' => 'ÖĞRENCI',
        'tc_no' => 'TEST_' . time(),
        'is_active' => 1
    ];
    
    $result = $student->create($testData);
    if ($result !== false) {
        echo "✅ Insert successful! ID: " . $result . "<br>";
        // Delete test record
        $student->delete($result);
        echo "✅ Test record deleted<br>";
    } else {
        echo "❌ Insert FAILED!<br>";
        $dbError = $student->getDb()->getError();
        echo "DB Error: " . htmlspecialchars($dbError) . "<br>";
    }
    
    // 7. Check recent import logs
    echo "<h3>7. Recent Import Error Logs</h3>";
    $logPath = LOG_PATH . '/';
    $files = glob($logPath . '/excel_import_errors_*.log');
    rsort($files);
    if (!empty($files)) {
        echo "Latest 5 import errors:<br><br>";
        foreach (array_slice($files, 0, 5) as $file) {
            echo "<strong>" . basename($file) . "</strong>:<br>";
            echo "<pre>" . htmlspecialchars(file_get_contents($file)) . "</pre>";
            echo "<br>";
        }
    } else {
        echo "No import error logs found<br>";
    }
    
} catch (Exception $e) {
    echo "<h3>Error</h3>";
    echo "<pre>";
    echo $e->getMessage() . "\n";
    echo $e->getTraceAsString();
    echo "</pre>";
}
?>
