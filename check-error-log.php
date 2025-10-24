<?php
// Production error log'u kontrol et
$errorLogPaths = [
    '/home/vildacgg/vldn.in/portalv2/storage/logs/php_error.log',
    '/home/vildacgg/vldn.in/portalv2/storage/logs/error.log',
    '/home/vildacgg/public_html/portalv2/storage/logs/php_error.log',
    '/home/vildacgg/vldn.in/error_log',
    '/home/vildacgg/public_html/error_log',
];

echo "<h1>Error Log Search Results</h1>";
echo "<p>Current date: " . date('Y-m-d H:i:s') . "</p>";

foreach ($errorLogPaths as $path) {
    echo "<h3>Checking: $path</h3>";
    
    if (file_exists($path)) {
        echo "<p style='color: green;'>✓ File exists</p>";
        $size = filesize($path);
        echo "<p>Size: " . number_format($size) . " bytes</p>";
        
        // Last 50 lines
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines) {
            echo "<p>Last 50 lines:</p>";
            echo "<pre style='background: #f0f0f0; padding: 10px; max-height: 600px; overflow-y: auto;'>";
            $lastLines = array_slice($lines, -50);
            foreach ($lastLines as $line) {
                echo htmlspecialchars($line) . "\n";
            }
            echo "</pre>";
        }
    } else {
        echo "<p style='color: red;'>✗ File not found</p>";
    }
    echo "<hr>";
}
?>
