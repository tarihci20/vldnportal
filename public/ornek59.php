<?php
echo "TEST FILE - Oluşturulma Zamanı: " . date('Y-m-d H:i:s');
echo "<br>";
echo "Git Commit Hash: ";
system('git rev-parse HEAD 2>/dev/null');
?>
