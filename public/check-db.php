<?php
/**
 * Check Database Records
 */

echo "<h1>Database Check</h1>";

try {
    // Load constants
    require_once dirname(__DIR__) . '/config/constants.php';
    
    // Direct PDO connection
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET,
        DB_USER,
        DB_PASS
    );
    
    // 1. Check table structure
    echo "<h2>1. Table Structure</h2>";
    $stmt = $pdo->query("DESCRIBE students");
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . ($row['Default'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // 2. Check all records
    echo "<h2>2. ALL Records in students table</h2>";
    $stmt = $pdo->query("SELECT * FROM students");
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($records)) {
        echo "<p style='color: red;'><strong>NO RECORDS FOUND</strong></p>";
    } else {
        echo "<p style='color: green;'>Found: " . count($records) . " records</p>";
        echo "<table border='1'>";
        echo "<tr>";
        foreach (array_keys($records[0]) as $key) {
            echo "<th>" . htmlspecialchars($key) . "</th>";
        }
        echo "</tr>";
        
        foreach ($records as $row) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // 3. Check ID=1 specifically
    echo "<h2>3. Check ID=1 Record</h2>";
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->execute([1]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($record) {
        echo "<p style='color: green;'>✅ Record FOUND</p>";
        echo "<pre>";
        var_dump($record);
        echo "</pre>";
    } else {
        echo "<p style='color: red;'>❌ NO Record with ID=1</p>";
    }
    
    // 4. Check count
    echo "<h2>4. Total Records</h2>";
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM students");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>Total: " . $count['total'] . "</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>Error:</strong> " . $e->getMessage() . "</p>";
}
?>
