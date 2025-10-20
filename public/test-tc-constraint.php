<?php
echo "<h1>Duplicate TC Test</h1>";

try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=vildacgg_portalv2;charset=utf8mb4',
        'vildacgg_tarihci20',
        'C@rg_;NBXBu5'
    );
    
    // Check table constraints
    echo "<h2>1. Table Constraints & Indexes:</h2>";
    $stmt = $pdo->query("SHOW KEYS FROM students WHERE Key_name = 'tc_no'");
    $keys = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($keys)) {
        echo "<p style='color: orange;'><strong>⚠️ WARNING:</strong> NO UNIQUE CONSTRAINT on tc_no!</p>";
    } else {
        echo "<p>✅ Constraints found:</p>";
        echo "<pre>";
        print_r($keys);
        echo "</pre>";
    }
    
    // Check duplicates in current data
    echo "<h2>2. Check for Duplicate TC Numbers:</h2>";
    $stmt = $pdo->query("SELECT tc_no, COUNT(*) as count FROM students WHERE tc_no IS NOT NULL GROUP BY tc_no HAVING count > 1 LIMIT 10");
    $duplicates = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<p>Duplicate TC numbers found: " . count($duplicates) . "</p>";
    if (!empty($duplicates)) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>TC Number</th><th>Count</th></tr>";
        foreach ($duplicates as $dup) {
            echo "<tr><td>" . $dup['tc_no'] . "</td><td>" . $dup['count'] . "</td></tr>";
        }
        echo "</table>";
        
        // Show example
        if (!empty($duplicates[0]['tc_no'])) {
            echo "<h3>Example: Records with TC " . $duplicates[0]['tc_no'] . "</h3>";
            $stmt = $pdo->prepare("SELECT id, first_name, tc_no, created_at FROM students WHERE tc_no = ? ORDER BY id DESC");
            $stmt->execute([$duplicates[0]['tc_no']]);
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "<pre>";
            print_r($records);
            echo "</pre>";
        }
    } else {
        echo "<p>✅ No duplicates found</p>";
    }
    
    // Check table structure
    echo "<h2>3. Table Structure (tc_no column):</h2>";
    $stmt = $pdo->query("DESC students");
    $structure = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($structure as $col) {
        if ($col['Field'] === 'tc_no') {
            echo "<pre>";
            print_r($col);
            echo "</pre>";
        }
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'><strong>Error:</strong> " . $e->getMessage() . "</p>";
}
?>
