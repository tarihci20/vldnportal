<?php
/**
 * DANGER: Database Reset Script
 * Tüm öğrenci verilerini sil ve UNIQUE constraint ekle
 */

echo "<h1 style='color: red;'>⚠️ DATABASE RESET</h1>";

if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'YES_I_AM_SURE') {
    echo "<p><strong>Bu script TÜM ÖĞRENCİ VERİLERİNİ SİLECEKTİR!</strong></p>";
    echo "<p>Eğer eminsen tıkla:</p>";
    echo "<a href='?confirm=YES_I_AM_SURE' style='background: red; color: white; padding: 10px 20px; text-decoration: none;'>";
    echo "✅ Evet, tüm verileri sil ve RESET et";
    echo "</a>";
    exit;
}

try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=vildacgg_portalv2;charset=utf8mb4',
        'vildacgg_tarihci20',
        'C@rg_;NBXBu5'
    );
    
    echo "<h2>1. Deleting all student records...</h2>";
    $stmt = $pdo->prepare("DELETE FROM students");
    $stmt->execute();
    $count = $stmt->rowCount();
    echo "<p>✅ Deleted: $count records</p>";
    
    echo "<h2>2. Reset AUTO_INCREMENT...</h2>";
    $stmt = $pdo->prepare("ALTER TABLE students AUTO_INCREMENT = 1");
    $stmt->execute();
    echo "<p>✅ AUTO_INCREMENT reset to 1</p>";
    
    echo "<h2>3. Checking existing constraints...</h2>";
    $stmt = $pdo->query("SHOW KEYS FROM students WHERE Key_name = 'unique_tc_no'");
    $existing = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($existing)) {
        echo "<p>Constraint 'unique_tc_no' already exists, skipping...</p>";
    } else {
        echo "<h2>4. Adding UNIQUE constraint on tc_no...</h2>";
        try {
            $stmt = $pdo->prepare("ALTER TABLE students ADD UNIQUE INDEX unique_tc_no (tc_no)");
            $stmt->execute();
            echo "<p>✅ UNIQUE constraint added</p>";
        } catch (PDOException $e) {
            echo "<p style='color: orange;'>⚠️ Could not add constraint: " . $e->getMessage() . "</p>";
        }
    }
    
    echo "<h2>5. Verifying table structure...</h2>";
    $stmt = $pdo->query("DESC students");
    $structure = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach ($structure as $col) {
        echo "<tr>";
        echo "<td>" . $col['Field'] . "</td>";
        echo "<td>" . $col['Type'] . "</td>";
        echo "<td>" . $col['Null'] . "</td>";
        echo "<td>" . $col['Key'] . "</td>";
        echo "<td>" . $col['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<h2>6. Final check - Total students:</h2>";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM students");
    $result = $stmt->fetch();
    echo "<p><strong style='font-size: 20px;'>Total: " . $result['count'] . " students</strong></p>";
    
    echo "<div style='background: green; color: white; padding: 20px; margin-top: 20px; border-radius: 5px;'>";
    echo "<h3>✅ DATABASE RESET COMPLETE!</h3>";
    echo "<p>Artık sistem temiz ve production'a hazır.</p>";
    echo "<p><a href='../students/create' style='color: white; text-decoration: underline;'>Yeni öğrenci ekle</a></p>";
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'><strong>❌ Error:</strong> " . $e->getMessage() . "</p>";
}
?>
