<?php
/**
 * Check latest error log
 */
echo "<h1>Error Log Tail (Son 50 satır)</h1>";

$logPaths = [
    '/var/log/php-errors.log',
    '/home/vildacgg/logs/php-errors.log',
    '/home/vildacgg/public_html/portalv2/error.log'
];

// Check each path
foreach ($logPaths as $logPath) {
    if (@file_exists($logPath)) {
        echo "<h2>$logPath</h2>";
        $lines = file($logPath);
        if ($lines) {
            $lastLines = array_slice($lines, -50);
            echo "<pre>" . htmlspecialchars(implode('', $lastLines)) . "</pre>";
        }
        break;
    }
}

// Also try to write a test log
error_log("TEST LOG ENTRY at " . date('Y-m-d H:i:s'));
echo "<p>Test log written. Check above.</p>";
?>
