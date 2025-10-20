<?php
/**
 * Full Debug: INSERT to DETAIL Page Flow
 */

echo "<h1>Complete Flow Debug</h1>";

try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=vildacgg_portalv2;charset=utf8mb4',
        'vildacgg_tarihci20',
        'C@rg_;NBXBu5'
    );
    
    echo "<h2>1. Current student count:</h2>";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM students");
    $result = $stmt->fetch();
    echo "<p>Total students: " . $result['count'] . "</p>";
    
    echo "<h2>2. Testing INSERT flow:</h2>";
    
    // Generate test data
    $testTc = '33333333333';
    $testName = 'Debug Test ' . time();
    
    echo "<p>Inserting: TC=$testTc, Name=$testName</p>";
    
    // Insert
    $insertSql = "INSERT INTO students (tc_no, first_name, last_name, birth_date, class, is_active, created_by, created_at, updated_at) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
    $stmt = $pdo->prepare($insertSql);
    $insertResult = $stmt->execute([
        $testTc,
        $testName,
        'Student',
        '2010-01-01',
        '9-A',
        1,
        1
    ]);
    
    if ($insertResult) {
        $newId = $pdo->lastInsertId();
        echo "<p style='color: green;'>✅ Insert successful. New ID: <strong>$newId</strong></p>";
        
        echo "<h2>3. Immediate verification:</h2>";
        
        // Test 1: Direct by ID
        echo "<p><strong>Test 1: SELECT by ID ($newId)</strong></p>";
        $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([$newId]);
        $found1 = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($found1) {
            echo "<p>✅ Found by ID</p>";
            echo "<pre>";
            print_r($found1);
            echo "</pre>";
        } else {
            echo "<p style='color: red;'>❌ NOT FOUND by ID!</p>";
        }
        
        // Test 2: By TC
        echo "<p><strong>Test 2: SELECT by TC ($testTc)</strong></p>";
        $stmt = $pdo->prepare("SELECT * FROM students WHERE tc_no = ?");
        $stmt->execute([$testTc]);
        $found2 = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($found2) {
            echo "<p>✅ Found by TC - ID: " . $found2['id'] . "</p>";
        } else {
            echo "<p style='color: red;'>❌ NOT FOUND by TC!</p>";
        }
        
        // Test 3: Direct query without WHERE
        echo "<p><strong>Test 3: Last student in table</strong></p>";
        $stmt = $pdo->query("SELECT id, tc_no, first_name, created_at FROM students ORDER BY id DESC LIMIT 1");
        $lastStudent = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<pre>";
        print_r($lastStudent);
        echo "</pre>";
        
        // Test 4: What Model would do
        echo "<p><strong>Test 4: Simulating Model->findById($newId)</strong></p>";
        require_once dirname(__DIR__) . '/config/config.php';
        require_once dirname(__DIR__) . '/vendor/autoload.php';
        
        $db = \Core\Database::getInstance();
        $model = new \App\Models\Student();
        $modelResult = $model->findById($newId);
        
        if ($modelResult) {
            echo "<p>✅ Model found it</p>";
            echo "<pre>";
            print_r($modelResult);
            echo "</pre>";
        } else {
            echo "<p style='color: red;'>❌ Model couldn't find it!</p>";
            
            // Debug: Check what query Model is running
            echo "<p><strong>Debug: Table name used by Model:</strong> " . $model->table . "</p>";
            echo "<p><strong>Debug: Primary key:</strong> " . $model->primaryKey . "</p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ Insert failed!</p>";
        echo "<p>Error: " . $stmt->errorInfo()[2] . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>Exception:</strong> " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
