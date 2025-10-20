<?php
echo "<h1>Fix Duplicate TC Numbers</h1>";

try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=vildacgg_portalv2;charset=utf8mb4',
        'vildacgg_tarihci20',
        'C@rg_;NBXBu5'
    );
    
    // Find duplicates
    echo "<h2>1. Find All Duplicates:</h2>";
    $stmt = $pdo->query("
        SELECT tc_no, COUNT(*) as count, GROUP_CONCAT(id ORDER BY id) as ids
        FROM students 
        WHERE tc_no IS NOT NULL 
        GROUP BY tc_no 
        HAVING count > 1
        ORDER BY count DESC
    ");
    $duplicates = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<p>Total duplicate TC groups: " . count($duplicates) . "</p>";
    
    if (!empty($duplicates)) {
        echo "<table border='1' cellpadding='10' style='margin: 20px 0;'>";
        echo "<tr><th>TC Number</th><th>Count</th><th>IDs</th></tr>";
        foreach ($duplicates as $dup) {
            echo "<tr>";
            echo "<td>" . $dup['tc_no'] . "</td>";
            echo "<td>" . $dup['count'] . "</td>";
            echo "<td>" . $dup['ids'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Show details
        echo "<h2>2. Duplicate Details:</h2>";
        foreach (array_slice($duplicates, 0, 5) as $dup) { // Show first 5
            echo "<h3>TC: " . $dup['tc_no'] . " (Count: " . $dup['count'] . ")</h3>";
            $stmt = $pdo->prepare("
                SELECT id, first_name, last_name, class, created_at 
                FROM students 
                WHERE tc_no = ? 
                ORDER BY id DESC
            ");
            $stmt->execute([$dup['tc_no']]);
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>ID</th><th>Name</th><th>Class</th><th>Created</th></tr>";
            foreach ($records as $r) {
                echo "<tr>";
                echo "<td>" . $r['id'] . "</td>";
                echo "<td>" . $r['first_name'] . " " . $r['last_name'] . "</td>";
                echo "<td>" . $r['class'] . "</td>";
                echo "<td>" . $r['created_at'] . "</td>";
                echo "</tr>";
            }
            echo "</table><br>";
        }
        
        // FIX: Remove older duplicates (keep newest)
        echo "<h2 style='color: red;'>3. Fix Strategy:</h2>";
        echo "<p>For each TC group, we'll keep the NEWEST (highest ID) and delete older ones.</p>";
        echo "<p><strong>SQL to execute (in cPanel):</strong></p>";
        
        $fixSql = "
-- Step 1: Create temp table with IDs to keep (newest for each TC)
CREATE TEMPORARY TABLE keep_ids AS
SELECT MAX(id) as id
FROM students
WHERE tc_no IS NOT NULL
GROUP BY tc_no;

-- Step 2: Delete duplicates (keep only the newest)
DELETE FROM students
WHERE tc_no IS NOT NULL
AND id NOT IN (SELECT id FROM keep_ids);

-- Step 3: Add UNIQUE constraint
ALTER TABLE students ADD UNIQUE INDEX unique_tc_no (tc_no);
        ";
        
        echo "<pre style='background: #f0f0f0; padding: 10px;'>";
        echo htmlspecialchars($fixSql);
        echo "</pre>";
        
    } else {
        echo "<p>✅ No duplicates found</p>";
        echo "<p>You can now safely add the UNIQUE constraint:</p>";
        echo "<pre>ALTER TABLE students ADD UNIQUE INDEX unique_tc_no (tc_no);</pre>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'><strong>Error:</strong> " . $e->getMessage() . "</p>";
}
?>
