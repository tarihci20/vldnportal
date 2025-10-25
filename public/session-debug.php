<?php
/**
 * SESSION DEBUG - Ne oluyor?
 */
session_name('app_session');
session_start();

echo "<h1>Session Debug</h1>";
echo "<pre>";
echo "Session ID: " . session_id() . "\n";
echo "Session Status: " . session_status() . " (1=disabled, 2=active)\n";
echo "Session Save Path: " . session_save_path() . "\n\n";

echo "=== \$_SESSION içeriği ===\n";
print_r($_SESSION);

echo "\n=== \$_COOKIE içeriği ===\n";
print_r($_COOKIE);

echo "\n=== Test: Manuel session set ===\n";
$_SESSION['test'] = 'test123';
echo "Session'a 'test' eklendi: " . $_SESSION['test'] . "\n";

echo "</pre>";

echo "<hr>";
echo "<h2>Ana sayfaya dön ve tekrar buraya gel</h2>";
echo "<a href='/portalv2'>Ana Sayfa</a> | ";
echo "<a href='/portalv2/perm-test.php'>Perm Test</a>";
?>
