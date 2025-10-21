<?php
/**
 * Debug CSRF and Session
 */

// Session başlat
session_start();

echo "<h1>Session Debug</h1>";
echo "<p>Session ID: " . session_id() . "</p>";
echo "<p>Session CSRF Token: " . ($_SESSION['csrf_token'] ?? 'NOT SET') . "</p>";
echo "<p>Session CSRF Time: " . ($_SESSION['csrf_token_time'] ?? 'NOT SET') . "</p>";

echo "<h2>All Session Data:</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h2>POST Data:</h2>";
echo "<pre>";
print_r($_POST);
echo "</pre>";
?>
