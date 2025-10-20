<?php
// Minimal test - no config
echo "<h1>Database Connection Test</h1>";

try {
    // Direct connection
    $pdo = new PDO(
        'mysql:host=localhost;dbname=vildacgg_portalv2;charset=utf8mb4',
        'vildacgg_tarihci20',
        'C@rg_;NBXBu5'
    );
    echo "<p style='color: green;'>✅ <strong>Database Connected</strong></p>";
    
    // Get last 5 students
    echo "<h2>Last 5 Students:</h2>";
    $stmt = $pdo->query('SELECT id, first_name, last_name, tc_no, is_active, created_at FROM students ORDER BY id DESC LIMIT 5');
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Name</th><th>TC</th><th>is_active</th><th>Created</th></tr>";
    foreach ($students as $s) {
        echo "<tr>";
        echo "<td>" . $s['id'] . "</td>";
        echo "<td>" . $s['first_name'] . " " . $s['last_name'] . "</td>";
        echo "<td>" . $s['tc_no'] . "</td>";
        echo "<td>" . ($s['is_active'] === null ? 'NULL' : $s['is_active']) . "</td>";
        echo "<td>" . $s['created_at'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Count by is_active
    echo "<h2>Student Counts:</h2>";
    $counts = [
        'total' => $pdo->query('SELECT COUNT(*) as c FROM students')->fetch()['c'],
        'active' => $pdo->query('SELECT COUNT(*) as c FROM students WHERE is_active = 1')->fetch()['c'],
        'inactive' => $pdo->query('SELECT COUNT(*) as c FROM students WHERE is_active = 0')->fetch()['c'],
        'null_status' => $pdo->query('SELECT COUNT(*) as c FROM students WHERE is_active IS NULL')->fetch()['c'],
    ];
    
    echo "<ul>";
    echo "<li>Total: " . $counts['total'] . "</li>";
    echo "<li>Active (is_active=1): " . $counts['active'] . "</li>";
    echo "<li>Inactive (is_active=0): " . $counts['inactive'] . "</li>";
    echo "<li>NULL status: " . $counts['null_status'] . "</li>";
    echo "</ul>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'><strong>❌ Error:</strong> " . $e->getMessage() . "</p>";
}
?>
