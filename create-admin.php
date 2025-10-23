<?php
require 'config/constants.php';

// Şifreyi hash'le
$password = 'aci2406717';
$hash = password_hash($password, PASSWORD_BCRYPT);

echo "Password: " . $password . "\n";
echo "Hash: " . $hash . "\n";
echo "\n";
echo "SQL komutu:\n";
echo "INSERT INTO vp_users (username, email, password_hash, full_name, role_id, is_active, created_at, updated_at)\n";
echo "VALUES (\n";
echo "    'tarihci20',\n";
echo "    'tarihci20@example.com',\n";
echo "    '" . $hash . "',\n";
echo "    'Admin Kullanıcı',\n";
echo "    1,\n";
echo "    1,\n";
echo "    NOW(),\n";
echo "    NOW()\n";
echo ");\n";
?>
