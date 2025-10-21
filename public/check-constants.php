<?php
/**
 * Check current constants
 */
echo "<h1>Current Constants</h1>";
echo "<pre>";
echo "BASE_URL: " . (defined('BASE_URL') ? BASE_URL : 'NOT DEFINED') . "\n";
echo "BASE_PATH: " . (defined('BASE_PATH') ? BASE_PATH : 'NOT DEFINED') . "\n";
echo "APP_PATH: " . (defined('APP_PATH') ? APP_PATH : 'NOT DEFINED') . "\n";
echo "ROOT_PATH: " . (defined('ROOT_PATH') ? ROOT_PATH : 'NOT DEFINED') . "\n";
echo "</pre>";

// Also check what the redirect would be
$redirect_url = (defined('BASE_URL') ? BASE_URL : 'NOT DEFINED') . '/students';
echo "<p>Redirect would be: <strong>$redirect_url</strong></p>";
?>
