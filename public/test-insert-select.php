<?php
// Test: INSERT ve hemen SELECT
echo "<h1>Test: Insert → Select Flow</h1>";

try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=vildacgg_portalv2;charset=utf8mb4',
        'vildacgg_tarihci20',
        'C@rg_;NBXBu5'
    );
    
    // Test data
    $testTc = '22222222222';
    $testName = 'Test ' . time();
    
    echo "<h2>1. Inserting...</h2>";
    $sql = "INSERT INTO students (tc_no, first_name, last_name, birth_date, class, is_active, created_by, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        $testTc,
        $testName,
        'Student',
        '2010-01-01',
        '9-A',
        1,
        1
    ]);
    
    echo "<p>Insert result: " . ($result ? "✅ Success" : "❌ Failed") . "</p>";
    
    if ($result) {
        $insertId = $pdo->lastInsertId();
        echo "<p>Last Insert ID: <strong>" . $insertId . "</strong></p>";
        
        // Immediately select
        echo "<h2>2. Selecting immediately after insert...</h2>";
        
        // Method 1: Direct SELECT
        echo "<p><strong>Method 1: Direct SELECT</strong></p>";
        $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([$insertId]);
        $result1 = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result1) {
            echo "<p>✅ Found by ID directly</p>";
            echo "<pre>";
            print_r($result1);
            echo "</pre>";
        } else {
            echo "<p>❌ NOT FOUND by direct SELECT</p>";
        }
        
        // Method 2: SELECT by TC
        echo "<p><strong>Method 2: SELECT by TC</strong></p>";
        $stmt = $pdo->prepare("SELECT * FROM students WHERE tc_no = ?");
        $stmt->execute([$testTc]);
        $result2 = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result2) {
            echo "<p>✅ Found by TC</p>";
            echo "<pre>";
            print_r($result2);
            echo "</pre>";
        } else {
            echo "<p>❌ NOT FOUND by TC</p>";
        }
        
        // Check recent inserts
        echo "<p><strong>Method 3: Last 3 inserts</strong></p>";
        $stmt = $pdo->query("SELECT id, tc_no, first_name, is_active, created_at FROM students ORDER BY id DESC LIMIT 3");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<pre>";
        print_r($results);
        echo "</pre>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'><strong>Error:</strong> " . $e->getMessage() . "</p>";
}
?>
