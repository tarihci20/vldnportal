<?php
/**
 * Check if files exist and can be loaded
 */
$files = [
    'config/constants.php',
    'config/config.php',
    'core/Database.php',
    'app/helpers/session.php'
];

echo "<h1>File Existence Check</h1>";
echo "<pre>";

$rootPath = dirname(dirname(__FILE__));
foreach ($files as $file) {
    $fullPath = $rootPath . '/' . $file;
    $exists = file_exists($fullPath);
    $readable = is_readable($fullPath);
    echo sprintf(
        "%-30s Exists: %s   Readable: %s\n", 
        $file, 
        $exists ? 'YES' : 'NO', 
        $readable ? 'YES' : 'NO'
    );
}

echo "</pre>";

// Try to manually include and check
echo "<h2>Manual Include Test</h2>";
echo "<pre>";

try {
    $testPath = $rootPath . '/config/constants.php';
    echo "Trying to include: $testPath\n";
    include $testPath;
    echo "Include successful!\n";
    echo "BASE_URL is now: " . (defined('BASE_URL') ? BASE_URL : 'NOT DEFINED') . "\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "</pre>";
?>
