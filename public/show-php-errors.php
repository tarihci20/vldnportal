<?php
/**
 * Show PHP Error Log Location and Contents
 */

echo "<h1>PHP Error Log Information</h1>";

// Get error log path
$error_log_path = ini_get('error_log');
echo "<p><strong>Error Log Path (from ini_get):</strong> " . htmlspecialchars($error_log_path) . "</p>";

// Check if it exists
if ($error_log_path && file_exists($error_log_path)) {
    echo "<p style='color: green;'><strong>✓ File exists!</strong></p>";
    echo "<p><strong>File size:</strong> " . filesize($error_log_path) . " bytes</p>";
    echo "<p><strong>Last modified:</strong> " . date('Y-m-d H:i:s', filemtime($error_log_path)) . "</p>";
    
    // Show last 50 lines
    $lines = file($error_log_path);
    $lastLines = array_slice($lines, -50);
    
    echo "<h2>Last 50 lines:</h2>";
    echo "<pre style='background:#f5f5f5; padding:10px; border:1px solid #ccc; overflow-x:auto; max-height:500px;'>";
    foreach ($lastLines as $line) {
        echo htmlspecialchars($line);
    }
    echo "</pre>";
} else {
    echo "<p style='color: red;'><strong>✗ Error log file not found at: " . htmlspecialchars($error_log_path) . "</strong></p>";
}

// Try common locations
echo "<h2>Common error log locations (checked):</h2>";
$commonPaths = [
    '/home/vildacgg/vldn.in/portalv2/public/error_log',
    '/home/vildacgg/vldn.in/public_html/error_log',
    '/tmp/php-errors.log',
    '/var/log/php-errors.log',
    ini_get('error_log'),
];

echo "<ul>";
foreach ($commonPaths as $path) {
    if (file_exists($path)) {
        echo "<li style='color:green;'><strong>✓ EXISTS:</strong> $path (size: " . filesize($path) . " bytes)</li>";
    } else {
        echo "<li style='color:red;'><strong>✗ NOT FOUND:</strong> $path</li>";
    }
}
echo "</ul>";
?>
