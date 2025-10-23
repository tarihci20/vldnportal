<?php
$config = require 'config/config.php';
$pdo = new PDO('mysql:host='.$config['db_host'].';dbname='.$config['db_name'], $config['db_user'], $config['db_pass']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $pdo->query('SELECT * FROM vp_pages ORDER BY id ASC');
$pages = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($pages as $p) {
    echo 'ID: '.$p['id'].', Name: '.$p['page_name'].', Key: '.$p['page_key'].', URL: '.$p['page_url']."\n";
}
?>
