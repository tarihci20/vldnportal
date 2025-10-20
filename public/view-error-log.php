<?php
/**
 * View Error Log
 */

echo "<h1>Error Log Viewer</h1>";

$logFile = __DIR__ . '/error_log';

if (!file_exists($logFile)) {
    echo "<p style='color: red;'>Error log not found at: " . htmlspecialchars($logFile) . "</p>";
    echo "<p><a href='check-db.php'>Back to DB Check</a></p>";
    exit;
}

$size = filesize($logFile);
echo "<p>File size: " . ($size / 1024) . " KB</p>";
echo "<p><a href='check-db.php'>Back to DB Check</a></p>";
echo "<hr>";

// Show last 200 lines
$lines = file($logFile, FILE_IGNORE_NEW_LINES);
$lastLines = array_slice($lines, -200);

echo "<pre style='background: #f5f5f5; padding: 10px; overflow-x: auto; max-height: 600px;'>";
foreach ($lastLines as $line) {
    echo htmlspecialchars($line) . "\n";
}
echo "</pre>";
?>
