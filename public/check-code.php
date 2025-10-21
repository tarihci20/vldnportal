<?php
/**
 * Debug: Verify Current Code
 */

require_once dirname(__DIR__) . '/config/constants.php';
require_once ROOT_PATH . '/vendor/autoload.php';
require_once ROOT_PATH . '/app/helpers/functions.php';
require_once ROOT_PATH . '/app/helpers/response.php';

echo "<h1>Code Version Check</h1>";

// Read StudentController
$controllerFile = ROOT_PATH . '/app/Controllers/StudentController.php';
$content = file_get_contents($controllerFile);

// Check if double url() fix is present
if (strpos($content, 'redirect(\'/students/\' . $studentId)') !== false) {
    echo "<p style='color: green;'><strong>✅ NEW CODE:</strong> Double url() fix is present</p>";
} elseif (strpos($content, 'redirect(url(\'/students/\' . $studentId))') !== false) {
    echo "<p style='color: red;'><strong>❌ OLD CODE:</strong> Double url() bug still present</p>";
} else {
    echo "<p style='color: orange;'><strong>❓ UNKNOWN:</strong> Cannot determine code version</p>";
}

// Show line around redirect in store()
echo "<h2>Store Method - Redirect Lines:</h2>";
$lines = file($controllerFile);
for ($i = 0; $i < count($lines); $i++) {
    if (strpos($lines[$i], 'Öğrenci başarıyla eklendi') !== false) {
        // Show context lines
        for ($j = max(0, $i - 2); $j <= min(count($lines) - 1, $i + 3); $j++) {
            echo "<pre style='background: #f5f5f5;'>";
            echo ($j + 1) . ": " . htmlspecialchars($lines[$j]);
            echo "</pre>";
        }
        break;
    }
}
?>
