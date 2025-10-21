<?php
/**
 * Form Submission Debugger
 * Shows what happens when POST request comes in
 */

echo "<h1>Form Submission Debugger</h1>";

// Show REQUEST info
echo "<h2>Current Request</h2>";
echo "<pre>";
echo "Method: " . $_SERVER['REQUEST_METHOD'] . "\n";
echo "URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "Script: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "</pre>";

// Show last error log entries
echo "<h2>Last Error Log Entries (tail -100)</h2>";

$error_log_file = 'error_log';
if (file_exists($error_log_file)) {
    $lines = file($error_log_file);
    $lastLines = array_slice($lines, -100);
    
    echo "<pre style='background:#f5f5f5; padding:10px; border:1px solid #ccc; overflow-x:auto; max-height:600px; font-size:12px;'>";
    foreach ($lastLines as $line) {
        // Highlight store() related lines
        if (strpos($line, 'store') !== false || strpos($line, 'SimpleStudent') !== false) {
            echo "<span style='background:yellow;'>" . htmlspecialchars($line) . "</span>";
        } else {
            echo htmlspecialchars($line);
        }
    }
    echo "</pre>";
} else {
    echo "<p style='color:red;'>Error log not found</p>";
}

// Show routes that might handle /simple-students
echo "<h2>Route Info</h2>";
echo "<pre>";
echo "Current URL might be mapped to: POST /simple-students\n";
echo "Controller: SimpleStudentController\n";
echo "Method: store()\n";
echo "</pre>";

// Show how to test
echo "<h2>Test Instructions</h2>";
echo "<ol>";
echo "<li>Fill the form at: <a href='https://vldn.in/portalv2/simple-students/create' target='_blank'>https://vldn.in/portalv2/simple-students/create</a></li>";
echo "<li>Use NEW TC number (not 55555555555 - that's already in DB)</li>";
echo "<li>Click Submit button</li>";
echo "<li>Refresh this page to see new log entries</li>";
echo "<li>Look for '=== SimpleStudentController::store()' in the logs</li>";
echo "</ol>";
?>
