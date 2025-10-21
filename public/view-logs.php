<?php
/**
 * Show last error log entries
 */

// Local paths
$errorLog = __DIR__ . '/../storage/logs/error.log';
$phpErrors = '/var/log/php-errors.log'; // Common location
$customLog = '/home/vildacgg/logs/php-errors.log'; // cPanel location

echo "<h1>Error Log Viewer</h1>";

// Try different log locations
$logFiles = [
    'storage/logs/error.log' => $errorLog,
    '/var/log/php-errors.log' => $phpErrors,
    '/home/vildacgg/logs/php-errors.log' => $customLog,
];

foreach ($logFiles as $name => $path) {
    echo "<h3>$name</h3>";
    
    if (file_exists($path)) {
        $content = file_get_contents($path);
        $lines = explode("\n", $content);
        $lastLines = array_slice($lines, -20); // Last 20 lines
        
        echo "<pre>";
        echo htmlspecialchars(implode("\n", $lastLines));
        echo "</pre>";
    } else {
        echo "<p>❌ File not found</p>";
    }
}

// Also check error_log() default location
echo "<h3>error_log() function location</h3>";
echo "<p>ini_get('error_log'): " . (ini_get('error_log') ?: 'Not set (stdout)') . "</p>";
?>
