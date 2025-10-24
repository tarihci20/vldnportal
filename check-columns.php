<?php
include 'config/config.php';

$db = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

if ($db->connect_error) {
    die('Connection failed: ' . $db->connect_error);
}

$result = $db->query('DESCRIBE vp_activity_areas');

echo "=== vp_activity_areas Columns ===\n";
while($row = $result->fetch_assoc()) {
    echo $row['Field'] . ' - ' . $row['Type'] . " (NULL: " . $row['Null'] . ", Default: " . $row['Default'] . ")\n";
}

$db->close();
?>
